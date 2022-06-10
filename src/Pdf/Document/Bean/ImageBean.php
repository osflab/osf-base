<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Image\ImageHelper;

/**
 * A picture
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 1 nov. 2013
 * @package osf
 * @subpackage pdf
 */
class ImageBean extends AbstractPdfDocumentBean
{
    protected $imageFile;
    protected $colors;
    
    public function __construct($imageFile)
    {
        $this->setImageFile($imageFile);
    }
    
    public function getImageFile()
    {
        return $this->imageFile;
    }
    
    /**
     * Set the picture file
     * @param string $imageFile
     * @return \Osf\Pdf\Document\Bean\ImageBean
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        return $this;
    }
    
    /**
     * Set picture colors
     * @param array $colors
     * @return \Osf\Pdf\Document\Bean\ImageBean
     */
    public function setColors(array $colors)
    {
        $this->colors = $colors;
        return $this;
    }
    
    /**
     * Get picture colors (build colors if not setted)
     */
    public function getColors(): ?array
    {
        if (!is_array($this->colors)) {
            $this->colors = ImageHelper::getColors($this->imageFile);
        }
        return $this->colors;
    }
}