<?php
namespace Osf\Pdf\Tcpdf;

use Osf\Pdf\Document\Bean\AddressBean;
use Osf\Pdf\Document\Bean\BaseDocumentBean;

/**
 * TCPDF Proxy : direct access to TCPDF + helpers
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class Document extends Proxy
{
    const MARGIN_LEFT  = 16;
    const MARGIN_TOP   = 14;
    const MARGIN_RIGHT = self::MARGIN_LEFT;
    
    const ADDRESS_X = 107;
    const ADDRESS_Y = 50;
    const ADDRESS_H = 20;
    
    /**
     * @var \Osf\Pdf\Document\Bean\BaseDocumentBean
     */
    protected $bean;
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->setCreator('OpenStates doc generator')
            ->setAuthor('OpenStates')
            ->setMargins(self::MARGIN_LEFT, self::MARGIN_TOP, self::MARGIN_RIGHT, true)
            // ->setDefaultFont('helvetica_light', 'helvetica_light')->setFont('helvetica_light')
            // ->setDefaultFont('times', 'times')->setFont('times')
            ->setHeaderMargin(self::MARGIN_TOP);
    }
    
    /**
     * @param AddressBean $address
     * @param int $fontSize
     * @return $this
     */
    public function addAddressWindow(AddressBean $address, int $fontSize = 11, string $additionalLine = '')
    {
        $additionalLine = $additionalLine ? $additionalLine . "\n" : '';
        $this->setFont($this->tcpdf->getDefaultFont(), 'B', $fontSize);
        $this->setAbsXY(self::ADDRESS_X, self::ADDRESS_Y);
        $this->cell(0, 0, $address->getTitle(), 0, false, 'L', false, '', false, true, 'A', 'T');
        $this->setFont($this->tcpdf->getDefaultFontLight(), '', $fontSize);
        $this->multiCell(0, 0, $additionalLine . $address->getComputedAddress(), 0, 'L', false, 1, self::ADDRESS_X, $this->getY() + $fontSize / 2.2, true, true, false, false, self::ADDRESS_H, 'T', true);
        $top = max($this->getMargins()['top'], $this->getY() + 4);
        $this->setXY($this->getMargins()['left'], $top);
        return $this;
    }
    
    /**
     * Display objects, attns, etc.
     * @param array $libs
     * @param bool $allBold
     * @return $this
     */
    public function writeLibs(array $libs, bool $allBold = false)
    {
        $allBold && $this->setFont($this->getDefaultFont(), 'B');
        foreach ($libs as $key => $lib) {
            if (!$lib) { continue; }
            $allBold || $this->setFont($this->getDefaultFont(), 'B');
            $this->setX($this->getMargins()['left'] - 1); // Pb de décalage avec write()
            $this->write(0, $key . ' ', '', false, 'L', false, 0, false, true);
            $allBold || $this->setFont($this->getDefaultFontLight());
            $this->multiCell(0, 0, $lib, 0, 'L', false, 1);
            $this->setY($this->getY() + 1);
        }
        $allBold && $this->setFont($this->getDefaultFont(), '');
        return $this;
    }
    
    /**
     * Horizontal line
     * @return $this
     */
    public function hr($space = null, bool $colored = false)
    {
        static $color = null;
        
        if ($colored) {
            if (!$color) {
                $color = $this->getDefaultColor();
            }
            $this->setDrawColor($color['r'], $color['g'], $color['b']);
        }
        $space = $space === null ? 0.5 : (float) $space;
        $this->setY($this->getY() + 0.5 + $space);
        $this->line($this->getMargins()['left'], $this->getY(), $this->getPageWidth() - $this->getMargins()['right'], $this->getY());
        $space && $this->setY($this->getY() + $space);
        $colored && $this->setDrawColor(0);
        return $this;
    }
    
    /**
     * @return float
     */
    public function getPageWidthWithoutMargins()
    {
        return $this->getPageWidth() - $this->getMargins()['left'] - $this->getMargins()['right'];
    }
    
    /**
     * @param type $data
     * @param type $x
     * @param type $y
     * @param type $w
     * @param type $h
     * @return $this
     */
    public function qrCode($data, $x, $y, $w, $h)
    {
        $style = [
            'border' => 0,
            'vpadding' => 0,
            'hpadding' => 0,
            'fgcolor' => [0,0,0],
            'bgcolor' => false,
            'module_width' => 1,
            'module_height' => 1
        ];

        $this->write2DBarcode($data, 'QRCODE,H', $x, $y, $w, $h, $style, 'N');
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function setBean(BaseDocumentBean $bean)
    {
        $this->bean = $bean;
        return $this;
    }

    /**
     * @return \Osf\Pdf\Document\Bean\BaseDocumentBean
     */
    public function getBean()
    {
        return $this->bean;
    }
}