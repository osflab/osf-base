<?php
namespace Osf\Image;

use Osf\Exception\ArchException;
use Osf\Exception\DisplayedException;
use Osf\Log\LogProxy as Log;
use Osf\Container\OsfContainer as Container;
use Imagick;

/**
 * Image manipulation tools
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 1 nov. 2013
 * @package osf
 * @subpackage image
 */
class ImageHelper
{
    const MAX_IMG_SIZE = 10000000; // 10mb
    const MIN_IMG_BORDER = 200;
    const IMAGE_TRIM_COEF  = 10000;
    //const IMAGE_RESOLUTION = 1152;
    const IMAGE_RESOLUTION = 300;
    const DEFAULT_PERIMETER = 1200;
    
    /**
     * Retrieves the dominant colors of an image
     * @param string $imageFile
     * @param int $colorCount
     * @param bool $skipGray
     * @param int $scaleBeforeCalculation
     * @param bool $hex
     * @param float $colorDegree
     * @throws ArchException
     * @task [IMG] factoriser la recherche des couleurs en une seule itération plus rapide
     * @return multitype:string multitype:boolean
     */
    public static function getColors(
            $imageFile = null, // File that contains picture...
            $imageContent = null, // ...or image content stream
            $colorCount = 10, // Colors count
            $skipGray = true, // Remove gray shades
            $scaleBeforeCalculation = 100, // Image reduction factor, in order to speed up the processing
            $hex = false, // Result in hexadecimal
            $highColorDegree = 0.6, // Degree of lightness to reject
            $lowColorDegree = 0.8, // Degree of darkness to reject
            $grayDegree = 0.85, // Rejection of too gray colors
            $colorDensityMin = 1) // At least X pixels of the same color
    {
        if ($highColorDegree < 0 || $highColorDegree > 1) {
            throw new ArchException("High color degree value must be between 0 and 1");
        }
        if ($lowColorDegree < 0 || $lowColorDegree > 1) {
            throw new ArchException("Low color degree value must be between 0 and 1");
        }
        if ((!$imageFile && !$imageContent) || ($imageFile && $imageContent)) {
            throw new ArchException('Set image file OR image content');
        }
        if ($imageFile) {
            $extensions = [
                    'jpg'  => 'jpeg',
                    'jpg'  => 'jpeg',
                    'png'  => 'png',
                    'gif'  => 'gif',
                    'bmp'  => 'wbmp',
                    'wbmp' => 'wbmp',
                    'xbm'  => 'xbm'
            ];
            $extension = substr(strtolower($imageFile), -3, 3);
            if (!isset($extensions[$extension])) {
                throw new ArchException('Unknown image type');
            }
            $function = 'imagecreatefrom' . $extensions[$extension];
            $image = $function($imageFile);
        } else {
            $image = imagecreatefromstring($imageContent);
        }
        if ($scaleBeforeCalculation) {
            [$width, $height] = $imageFile ? getimagesize($imageFile) : getimagesizefromstring($imageContent);
            if ($width > $height) {
                $newWidth = $scaleBeforeCalculation;
                $newHeight = round($height * ($scaleBeforeCalculation / $width), 0);
            } else {
                $newWidth = round($width * ($scaleBeforeCalculation / $height), 0);
                $newHeight = $scaleBeforeCalculation;
            }
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $image = $newImage;
            //imagepng($image, '/tmp/test.png');
        }
        
        $colors = [];
        $imagesx = imagesx($image);
        for ($x = 0; $x < $imagesx; $x++) {
            $imagesy = imagesy($image);
            for ($y = 0; $y < $imagesy; $y++) {
                $rgb = imagecolorat($image, $x, $y);
                if (!isset($colors[$rgb])) {
                    $colors[$rgb] = 1;
                } else {
                    $colors[$rgb]++;
                }
            }
        }
        arsort($colors);
        $retVal = [];
        $cpt = 0;
        $highColor = 255 * 3 * $highColorDegree;
        $lowColor = (255 - (255 * $lowColorDegree)) * 3;
        foreach ($colors as $color => $iterations) {
            
            if ($iterations < $colorDensityMin) {
                break;
            }
            
            $red = ($color >> 16) & 0xFF;
            $green = ($color >> 8) & 0xFF;
            $blue =  $color & 0xFF;
            
            // Gray color
            if ($skipGray) {
            
                // Full gray color
                if ($red == $green && $green == $blue) {
                    continue;
                }
                
                // Too gray color
                $gap = floor(255 * (1 - $grayDegree));
                if ($skipGray && abs($red - $blue) < $gap
                              && abs($red - $green) < $gap
                              && abs($green - $blue) < $gap) {
                    continue;
                }
            }
            
            // Too high color
            if (($red + $green + $blue) > $highColor) {
                continue;
            }
            
            // Too low color
            if (($red + $green + $blue) < $lowColor) {
                continue;
            }
            
            if ($hex) {
                $retVal[] = sprintf("%'02s%'02s%'02s", dechex($red), dechex($green), dechex($blue));
            } else {
                $retVal[] = ['r' => $red, 'g' => $green, 'b' => $blue];
            }
            $cpt++;
            if ($cpt >= $colorCount) {
                break;
            }
        }
        return $retVal;
    }
    
    /**
     * Load any image, remove the edges, resize using the mentionned perimeter and get the Imagick instance
     * @param string $imageFile
     * @param integer $perimeter
     * @param string $outputType
     * @return \Imagick
     */
    public static function getImageContentFromBlob($imageBlob, $perimeter = self::DEFAULT_PERIMETER)
    {
        $imagick = self::newImagick();
        $imagick->readimageblob($imageBlob);
        return self::buildImageContent($imagick, $perimeter);
    }
    
    /**
     * Load any image, remove the edges, resize using the mentionned perimeter and get the Imagick instance
     * @param string $image
     * @param integer $perimeter
     * @param string $outputType
     * @return \Imagick
     */
    public static function getImageContent($image, $perimeter = self::DEFAULT_PERIMETER)
    {
        if ($image instanceof Imagick) {
            $imagick = $image;
        } else {
            $imagick = self::newImagick();
            $imagick->readimage($image);
        }
        return self::buildImageContent($imagick, $perimeter);
    }
    
    /**
     * @return Imagick
     */
    protected static function newImagick()
    {
        $img = new Imagick();
        $img->setresolution(self::IMAGE_RESOLUTION, self::IMAGE_RESOLUTION);
        $img->setbackgroundcolor('white');
        return $img;
    }
    
    /**
     * Improves the image in order to use it in PDF documents
     * @param Imagick $img
     * @param int $perimeter
     * @return Imagick
     */
    protected static function buildImageContent(Imagick $img, $perimeter):Imagick
    {
        //$img->setimagebackgroundcolor(Imagick::COLOR_CYAN);
        $img->setimageformat('PNG');
        $img->trimimage(self::IMAGE_TRIM_COEF);
        $iw = $img->getimagewidth();
        $ih = $img->getimageheight();
        $niw = ceil(($perimeter / (($iw + $ih) * 2)) * $iw);
        $nih = ceil(($perimeter / (($iw + $ih) * 2)) * $ih);
        $img->setimageresolution($niw, $nih);
        if ($niw < $iw) {
            //$img->resampleImage($niw,$nih,imagick::FILTER_UNDEFINED,0);
            $img->resizeimage($niw, $nih, Imagick::FILTER_LANCZOSRADIUS, 1);
//            $img->adaptiveresizeimage($niw, $nih, true);
        }
        $img->transformimagecolorspace(Imagick::COLORSPACE_CMYK);
        $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        //$img->thumbnailimage(ceil(($perimeter / (($iw + $ih) * 2)) * $iw), null);
        return $img;
    }
    
    /**
     * Check uploaded images
     * @param array $image
     * @param bool $checkUploaded only used for unit tests
     * @return array
     * @throws DisplayedException
     * @todo refactoring
     */
    public static function checkImageFromPostFile(array $image, bool $checkUploaded = true): array
    {
        // Structure de retour
        $output = ['img' => null, 'error' => null, 'warning' => null];
        
        // Lecture des erreurs de chargement
        if ($image['error'] !== UPLOAD_ERR_OK) {
            switch ($image['error']) {
                case UPLOAD_ERR_FORM_SIZE :
                case UPLOAD_ERR_INI_SIZE :
                    $output['error'] = __("La taille de votre fichier est trop importante.");
                    break;
                case UPLOAD_ERR_PARTIAL : 
                    $output['error'] = __("Votre fichier n'a pas été chargé en entier. Cela est peut-être dû à une connexion trop lente. Veuillez réessayer.");
                    break;
                default :
                    $output['error'] = __("Une erreur a été détectée lors de l'envoi de votre fichier. Veuillez réessayer.");
            }
            self::log('Uploaded file error', $image);
        } else if ($checkUploaded && !is_uploaded_file($image['tmp_name'])) {
            self::log('Not an uploaded file: ' . $image['tmp_name'], $image, true);
            $output['error'] = __("Ce fichier ne peut être traité. Veuillez nous excuser pour la gêne occasionnée.");
        } else if (!preg_match('/^image\/.*$/', $image['type'])) {
            $output['error'] = __("Ce fichier ne semble pas être une image valide. Veuillez sélectionner un fichier contenant une image.");
        } else if ($image['size'] > self::MAX_IMG_SIZE) {
            $output['error'] = __("Votre fichier est trop lourd, veuillez vous assurer qu'il fasse moins de 10mb.");
        } 
        
        // Pas d'erreur, on lit l'image
        else {
            try {
                
                $imagick = self::newImagick();
                
                // Tentative de conversion par le service inkscape
                $inkUrl = Container::getConfig()->getConfig('inkscape', 'server');
                $isSvg = strpos($image['type'], 'svg');
                if ($inkUrl && $isSvg) {
                    $png = InkscapeProxy::call($inkUrl, file_get_contents($image['tmp_name']));
                    if ($png && $png != '0') {
                        $pngFile = tempnam(sys_get_temp_dir(), 'svg-') . '.png';
                        file_put_contents($pngFile, $png);
                        $imagick->readimage($pngFile);
                        unlink($pngFile);
                    } else {
                        throw new DisplayedException(__("Nous avons tenté sans succès de convertir votre image vectorielle. Veuillez essayer avec un autre format d'image et nous excuser pour la gêne occasionnée."));
                    }
                } 
                
                // Lecture du fichier par imagick (hors inskape)
                if (!isset($pngFile)) {
                    $imagick->readimage($image['tmp_name']);
                }
                
                // Imagick a-t-il bien lu l'image ?
                $geometry = $imagick->getimagegeometry();
                if (!isset($geometry['width'])) {
                    throw new DisplayedException(__("Impossible de lire votre image. Veuillez vous assurer qu'elle est lisible et non corrompue."));
                }
                
                // On check la taille pour mettre un avertissement
                $vector = !isset($pngFile) && ($isSvg || strpos($image['type'], 'eps'));
                $tooSmall = $geometry['width'] + $geometry['height'] < self::MIN_IMG_BORDER + self::MIN_IMG_BORDER;
                if (!$vector && $tooSmall) {
                    $size = $geometry['width'] . 'x' . $geometry['height'];
                    $minSize = self::MIN_IMG_BORDER . 'x' . self::MIN_IMG_BORDER;
                    $output['warning'] = sprintf(__("Votre image est de petite taille (%s). Remplacez-la par une image de résolution supérieure (au moins %s) pour être sûr qu'elle s'affichera correctement dans vos documents."), $size, $minSize);
                }
                $output['img'] = $imagick;
            } catch (DisplayedException $e) {
                self::log('Image (displayed): ' . $e->getMessage(), $e);
                $output['error'] = $e->getMessage();
            } catch (\Exception $e) {
                self::log('Traitement image: ' . $e->getMessage(), $e);
                $output['error'] = __("Une erreur est survenue pendant le traitement de votre image. Veuillez vous assurer qu'elle est lisible et non corrompue.");
            }
        }
        return $output;
    }
    
    /**
     * Internal log
     * @param string $msg
     * @param type $details
     * @return void
     */
    protected static function log(string $msg, $details = null, bool $hack = false): void
    {
        if (class_exists('Log')) {
            if ($hack) {
                Log::hack($msg, $details);
            } else {
                Log::error($msg, 'IMG', $details);
            }
        }
    }
}
