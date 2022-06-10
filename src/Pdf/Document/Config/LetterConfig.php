<?php
namespace Osf\Pdf\Document\Config;

/**
 * Letter PDF document configuration file
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 31 oct. 2013
 * @package osf
 * @subpackage pdf
 */
class LetterConfig extends BaseDocumentConfig
{
    protected $maxFontSize = 10;
    protected $minFontSize = 6;
    
    /**
     * Maximum font size
     * @return number
     */
    public function getMaxFontSize()
    {
        return $this->maxFontSize;
    }

    /**
     * Minimum font size
     * @return number
     */
    public function getMinFontSize()
    {
        return $this->minFontSize;
    }

    /**
     * Maximum font size
     * @param number $maxFontSize
     * @return \Osf\Pdf\Document\Config\LetterConfig
     */
    public function setMaxFontSize($maxFontSize)
    {
        $this->maxFontSize = $maxFontSize;
        return $this;
    }

    /**
     * Minimum font size
     * @param number $minFontSize
     * @return \Osf\Pdf\Document\Config\LetterConfig
     */
    public function setMinFontSize($minFontSize)
    {
        $this->minFontSize = $minFontSize;
        return $this;
    }

}