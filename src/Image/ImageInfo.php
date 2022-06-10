<?php
namespace Osf\Image;

/**
 * Bean with information about a picture
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 15 nov. 2013
 * @package osf
 * @subpackage image
 */
class ImageInfo
{
    const QUALITY_POOR = 'poor';
    const QUALITY_GOOD = 'good';
    
    protected $colors;
    protected $format;
    protected $width;
    protected $height;
    protected $quality;

    /**
     * @return array
     */
    public function getColors() {
        return $this->colors;
    }

    /**
     * @param array $colors
     * @return \Image\ImageInfo
     */
    public function setColors(array $colors) {
        $this->colors = $colors;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param string $format
     * @return \Image\ImageInfo
     */
    public function setFormat($format) {
        $this->format = $format;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @param int $width
     * @return \Image\ImageInfo
     */
    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param int $height
     * @return \Image\ImageInfo
     */
    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuality() {
        return $this->quality;
    }

    /**
     * @param string $quality
     * @return \Image\ImageInfo
     */
    public function setQuality($quality) {
        $this->quality = $quality;
        return $this;
    }
}