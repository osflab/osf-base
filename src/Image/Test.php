<?php
namespace Osf\Image;

use Osf\Test\Runner as OsfTest;

/**
 * Filters unit tests
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        // Requirements
        $imageFile = __DIR__ . '/Test/simplemanager.png';
        self::assert(extension_loaded('imagick'), 'Imagick extension is required');
        
        // Colors
        $colors = ImageHelper::getColors($imageFile);
        self::assertEqual($colors[0], ['r' => 0, 'g' => 121, 'b' => 255], 'Image main color detection failed');
        self::assertEqual($colors[1], ['r' => 0, 'g' => 175, 'b' => 0], 'Image secondary color detection failed');
        
        // Imagick checking
        $image = [
            'tmp_name' => $imageFile,
            'error' => UPLOAD_ERR_OK,
            'type' => 'image/png',
            'size' => filesize($imageFile)
        ];
        $result = ImageHelper::checkImageFromPostFile($image, false);
        self::assert(array_key_exists('error', $result) && !$result['error']);
        self::assert(array_key_exists('warning', $result) && !$result['warning']);
        if (self::assert(array_key_exists('img', $result) && $result['img'] instanceof \Imagick)) {
            $imagick = $result['img'];
            self::assertEqual($imagick->getimagebackgroundcolor()->getcolor(), ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 1]);
            self::assertEqual(basename($imagick->getimagefilename()), 'simplemanager.png');
        }
        
        return self::getResult();
    }
}