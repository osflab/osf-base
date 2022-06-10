<?php
namespace Osf\Pdf\ZendPdf;

/**
 * Zend PDF Page with text wrap and other features
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 26 sept. 2013
 * @package osf
 * @subpackage pdf
 * @deprecated since version 3.0.0 - Zend Pdf is now deprecated
 */
class Page extends \ZendPdf\Page
{

    /**
     * FR: Cette fonction retourne la largeur typographique d'un string.
     * @param string $text_line le texte à utiliser
     * @return integer la largeur du texte
     */
    protected function _getTextWidth($line)
    {
        $font = $this->getFont();
        $fontSize = $this->getFontSize();
        $glyphs = $charsOrd = array();
        $chars = str_split($line, 1);
        foreach ($chars as $character) {
            $charsOrd[] = ord($character);
        }
        $glyphs = $font->glyphNumbersForCharacters($charsOrd);
        $widths = $font->widthsForGlyphs($glyphs);
        $totalWidth = 0;
        foreach ($widths as $charWidth) {
            $totalWidth += $charWidth;
        }
        return ($totalWidth / 1000) * $fontSize;
    }

    /**
     * FR: Dessine du texte aligné à droite de $x
     */
    public function drawTextAlignRight ($text, $x, $y)
    {
        $width = $this->_getTextWidth($text);
        $this->drawText($text, $x - $width, $y);
    }

    /**
     * FR: Dessine du texte centré sur $x
     */
    public function drawTextAlignCenter ($text, $x, $y)
    {
        $width = $this->_getTextWidth($text);
        $this->drawText($text, $x - ($width / 2), $y);
    }
}