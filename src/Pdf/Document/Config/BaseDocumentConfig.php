<?php
namespace Osf\Pdf\Document\Config;

/**
 * Configuration file for base pdf document (top config)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 31 oct. 2013
 * @package osf
 * @subpackage pdf
 */
class BaseDocumentConfig
{
    protected $marginLeft   = 18;
    protected $marginTop    = 18;
    protected $marginRight  = 18;
    protected $marginBottom = 18;
    
    protected $fontFamily = [];
    protected $fontSize = 11;
    
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    public function getMarginTop()
    {
        return $this->marginTop;
    }

    public function getMarginRight()
    {
        return $this->marginRight;
    }

    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    public function getFontFamily()
    {
        return isset($this->fontFamily['regular']) ? $this->fontFamily['regular'] : null;
    }

    public function getFontFamilyLight()
    {
        return isset($this->fontFamily['light']) ? $this->fontFamily['light'] : null;
    }

    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param number $marginLeft
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function setMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;
        return $this;
    }

    /**
     * @param number $marginTop
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function setMarginTop($marginTop)
    {
        $this->marginTop = $marginTop;
        return $this;
    }

    /**
     * @param number $marginRight
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function setMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;
        return $this;
    }

    /**
     * @param number $marginBottom
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function setMarginBottom($marginBottom)
    {
        $this->marginBottom = $marginBottom;
        return $this;
    }

    /**
     * @param string $fontFamily
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function setFontFamily($regular, $light = null)
    {
        $this->fontFamily = [
            'regular' => $regular,
            'light' => $light
        ];
        return $this;
    }

    /**
     * @param number $fontSize
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }
}