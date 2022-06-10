<?php
namespace Osf\Pdf\Tcpdf;

use Osf\Pdf\Tcpdf;
use Osf\Pdf\Document\Bean\ContactBean;

/**
 * TCPDF Proxy
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class Proxy
{
    /**
     * @var \Osf\Pdf\Tcpdf 
     */
    protected $tcpdf;
    
    /**
     * Transaction overload
     * @var \Osf\Pdf\Tcpdf 
     */
    protected $tcpdfSaved;
    
    // ADDONS
    
    /**
     * @param string $regular
     * @param string $light
     * @return $this
     */
    public function setDefaultFont(string $regular, string $light = null)
    {
        $this->tcpdf->setDefaultFont($regular, $light);
        return $this;
    }
    
    public function getDefaultFont()
    {
        return $this->tcpdf->getDefaultFont();
    }
    
    public function getDefaultFontLight()
    {
        return $this->tcpdf->getDefaultFontLight();
    }
    
    /**
     * Default alternative color (for titles, links)
     * @param int $r
     * @param int $g
     * @param int $b
     * @return $this
     */
    public function setDefaultColor(int $r, int $g, int $b)
    {
        $this->tcpdf->setDefaultColor($r, $g, $b);
        return $this;
    }
    
    public function getDefaultColor()
    {
        return $this->tcpdf->getDefaultColor();
    }
    
    /**
     * Alternative color for links (emails, urls)
     * @param int $r
     * @param int $g
     * @param int $b
     * @return $this
     */
    public function setLinkColor(int $r, int $g, int $b)
    {
        $this->tcpdf->setLinkColor($r, $g, $b);
        return $this;
    }
    
    /**
     * Header and footer data
     * @param \Osf\Pdf\Tcpdf\ContactBean $contact
     * @param string $place
     * @param string $date
     * @param bool $confidential
     * @param bool $createdBy
     * @return $this
     */
    public function setHeadFootInfo(ContactBean $contact, string $place = null, string $date = null, bool $confidential = true, bool $createdBy = true)
    {
        $this->tcpdf->setHeadFootInfo($contact, $place, $date, $confidential, $createdBy);
        return $this;
    }
    
    /**
     * @param $dateLabel string|null
     * @return $this
     */
    public function setDateLabel($dateLabel)
    {
        $this->tcpdf->setDateLabel($dateLabel);
        return $this;
    }
    
    /**
     * Titles for invoices like documents
     * @param string $title
     * @param string $subTitle
     * @param array $titleLines
     * @return $this
     */
    public function setTitles($title, $subTitle = null, array $titleLines = [])
    {
        $this->tcpdf->setTitles($title, $subTitle, $titleLines);
        return $this;
    }

    /**
     * Set params (category "document" de la configuration de l'utilisateur)
     * @return $this
     */
    public function setParams($params)
    {
        return $this->tcpdf->setParams($params);
    }

    public function getParams()
    {
        return $this->tcpdf->getParams();
    }
    
    // TCPDF
    
    /**
     * This is the class constructor.
     * It allows to set up the page format, the orientation and the measure unit used in all the methods (except for the font sizes).
     * IMPORTANT: Please note that this method sets the mb_internal_encoding to ASCII, so if you are using the mbstring module functions with TCPDF you need to correctly set/unset the mb_internal_encoding when needed.
     * @param $orientation (string) page orientation. Possible values are (case insensitive):<ul><li>P or Portrait (default)</li><li>L or Landscape</li><li>'' (empty string) for automatic orientation</li></ul>
     * @param $unit (string) User measure unit. Possible values are:<ul><li>pt: point</li><li>mm: millimeter (default)</li><li>cm: centimeter</li><li>in: inch</li></ul><br />A point equals 1/72 of inch, that is to say about 0.35 mm (an inch being 2.54 cm). This is a very common unit in typography; font sizes are expressed in that unit.
     * @param $format (mixed) The format used for pages. It can be either: one of the string values specified at getPageSizeFromFormat() or an array of parameters specified at setPageFormat().
     * @param $unicode (boolean) TRUE means that the input text is unicode (default = true)
     * @param $encoding (string) Charset encoding (used only when converting back html entities); default is UTF-8.
     * @param $diskcache (boolean) DEPRECATED FEATURE
     * @param $pdfa (boolean) If TRUE set the document to PDF/A mode.
     * @public
     * @see getPageSizeFromFormat(), setPageFormat()
     */
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        $this->tcpdf = new Tcpdf($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }
    
    /**
     * Set the units of measure for the document.
     * @param $unit (string) User measure unit. Possible values are:<ul><li>pt:
     * point</li><li>mm: millimeter (default)</li><li>cm: centimeter</li><li>in:
     * inch</li></ul><br />A point equals 1/72 of inch, that is to say about 0.35 mm
     * (an inch being 2.54 cm). This is a very common unit in typography; font sizes
     * are expressed in that unit.
     * @public
     * @since 3.0.015 (2008-06-06)
     * @return $this
     */
    public function setPageUnit($unit)
    {
        $this->tcpdf->setPageUnit($unit);
        return $this;
    }

    /**
     * Set page orientation.
     * @param $orientation (string) page orientation. Possible values are (case
     * insensitive):<ul><li>P or Portrait (default)</li><li>L or Landscape</li><li>''
     * (empty string) for automatic orientation</li></ul>
     * @param $autopagebreak (boolean) Boolean indicating if auto-page-break mode
     * should be on or off.
     * @param $bottommargin (float) bottom margin of the page.
     * @public
     * @since 3.0.015 (2008-06-06)
     * @return $this
     */
    public function setPageOrientation($orientation, $autopagebreak = '', $bottommargin = '')
    {
        $this->tcpdf->setPageOrientation($orientation, $autopagebreak, $bottommargin);
        return $this;
    }

    /**
     * Set regular expression to detect withespaces or word separators.
     * The pattern delimiter must be the forward-slash character "/".
     * Some example patterns are:
     * <pre>
     * Non-Unicode or missing PCRE unicode support: "/[^\S\xa0]/"
     * Unicode and PCRE unicode support: "/(?!\xa0)[\s\p{Z}]/u"
     * Unicode and PCRE unicode support in Chinese mode:
     * "/(?!\xa0)[\s\p{Z}\p{Lo}]/u"
     * if PCRE unicode support is turned ON ("\P" is the negate class of "\p"):
     *      \s     : any whitespace character
     *      \p{Z}  : any separator
     *      \p{Lo} : Unicode letter or ideograph that does not have lowercase and
     * uppercase variants. Is used to chunk chinese words.
     *      \xa0   : Unicode Character 'NO-BREAK SPACE' (U+00A0)
     * </pre>
     * @param $re (string) regular expression (leave empty for default).
     * @public
     * @since 4.6.016 (2009-06-15)
     * @return $this
     */
    public function setSpacesRE($re = '/[^\\S\\xa0]/')
    {
        $this->tcpdf->setSpacesRE($re);
        return $this;
    }

    /**
     * Enable or disable Right-To-Left language mode
     * @param $enable (Boolean) if true enable Right-To-Left language mode.
     * @param $resetx (Boolean) if true reset the X position on direction change.
     * @public
     * @since 2.0.000 (2008-01-03)
     * @return $this
     */
    public function setRTL($enable, $resetx = true)
    {
        $this->tcpdf->setRTL($enable, $resetx);
        return $this;
    }

    /**
     * Return the RTL status
     * @return boolean
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getRTL()
    {
        return $this->tcpdf->getRTL();
    }

    /**
     * Force temporary RTL language direction
     * @param $mode (mixed) can be false, 'L' for LTR or 'R' for RTL
     * @public
     * @since 2.1.000 (2008-01-09)
     * @return $this
     */
    public function setTempRTL($mode)
    {
        $this->tcpdf->setTempRTL($mode);
        return $this;
    }

    /**
     * Return the current temporary RTL status
     * @return boolean
     * @public
     * @since 4.8.014 (2009-11-04)
     */
    public function isRTLTextDir()
    {
        return $this->tcpdf->isRTLTextDir();
    }

    /**
     * Set the last cell height.
     * @param $h (float) cell height.
     * @author Nicola Asuni
     * @public
     * @since 1.53.0.TC034
     * @return $this
     */
    public function setLastH($h)
    {
        $this->tcpdf->setLastH($h);
        return $this;
    }

    /**
     * Return the cell height
     * @param $fontsize (int) Font size in internal units
     * @param $padding (boolean) If true add cell padding
     * @public
     */
    public function getCellHeight($fontsize, $padding = true)
    {
        return $this->tcpdf->getCellHeight($fontsize, $padding);
    }

    /**
     * Reset the last cell height.
     * @public
     * @since 5.9.000 (2010-10-03)
     * @return $this
     */
    public function resetLastH()
    {
        $this->tcpdf->resetLastH();
        return $this;
    }

    /**
     * Get the last cell height.
     * @return last cell height
     * @public
     * @since 4.0.017 (2008-08-05)
     */
    public function getLastH()
    {
        return $this->tcpdf->getLastH();
    }

    /**
     * Set the adjusting factor to convert pixels to user units.
     * @param $scale (float) adjusting factor to convert pixels to user units.
     * @author Nicola Asuni
     * @public
     * @since 1.5.2
     * @return $this
     */
    public function setImageScale($scale)
    {
        $this->tcpdf->setImageScale($scale);
        return $this;
    }

    /**
     * Returns the adjusting factor to convert pixels to user units.
     * @return float adjusting factor to convert pixels to user units.
     * @author Nicola Asuni
     * @public
     * @since 1.5.2
     */
    public function getImageScale()
    {
        return $this->tcpdf->getImageScale();
    }

    /**
     * Returns an array of page dimensions:
     * <ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['w'] = page width in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['h'] = height in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['wk'] = page width in
     * user units</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['hk'] = page height
     * in user units</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['tm'] = top
     * margin</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['bm'] = bottom
     * margin</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['lm'] = left
     * margin</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['rm'] = right
     * margin</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['pb'] = auto page
     * break</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['or'] = page
     * orientation</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['olm'] = original
     * left margin</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['orm'] = original
     * right margin</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['Rotate'] = The
     * number of degrees by which the page shall be rotated clockwise when displayed or
     * printed. The value shall be a multiple of
     * 90.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['PZ'] = The page's preferred
     * zoom (magnification)
     * factor.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans'] : the style and
     * duration of the visual transition to use when moving from another page to the
     * given page during a
     * presentation<ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['Dur'] =
     * The page's display duration (also called its advance timing): the maximum length
     * of time, in seconds, that the page shall be displayed during presentations
     * before the viewer application shall automatically advance to the next
     * page.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['S'] = transition
     * style : Split, Blinds, Box, Wipe, Dissolve, Glitter, R, Fly, Push, Cover,
     * Uncover, Fade</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['D'] =
     * The duration of the transition effect, in
     * seconds.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['Dm'] = (Split
     * and Blinds transition styles only) The dimension in which the specified
     * transition effect shall occur: H = Horizontal, V = Vertical. Default value:
     * H.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['M'] = (Split, Box
     * and Fly transition styles only) The direction of motion for the specified
     * transition effect: I = Inward from the edges of the page, O = Outward from the
     * center of the pageDefault value:
     * I.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['Di'] = (Wipe,
     * Glitter, Fly, Cover, Uncover and Push transition styles only) The direction in
     * which the specified transition effect shall moves, expressed in degrees
     * counterclockwise starting from a left-to-right direction. If the value is a
     * number, it shall be one of: 0 = Left to right, 90 = Bottom to top (Wipe only),
     * 180 = Right to left (Wipe only), 270 = Top to bottom, 315 = Top-left to
     * bottom-right (Glitter only). If the value is a name, it shall be None, which is
     * relevant only for the Fly transition when the value of SS is not 1.0. Default
     * value: 0.</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['SS'] = (Fly
     * transition style only) The starting or ending scale at which the changes shall
     * be drawn. If M specifies an inward transition, the scale of the changes drawn
     * shall progress from SS to 1.0 over the course of the transition. If M specifies
     * an outward transition, the scale of the changes drawn shall progress from 1.0 to
     * SS over the course of the transition. Default: 1.0.
     * </li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['trans']['B'] = (Fly transition
     * style only) If true, the area that shall be flown in is rectangular and opaque.
     * Default:
     * false.</li></ul></li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['MediaBox'] :
     * the boundaries of the physical medium on which the page shall be displayed or
     * printed<ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['MediaBox']['llx'] =
     * lower-left x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['MediaBox']['lly'] =
     * lower-left y coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['MediaBox']['urx'] =
     * upper-right x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['MediaBox']['ury'] =
     * upper-right y coordinate in
     * points</li></ul></li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['CropBox'] :
     * the visible region of default user
     * space<ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['CropBox']['llx'] =
     * lower-left x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['CropBox']['lly'] =
     * lower-left y coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['CropBox']['urx'] =
     * upper-right x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['CropBox']['ury'] =
     * upper-right y coordinate in
     * points</li></ul></li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['BleedBox'] :
     * the region to which the contents of the page shall be clipped when output in a
     * production
     * environment<ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['BleedBox']['llx'] =
     * lower-left x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['BleedBox']['lly'] =
     * lower-left y coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['BleedBox']['urx'] =
     * upper-right x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['BleedBox']['ury'] =
     * upper-right y coordinate in
     * points</li></ul></li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['TrimBox'] :
     * the intended dimensions of the finished page after
     * trimming<ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['TrimBox']['llx'] =
     * lower-left x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['TrimBox']['lly'] =
     * lower-left y coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['TrimBox']['urx'] =
     * upper-right x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['TrimBox']['ury'] =
     * upper-right y coordinate in
     * points</li></ul></li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['ArtBox'] : the
     * extent of the page's meaningful
     * content<ul><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['ArtBox']['llx'] =
     * lower-left x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['ArtBox']['lly'] =
     * lower-left y coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['ArtBox']['urx'] =
     * upper-right x coordinate in
     * points</li><li>\Osf\Pdf\Tc->pagedim[\Osf\Pdf\Tc->page]['ArtBox']['ury'] =
     * upper-right y coordinate in points</li></ul></li></ul>
     * @param $pagenum (int) page number (empty = current page)
     * @return array of page dimensions.
     * @author Nicola Asuni
     * @public
     * @since 4.5.027 (2009-03-16)
     */
    public function getPageDimensions($pagenum = '')
    {
        return $this->tcpdf->getPageDimensions($pagenum);
    }

    /**
     * Returns the page width in units.
     * @param $pagenum (int) page number (empty = current page)
     * @return int page width.
     * @author Nicola Asuni
     * @public
     * @since 1.5.2
     * @see getPageDimensions()
     */
    public function getPageWidth($pagenum = '')
    {
        return $this->tcpdf->getPageWidth($pagenum);
    }

    /**
     * Returns the page height in units.
     * @param $pagenum (int) page number (empty = current page)
     * @return int page height.
     * @author Nicola Asuni
     * @public
     * @since 1.5.2
     * @see getPageDimensions()
     */
    public function getPageHeight($pagenum = '')
    {
        return $this->tcpdf->getPageHeight($pagenum);
    }

    /**
     * Returns the page break margin.
     * @param $pagenum (int) page number (empty = current page)
     * @return int page break margin.
     * @author Nicola Asuni
     * @public
     * @since 1.5.2
     * @see getPageDimensions()
     */
    public function getBreakMargin($pagenum = '')
    {
        return $this->tcpdf->getBreakMargin($pagenum);
    }

    /**
     * Returns the scale factor (number of points in user unit).
     * @return int scale factor.
     * @author Nicola Asuni
     * @public
     * @since 1.5.2
     */
    public function getScaleFactor()
    {
        return $this->tcpdf->getScaleFactor();
    }

    /**
     * Defines the left, top and right margins.
     * @param $left (float) Left margin.
     * @param $top (float) Top margin.
     * @param $right (float) Right margin. Default value is the left one.
     * @param $keepmargins (boolean) if true overwrites the default page margins
     * @public
     * @since 1.0
     * @see SetLeftMargin(), SetTopMargin(), SetRightMargin(), SetAutoPageBreak()
     * @return $this
     */
    public function setMargins($left, $top, $right = -1, $keepmargins = false)
    {
        $this->tcpdf->SetMargins($left, $top, $right, $keepmargins);
        return $this;
    }

    /**
     * Defines the left margin. The method can be called before creating the first
     * page. If the current abscissa gets out of page, it is brought back to the
     * margin.
     * @param $margin (float) The margin.
     * @public
     * @since 1.4
     * @see SetTopMargin(), SetRightMargin(), SetAutoPageBreak(), SetMargins()
     * @return $this
     */
    public function setLeftMargin($margin)
    {
        $this->tcpdf->SetLeftMargin($margin);
        return $this;
    }

    /**
     * Defines the top margin. The method can be called before creating the first
     * page.
     * @param $margin (float) The margin.
     * @public
     * @since 1.5
     * @see SetLeftMargin(), SetRightMargin(), SetAutoPageBreak(), SetMargins()
     * @return $this
     */
    public function setTopMargin($margin)
    {
        $this->tcpdf->SetTopMargin($margin);
        return $this;
    }

    /**
     * Defines the right margin. The method can be called before creating the first
     * page.
     * @param $margin (float) The margin.
     * @public
     * @since 1.5
     * @see SetLeftMargin(), SetTopMargin(), SetAutoPageBreak(), SetMargins()
     * @return $this
     */
    public function setRightMargin($margin)
    {
        $this->tcpdf->SetRightMargin($margin);
        return $this;
    }

    /**
     * Set the same internal Cell padding for top, right, bottom, left-
     * @param $pad (float) internal padding.
     * @public
     * @since 2.1.000 (2008-01-09)
     * @see getCellPaddings(), setCellPaddings()
     * @return $this
     */
    public function setCellPadding($pad)
    {
        $this->tcpdf->SetCellPadding($pad);
        return $this;
    }

    /**
     * Set the internal Cell paddings.
     * @param $left (float) left padding
     * @param $top (float) top padding
     * @param $right (float) right padding
     * @param $bottom (float) bottom padding
     * @public
     * @since 5.9.000 (2010-10-03)
     * @see getCellPaddings(), SetCellPadding()
     * @return $this
     */
    public function setCellPaddings($left = '', $top = '', $right = '', $bottom = '')
    {
        $this->tcpdf->setCellPaddings($left, $top, $right, $bottom);
        return $this;
    }

    /**
     * Get the internal Cell padding array.
     * @return array of padding values
     * @public
     * @since 5.9.000 (2010-10-03)
     * @see setCellPaddings(), SetCellPadding()
     */
    public function getCellPaddings()
    {
        return $this->tcpdf->getCellPaddings();
    }

    /**
     * Set the internal Cell margins.
     * @param $left (float) left margin
     * @param $top (float) top margin
     * @param $right (float) right margin
     * @param $bottom (float) bottom margin
     * @public
     * @since 5.9.000 (2010-10-03)
     * @see getCellMargins()
     * @return $this
     */
    public function setCellMargins($left = '', $top = '', $right = '', $bottom = '')
    {
        $this->tcpdf->setCellMargins($left, $top, $right, $bottom);
        return $this;
    }

    /**
     * Get the internal Cell margin array.
     * @return array of margin values
     * @public
     * @since 5.9.000 (2010-10-03)
     * @see setCellMargins()
     */
    public function getCellMargins()
    {
        return $this->tcpdf->getCellMargins();
    }

    /**
     * Enables or disables the automatic page breaking mode. When enabling, the
     * second parameter is the distance from the bottom of the page that defines the
     * triggering limit. By default, the mode is on and the margin is 2 cm.
     * @param $auto (boolean) Boolean indicating if mode should be on or off.
     * @param $margin (float) Distance from the bottom of the page.
     * @public
     * @since 1.0
     * @see Cell(), MultiCell(), AcceptPageBreak()
     * @return $this
     */
    public function setAutoPageBreak($auto, $margin = 0)
    {
        $this->tcpdf->SetAutoPageBreak($auto, $margin);
        return $this;
    }

    /**
     * Return the auto-page-break mode (true or false).
     * @return boolean auto-page-break mode
     * @public
     * @since 5.9.088
     */
    public function getAutoPageBreak()
    {
        return $this->tcpdf->getAutoPageBreak();
    }

    /**
     * Defines the way the document is to be displayed by the viewer.
     * @param $zoom (mixed) The zoom to use. It can be one of the following string
     * values or a number indicating the zooming factor to use. <ul><li>fullpage:
     * displays the entire page on screen </li><li>fullwidth: uses maximum width of
     * window</li><li>real: uses real size (equivalent to 100% zoom)</li><li>default:
     * uses viewer default mode</li></ul>
     * @param $layout (string) The page layout. Possible values
     * are:<ul><li>SinglePage Display one page at a time</li><li>OneColumn Display the
     * pages in one column</li><li>TwoColumnLeft Display the pages in two columns, with
     * odd-numbered pages on the left</li><li>TwoColumnRight Display the pages in two
     * columns, with odd-numbered pages on the right</li><li>TwoPageLeft (PDF 1.5)
     * Display the pages two at a time, with odd-numbered pages on the
     * left</li><li>TwoPageRight (PDF 1.5) Display the pages two at a time, with
     * odd-numbered pages on the right</li></ul>
     * @param $mode (string) A name object specifying how the document should be
     * displayed when opened:<ul><li>UseNone Neither document outline nor thumbnail
     * images visible</li><li>UseOutlines Document outline visible</li><li>UseThumbs
     * Thumbnail images visible</li><li>FullScreen Full-screen mode, with no menu bar,
     * window controls, or any other window visible</li><li>UseOC (PDF 1.5) Optional
     * content group panel visible</li><li>UseAttachments (PDF 1.6) Attachments panel
     * visible</li></ul>
     * @public
     * @since 1.2
     * @return $this
     */
    public function setDisplayMode($zoom, $layout = 'SinglePage', $mode = 'UseNone')
    {
        $this->tcpdf->SetDisplayMode($zoom, $layout, $mode);
        return $this;
    }

    /**
     * Activates or deactivates page compression. When activated, the internal
     * representation of each page is compressed, which leads to a compression ratio of
     * about 2 for the resulting document. Compression is on by default.
     * Note: the Zlib extension is required for this feature. If not present,
     * compression will be turned off.
     * @param $compress (boolean) Boolean indicating if compression must be
     * enabled.
     * @public
     * @since 1.4
     * @return $this
     */
    public function setCompression($compress = true)
    {
        $this->tcpdf->SetCompression($compress);
        return $this;
    }

    /**
     * Set flag to force sRGB_IEC61966-2.1 black scaled ICC color profile for the
     * whole document.
     * @param $mode (boolean) If true force sRGB output intent.
     * @public
     * @since 5.9.121 (2011-09-28)
     * @return $this
     */
    public function setSRGBmode($mode = false)
    {
        $this->tcpdf->setSRGBmode($mode);
        return $this;
    }

    /**
     * Turn on/off Unicode mode for document information dictionary (meta tags).
     * This has effect only when unicode mode is set to false.
     * @param $unicode (boolean) if true set the meta information in Unicode
     * @since 5.9.027 (2010-12-01)
     * @public
     * @return $this
     */
    public function setDocInfoUnicode($unicode = true)
    {
        $this->tcpdf->SetDocInfoUnicode($unicode);
        return $this;
    }

    /**
     * Defines the title of the document.
     * @param $title (string) The title.
     * @public
     * @since 1.2
     * @see SetAuthor(), SetCreator(), SetKeywords(), SetSubject()
     * @return $this
     */
    public function setTitle($title)
    {
        $this->tcpdf->SetTitle($title);
        return $this;
    }

    /**
     * Defines the subject of the document.
     * @param $subject (string) The subject.
     * @public
     * @since 1.2
     * @see SetAuthor(), SetCreator(), SetKeywords(), SetTitle()
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->tcpdf->SetSubject($subject);
        return $this;
    }

    /**
     * Defines the author of the document.
     * @param $author (string) The name of the author.
     * @public
     * @since 1.2
     * @see SetCreator(), SetKeywords(), SetSubject(), SetTitle()
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->tcpdf->SetAuthor($author);
        return $this;
    }

    /**
     * Associates keywords with the document, generally in the form 'keyword1
     * keyword2 ...'.
     * @param $keywords (string) The list of keywords.
     * @public
     * @since 1.2
     * @see SetAuthor(), SetCreator(), SetSubject(), SetTitle()
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->tcpdf->SetKeywords($keywords);
        return $this;
    }

    /**
     * Defines the creator of the document. This is typically the name of the
     * application that generates the PDF.
     * @param $creator (string) The name of the creator.
     * @public
     * @since 1.2
     * @see SetAuthor(), SetKeywords(), SetSubject(), SetTitle()
     * @return $this
     */
    public function setCreator($creator)
    {
        $this->tcpdf->SetCreator($creator);
        return $this;
    }

    /**
     * Throw an exception or print an error message and die if the
     * K_TCPDF_PARSER_THROW_EXCEPTION_ERROR constant is set to true.
     * @param $msg (string) The error message
     * @public
     * @since 1.0
     */
//    public function error($msg)
//    {
//        $this->tcpdf->Error($msg);
//        return $this;
//    }

    /**
     * This method begins the generation of the PDF document.
     * It is not necessary to call it explicitly because AddPage() does it
     * automatically.
     * Note: no page is created by this method
     * @public
     * @since 1.0
     * @see AddPage(), Close()
     */
//    public function open()
//    {
//        $this->tcpdf->Open();
//        return $this;
//    }

    /**
     * Terminates the PDF document.
     * It is not necessary to call this method explicitly because Output() does it
     * automatically.
     * If the document contains no page, AddPage() is called to prevent from
     * getting an invalid document.
     * @public
     * @since 1.0
     * @see Open(), Output()
     */
//    public function close()
//    {
//        $this->tcpdf->Close();
//        return $this;
//    }

    /**
     * Move pointer at the specified document page and update page dimensions.
     * @param $pnum (int) page number (1 ... numpages)
     * @param $resetmargins (boolean) if true reset left, right, top margins and Y
     * position.
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see getPage(), lastpage(), getNumPages()
     * @return $this
     */
    public function setPage($pnum, $resetmargins = false)
    {
        $this->tcpdf->setPage($pnum, $resetmargins);
        return $this;
    }

    /**
     * Reset pointer to the last document page.
     * @param $resetmargins (boolean) if true reset left, right, top margins and Y
     * position.
     * @public
     * @since 2.0.000 (2008-01-04)
     * @see setPage(), getPage(), getNumPages()
     * @return $this
     */
    public function lastPage($resetmargins = false)
    {
        $this->tcpdf->lastPage($resetmargins);
        return $this;
    }

    /**
     * Get current document page number.
     * @return int page number
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see setPage(), lastpage(), getNumPages()
     */
    public function getPage()
    {
        return $this->tcpdf->getPage();
    }

    /**
     * Get the total number of insered pages.
     * @return int number of pages
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see setPage(), getPage(), lastpage()
     */
    public function getNumPages()
    {
        return $this->tcpdf->getNumPages();
    }

    /**
     * Adds a new TOC (Table Of Content) page to the document.
     * @param $orientation (string) page orientation.
     * @param $format (mixed) The format used for pages. It can be either: one of
     * the string values specified at getPageSizeFromFormat() or an array of parameters
     * specified at setPageFormat().
     * @param $keepmargins (boolean) if true overwrites the default page margins
     * with the current margins
     * @public
     * @since 5.0.001 (2010-05-06)
     * @see AddPage(), startPage(), endPage(), endTOCPage()
     * @return $this
     */
    public function addTOCPage($orientation = '', $format = '', $keepmargins = false)
    {
        $this->tcpdf->addTOCPage($orientation, $format, $keepmargins);
        return $this;
    }

    /**
     * Terminate the current TOC (Table Of Content) page
     * @public
     * @since 5.0.001 (2010-05-06)
     * @see AddPage(), startPage(), endPage(), addTOCPage()
     * @return $this
     */
    public function endTOCPage()
    {
        $this->tcpdf->endTOCPage();
        return $this;
    }

    /**
     * Adds a new page to the document. If a page is already present, the Footer()
     * method is called first to output the footer (if enabled). Then the page is
     * added, the current position set to the top-left corner according to the left and
     * top margins (or top-right if in RTL mode), and Header() is called to display the
     * header (if enabled).
     * The origin of the coordinate system is at the top-left corner (or top-right
     * for RTL) and increasing ordinates go downwards.
     * @param $orientation (string) page orientation. Possible values are (case
     * insensitive):<ul><li>P or PORTRAIT (default)</li><li>L or LANDSCAPE</li></ul>
     * @param $format (mixed) The format used for pages. It can be either: one of
     * the string values specified at getPageSizeFromFormat() or an array of parameters
     * specified at setPageFormat().
     * @param $keepmargins (boolean) if true overwrites the default page margins
     * with the current margins
     * @param $tocpage (boolean) if true set the tocpage state to true (the added
     * page will be used to display Table Of Content).
     * @public
     * @since 1.0
     * @see startPage(), endPage(), addTOCPage(), endTOCPage(),
     * getPageSizeFromFormat(), setPageFormat()
     * @return $this
     */
    public function addPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false)
    {
        $this->tcpdf->AddPage($orientation, $format, $keepmargins, $tocpage);
        return $this;
    }

    /**
     * Terminate the current page
     * @param $tocpage (boolean) if true set the tocpage state to false (end the
     * page used to display Table Of Content).
     * @public
     * @since 4.2.010 (2008-11-14)
     * @see AddPage(), startPage(), addTOCPage(), endTOCPage()
     * @return $this
     */
    public function endPage($tocpage = false)
    {
        $this->tcpdf->endPage($tocpage);
        return $this;
    }

    /**
     * Starts a new page to the document. The page must be closed using the endPage()
     * function.
     * The origin of the coordinate system is at the top-left corner and increasing
     * ordinates go downwards.
     * @param $orientation (string) page orientation. Possible values are (case
     * insensitive):<ul><li>P or PORTRAIT (default)</li><li>L or LANDSCAPE</li></ul>
     * @param $format (mixed) The format used for pages. It can be either: one of
     * the string values specified at getPageSizeFromFormat() or an array of parameters
     * specified at setPageFormat().
     * @param $tocpage (boolean) if true the page is designated to contain the
     * Table-Of-Content.
     * @since 4.2.010 (2008-11-14)
     * @see AddPage(), endPage(), addTOCPage(), endTOCPage(),
     * getPageSizeFromFormat(), setPageFormat()
     * @public
     * @return $this
     */
    public function startPage($orientation = '', $format = '', $tocpage = false)
    {
        $this->tcpdf->startPage($orientation, $format, $tocpage);
        return $this;
    }

    /**
     * Set start-writing mark on current page stream used to put borders and fills.
     * Borders and fills are always created after content and inserted on the
     * position marked by this method.
     * This function must be called after calling Image() function for a background
     * image.
     * Background images must be always inserted before calling Multicell() or
     * WriteHTMLCell() or WriteHTML() functions.
     * @public
     * @since 4.0.016 (2008-07-30)
     * @return $this
     */
    public function setPageMark()
    {
        $this->tcpdf->setPageMark();
        return $this;
    }

    /**
     * Set header data.
     * @param $ln (string) header image logo
     * @param $lw (string) header image logo width in mm
     * @param $ht (string) string to print as title on document header
     * @param $hs (string) string to print on document header
     * @param $tc (array) RGB array color for text.
     * @param $lc (array) RGB array color for line.
     * @public
     * @return $this
     */
    public function setHeaderData($ln = '', $lw = 0, $ht = '', $hs = '', $tc = array(0, 0, 0), $lc = array(0, 0, 0))
    {
        $this->tcpdf->setHeaderData($ln, $lw, $ht, $hs, $tc, $lc);
        return $this;
    }

    /**
     * Set footer data.
     * @param $tc (array) RGB array color for text.
     * @param $lc (array) RGB array color for line.
     * @public
     * @return $this
     */
    public function setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0))
    {
        $this->tcpdf->setFooterData($tc, $lc);
        return $this;
    }

    /**
     * Returns header data:
     * <ul><li>$ret['logo'] = logo image</li><li>$ret['logo_width'] = width of the
     * image logo in user units</li><li>$ret['title'] = header
     * title</li><li>$ret['string'] = header description string</li></ul>
     * @return array()
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getHeaderData()
    {
        return $this->tcpdf->getHeaderData();
    }

    /**
     * Set header margin.
     * (minimum distance between header and top page margin)
     * @param $hm (int) distance in user units
     * @public
     * @return $this
     */
    public function setHeaderMargin($hm = 10)
    {
        $this->tcpdf->setHeaderMargin($hm);
        return $this;
    }

    /**
     * Returns header margin in user units.
     * @return float
     * @since 4.0.012 (2008-07-24)
     * @public
     */
    public function getHeaderMargin()
    {
        return $this->tcpdf->getHeaderMargin();
    }

    /**
     * Set footer margin.
     * (minimum distance between footer and bottom page margin)
     * @param $fm (int) distance in user units
     * @public
     * @return $this
     */
    public function setFooterMargin($fm = 10)
    {
        $this->tcpdf->setFooterMargin($fm);
        return $this;
    }

    /**
     * Returns footer margin in user units.
     * @return float
     * @since 4.0.012 (2008-07-24)
     * @public
     */
    public function getFooterMargin()
    {
        return $this->tcpdf->getFooterMargin();
    }

    /**
     * Set a flag to print page header.
     * @param $val (boolean) set to true to print the page header (default), false
     * otherwise.
     * @public
     * @return $this
     */
    public function setPrintHeader($val = true)
    {
        $this->tcpdf->setPrintHeader($val);
        return $this;
    }

    /**
     * Set a flag to print page footer.
     * @param $val (boolean) set to true to print the page footer (default), false
     * otherwise.
     * @public
     * @return $this
     */
    public function setPrintFooter($val = true)
    {
        $this->tcpdf->setPrintFooter($val);
        return $this;
    }

    /**
     * Return the right-bottom (or left-bottom for RTL) corner X coordinate of last
     * inserted image
     * @return float
     * @public
     */
    public function getImageRBX()
    {
        return $this->tcpdf->getImageRBX();
    }

    /**
     * Return the right-bottom (or left-bottom for RTL) corner Y coordinate of last
     * inserted image
     * @return float
     * @public
     */
    public function getImageRBY()
    {
        return $this->tcpdf->getImageRBY();
    }

    /**
     * Reset the xobject template used by Header() method.
     * @public
     * @return $this
     */
    public function resetHeaderTemplate()
    {
        $this->tcpdf->resetHeaderTemplate();
        return $this;
    }

    /**
     * Set a flag to automatically reset the xobject template used by Header() method
     * at each page.
     * @param $val (boolean) set to true to reset Header xobject template at each
     * page, false otherwise.
     * @public
     * @return $this
     */
    public function setHeaderTemplateAutoreset($val = true)
    {
        $this->tcpdf->setHeaderTemplateAutoreset($val);
        return $this;
    }

    /**
     * This method is used to render the page header.
     * It is automatically called by AddPage() and could be overwritten in your own
     * inherited class.
     * @public
     * @return $this
     */
    public function header()
    {
        $this->tcpdf->Header();
        return $this;
    }

    /**
     * This method is used to render the page footer.
     * It is automatically called by AddPage() and could be overwritten in your own
     * inherited class.
     * @public
     * @return $this
     */
    public function footer()
    {
        $this->tcpdf->Footer();
        return $this;
    }

    /**
     * Returns the current page number.
     * @return int page number
     * @public
     * @since 1.0
     * @see getAliasNbPages()
     */
    public function pageNo()
    {
        return $this->tcpdf->PageNo();
    }

    /**
     * Returns the array of spot colors.
     * @return (array) Spot colors array.
     * @public
     * @since 6.0.038 (2013-09-30)
     */
    public function getAllSpotColors()
    {
        return $this->tcpdf->getAllSpotColors();
    }

    /**
     * Defines a new spot color.
     * It can be expressed in RGB components or gray scale.
     * The method can be called before the first page is created and the value is
     * retained from page to page.
     * @param $name (string) Full name of the spot color.
     * @param $c (float) Cyan color for CMYK. Value between 0 and 100.
     * @param $m (float) Magenta color for CMYK. Value between 0 and 100.
     * @param $y (float) Yellow color for CMYK. Value between 0 and 100.
     * @param $k (float) Key (Black) color for CMYK. Value between 0 and 100.
     * @public
     * @since 4.0.024 (2008-09-12)
     * @see SetDrawSpotColor(), SetFillSpotColor(), SetTextSpotColor()
     * @return $this
     */
    public function addSpotColor($name, $c, $m, $y, $k)
    {
        $this->tcpdf->AddSpotColor($name, $c, $m, $y, $k);
        return $this;
    }

    /**
     * Set the spot color for the specified type ('draw', 'fill', 'text').
     * @param $type (string) Type of object affected by this color: ('draw',
     * 'fill', 'text').
     * @param $name (string) Name of the spot color.
     * @param $tint (float) Intensity of the color (from 0 to 100 ; 100 = full
     * intensity by default).
     * @return (string) PDF color command.
     * @public
     * @since 5.9.125 (2011-10-03)
     */
    public function setSpotColor($type, $name, $tint = 100)
    {
        return $this->tcpdf->setSpotColor($type, $name, $tint);
    }

    /**
     * Defines the spot color used for all drawing operations (lines, rectangles and
     * cell borders).
     * @param $name (string) Name of the spot color.
     * @param $tint (float) Intensity of the color (from 0 to 100 ; 100 = full
     * intensity by default).
     * @public
     * @since 4.0.024 (2008-09-12)
     * @see AddSpotColor(), SetFillSpotColor(), SetTextSpotColor()
     * @return $this
     */
    public function setDrawSpotColor($name, $tint = 100)
    {
        $this->tcpdf->SetDrawSpotColor($name, $tint);
        return $this;
    }

    /**
     * Defines the spot color used for all filling operations (filled rectangles and
     * cell backgrounds).
     * @param $name (string) Name of the spot color.
     * @param $tint (float) Intensity of the color (from 0 to 100 ; 100 = full
     * intensity by default).
     * @public
     * @since 4.0.024 (2008-09-12)
     * @see AddSpotColor(), SetDrawSpotColor(), SetTextSpotColor()
     * @return $this
     */
    public function setFillSpotColor($name, $tint = 100)
    {
        $this->tcpdf->SetFillSpotColor($name, $tint);
        return $this;
    }

    /**
     * Defines the spot color used for text.
     * @param $name (string) Name of the spot color.
     * @param $tint (int) Intensity of the color (from 0 to 100 ; 100 = full
     * intensity by default).
     * @public
     * @since 4.0.024 (2008-09-12)
     * @see AddSpotColor(), SetDrawSpotColor(), SetFillSpotColor()
     * @return $this
     */
    public function setTextSpotColor($name, $tint = 100)
    {
        $this->tcpdf->SetTextSpotColor($name, $tint);
        return $this;
    }

    /**
     * Set the color array for the specified type ('draw', 'fill', 'text').
     * It can be expressed in RGB, CMYK or GRAY SCALE components.
     * The method can be called before the first page is created and the value is
     * retained from page to page.
     * @param $type (string) Type of object affected by this color: ('draw',
     * 'fill', 'text').
     * @param $color (array) Array of colors (1=gray, 3=RGB, 4=CMYK or
     * 5=spotcolor=CMYK+name values).
     * @param $ret (boolean) If true do not send the PDF command.
     * @return (string) The PDF command or empty string.
     * @public
     * @since 3.1.000 (2008-06-11)
     */
    public function setColorArray($type, $color, $ret = false)
    {
        return $this->tcpdf->setColorArray($type, $color, $ret);
    }

    /**
     * Defines the color used for all drawing operations (lines, rectangles and cell
     * borders).
     * It can be expressed in RGB, CMYK or GRAY SCALE components.
     * The method can be called before the first page is created and the value is
     * retained from page to page.
     * @param $color (array) Array of colors (1, 3 or 4 values).
     * @param $ret (boolean) If true do not send the PDF command.
     * @return string the PDF command
     * @public
     * @since 3.1.000 (2008-06-11)
     * @see SetDrawColor()
     */
    public function setDrawColorArray($color, $ret = false)
    {
        return $this->tcpdf->SetDrawColorArray($color, $ret);
    }

    /**
     * Defines the color used for all filling operations (filled rectangles and cell
     * backgrounds).
     * It can be expressed in RGB, CMYK or GRAY SCALE components.
     * The method can be called before the first page is created and the value is
     * retained from page to page.
     * @param $color (array) Array of colors (1, 3 or 4 values).
     * @param $ret (boolean) If true do not send the PDF command.
     * @public
     * @since 3.1.000 (2008-6-11)
     * @see SetFillColor()
     */
    public function setFillColorArray($color, $ret = false)
    {
        return $this->tcpdf->SetFillColorArray($color, $ret);
    }

    /**
     * Defines the color used for text. It can be expressed in RGB components or gray
     * scale.
     * The method can be called before the first page is created and the value is
     * retained from page to page.
     * @param $color (array) Array of colors (1, 3 or 4 values).
     * @param $ret (boolean) If true do not send the PDF command.
     * @public
     * @since 3.1.000 (2008-6-11)
     * @see SetFillColor()
     * @return $this
     */
    public function setTextColorArray($color, $ret = false)
    {
        return $this->tcpdf->SetTextColorArray($color, $ret);
    }

    /**
     * Defines the color used by the specified type ('draw', 'fill', 'text').
     * @param $type (string) Type of object affected by this color: ('draw',
     * 'fill', 'text').
     * @param $col1 (float) GRAY level for single color, or Red color for RGB
     * (0-255), or CYAN color for CMYK (0-100).
     * @param $col2 (float) GREEN color for RGB (0-255), or MAGENTA color for CMYK
     * @param $col3 (float) BLUE color for RGB (0-255), or YELLOW color for CMYK
     * @param $col4 (float) KEY (BLACK) color for CMYK (0-100).
     * @param $ret (boolean) If true do not send the command.
     * @param $name (string) spot color name (if any)
     * @return (string) The PDF command or empty string.
     * @public
     * @since 5.9.125 (2011-10-03)
     */
    public function setColor($type, $col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1, $ret = false, $name = '')
    {
        return $this->tcpdf->setColor($type, $col1, $col2, $col3, $col4, $ret, $name);
    }

    /**
     * Defines the color used for all drawing operations (lines, rectangles and cell
     * borders). It can be expressed in RGB components or gray scale. The method can be
     * called before the first page is created and the value is retained from page to
     * page.
     * @param $col1 (float) GRAY level for single color, or Red color for RGB
     * (0-255), or CYAN color for CMYK (0-100).
     * @param $col2 (float) GREEN color for RGB (0-255), or MAGENTA color for CMYK
     * @param $col3 (float) BLUE color for RGB (0-255), or YELLOW color for CMYK
     * @param $col4 (float) KEY (BLACK) color for CMYK (0-100).
     * @param $ret (boolean) If true do not send the command.
     * @param $name (string) spot color name (if any)
     * @return string the PDF command
     * @public
     * @since 1.3
     * @see SetDrawColorArray(), SetFillColor(), SetTextColor(), Line(), Rect(),
     * Cell(), MultiCell()
     */
    public function setDrawColor($col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1, $ret = false, $name = '')
    {
        return $this->tcpdf->SetDrawColor($col1, $col2, $col3, $col4, $ret, $name);
    }

    /**
     * Defines the color used for all filling operations (filled rectangles and cell
     * backgrounds). It can be expressed in RGB components or gray scale. The method
     * can be called before the first page is created and the value is retained from
     * page to page.
     * @param $col1 (float) GRAY level for single color, or Red color for RGB
     * (0-255), or CYAN color for CMYK (0-100).
     * @param $col2 (float) GREEN color for RGB (0-255), or MAGENTA color for CMYK
     * @param $col3 (float) BLUE color for RGB (0-255), or YELLOW color for CMYK
     * @param $col4 (float) KEY (BLACK) color for CMYK (0-100).
     * @param $ret (boolean) If true do not send the command.
     * @param $name (string) Spot color name (if any).
     * @return (string) The PDF command.
     * @public
     * @since 1.3
     * @see SetFillColorArray(), SetDrawColor(), SetTextColor(), Rect(), Cell(),
     * MultiCell()
     */
    public function setFillColor($col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1, $ret = false, $name = '')
    {
        return $this->tcpdf->SetFillColor($col1, $col2, $col3, $col4, $ret, $name);
    }

    /**
     * Defines the color used for text. It can be expressed in RGB components or gray
     * scale. The method can be called before the first page is created and the value
     * is retained from page to page.
     * @param $col1 (float) GRAY level for single color, or Red color for RGB
     * (0-255), or CYAN color for CMYK (0-100).
     * @param $col2 (float) GREEN color for RGB (0-255), or MAGENTA color for CMYK
     * @param $col3 (float) BLUE color for RGB (0-255), or YELLOW color for CMYK
     * @param $col4 (float) KEY (BLACK) color for CMYK (0-100).
     * @param $ret (boolean) If true do not send the command.
     * @param $name (string) Spot color name (if any).
     * @return (string) Empty string.
     * @public
     * @since 1.3
     * @see SetTextColorArray(), SetDrawColor(), SetFillColor(), Text(), Cell(),
     * MultiCell()
     */
    public function setTextColor($col1 = 0, $col2 = -1, $col3 = -1, $col4 = -1, $ret = false, $name = '')
    {
        return $this->tcpdf->SetTextColor($col1, $col2, $col3, $col4, $ret, $name);
    }

    /**
     * Returns the length of a string in user unit. A font must be selected.<br>
     * @param $s (string) The string whose length is to be computed
     * @param $fontname (string) Family font. It can be either a name defined by
     * AddFont() or one of the standard families. It is also possible to pass an empty
     * string, in that case, the current family is retained.
     * @param $fontstyle (string) Font style. Possible values are (case
     * insensitive):<ul><li>empty string: regular</li><li>B: bold</li><li>I:
     * italic</li><li>U: underline</li><li>D: line-through</li><li>O:
     * overline</li></ul> or any combination. The default value is regular.
     * @param $fontsize (float) Font size in points. The default value is the
     * current size.
     * @param $getarray (boolean) if true returns an array of characters widths, if
     * false returns the total length.
     * @return mixed int total string length or array of characted widths
     * @author Nicola Asuni
     * @public
     * @since 1.2
     */
    public function getStringWidth($s, $fontname = '', $fontstyle = '', $fontsize = 0, $getarray = false)
    {
        return $this->tcpdf->GetStringWidth($s, $fontname, $fontstyle, $fontsize, $getarray);
    }

    /**
     * Returns the string length of an array of chars in user unit or an array of
     * characters widths. A font must be selected.<br>
     * @param $sa (string) The array of chars whose total length is to be computed
     * @param $fontname (string) Family font. It can be either a name defined by
     * AddFont() or one of the standard families. It is also possible to pass an empty
     * string, in that case, the current family is retained.
     * @param $fontstyle (string) Font style. Possible values are (case
     * insensitive):<ul><li>empty string: regular</li><li>B: bold</li><li>I:
     * italic</li><li>U: underline</li><li>D: line through</li><li>O:
     * overline</li></ul> or any combination. The default value is regular.
     * @param $fontsize (float) Font size in points. The default value is the
     * current size.
     * @param $getarray (boolean) if true returns an array of characters widths, if
     * false returns the total length.
     * @return mixed int total string length or array of characted widths
     * @author Nicola Asuni
     * @public
     * @since 2.4.000 (2008-03-06)
     */
    public function getArrStringWidth($sa, $fontname = '', $fontstyle = '', $fontsize = 0, $getarray = false)
    {
        return $this->tcpdf->GetArrStringWidth($sa, $fontname, $fontstyle, $fontsize, $getarray);
    }

    /**
     * Returns the length of the char in user unit for the current font considering
     * current stretching and spacing (tracking).
     * @param $char (int) The char code whose length is to be returned
     * @param $notlast (boolean) If false ignore the font-spacing.
     * @return float char width
     * @author Nicola Asuni
     * @public
     * @since 2.4.000 (2008-03-06)
     */
    public function getCharWidth($char, $notlast = true)
    {
        return $this->tcpdf->GetCharWidth($char, $notlast);
    }

    /**
     * Returns the length of the char in user unit for the current font.
     * @param $char (int) The char code whose length is to be returned
     * @return float char width
     * @author Nicola Asuni
     * @public
     * @since 5.9.000 (2010-09-28)
     */
    public function getRawCharWidth($char)
    {
        return $this->tcpdf->getRawCharWidth($char);
    }

    /**
     * Returns the numbero of characters in a string.
     * @param $s (string) The input string.
     * @return int number of characters
     * @public
     * @since 2.0.0001 (2008-01-07)
     */
    public function getNumChars($s)
    {
        return $this->tcpdf->GetNumChars($s);
    }

    /**
     * Imports a TrueType, Type1, core, or CID0 font and makes it available.
     * It is necessary to generate a font definition file first (read
     * /fonts/utils/README.TXT).
     * The definition file (and the font file itself when embedding) must be
     * present either in the current directory or in the one indicated by K_PATH_FONTS
     * if the constant is defined. If it could not be found, the error "Could not
     * include font definition file" is generated.
     * @param $family (string) Font family. The name can be chosen arbitrarily. If
     * it is a standard family name, it will override the corresponding font.
     * @param $style (string) Font style. Possible values are (case
     * insensitive):<ul><li>empty string: regular (default)</li><li>B: bold</li><li>I:
     * italic</li><li>BI or IB: bold italic</li></ul>
     * @param $fontfile (string) The font definition file. By default, the name is
     * built from the family and style, in lower case with no spaces.
     * @return array containing the font data, or false in case of error.
     * @param $subset (mixed) if true embedd only a subset of the font (stores only
     * the information related to the used characters); if false embedd full font; if
     * 'default' uses the default value set using setFontSubsetting(). This option is
     * valid only for TrueTypeUnicode fonts. If you want to enable users to change the
     * document, set this parameter to false. If you subset the font, the person who
     * receives your PDF would need to have your same font in order to make changes to
     * your PDF. The file size of the PDF would also be smaller because you are
     * embedding only part of a font.
     * @public
     * @since 1.5
     * @see SetFont(), setFontSubsetting()
     */
    public function addFont($family, $style = '', $fontfile = '', $subset = 'default')
    {
        return $this->tcpdf->AddFont($family, $style, $fontfile, $subset);
    }

    /**
     * Sets the font used to print character strings.
     * The font can be either a standard one or a font added via the AddFont()
     * method. Standard fonts use Windows encoding cp1252 (Western Europe).
     * The method can be called before the first page is created and the font is
     * retained from page to page.
     * If you just wish to change the current font size, it is simpler to call
     * SetFontSize().
     * Note: for the standard fonts, the font metric files must be accessible.
     * There are three possibilities for this:<ul><li>They are in the current directory
     * (the one where the running script lies)</li><li>They are in one of the
     * directories defined by the include_path parameter</li><li>They are in the
     * directory defined by the K_PATH_FONTS constant</li></ul><br />
     * @param $family (string) Family font. It can be either a name defined by
     * AddFont() or one of the standard Type1 families (case insensitive):<ul><li>times
     * (Times-Roman)</li><li>timesb (Times-Bold)</li><li>timesi
     * (Times-Italic)</li><li>timesbi (Times-BoldItalic)</li><li>helvetica
     * (Helvetica)</li><li>helveticab (Helvetica-Bold)</li><li>helveticai
     * (Helvetica-Oblique)</li><li>helveticabi (Helvetica-BoldOblique)</li><li>courier
     * (Courier)</li><li>courierb (Courier-Bold)</li><li>courieri
     * (Courier-Oblique)</li><li>courierbi (Courier-BoldOblique)</li><li>symbol
     * (Symbol)</li><li>zapfdingbats (ZapfDingbats)</li></ul> It is also possible to
     * pass an empty string. In that case, the current family is retained.
     * @param $style (string) Font style. Possible values are (case
     * insensitive):<ul><li>empty string: regular</li><li>B: bold</li><li>I:
     * italic</li><li>U: underline</li><li>D: line through</li><li>O:
     * overline</li></ul> or any combination. The default value is regular. Bold and
     * italic styles do not apply to Symbol and ZapfDingbats basic fonts or other fonts
     * when not defined.
     * @param $size (float) Font size in points. The default value is the current
     * size. If no size has been specified since the beginning of the document, the
     * value taken is 12
     * @param $fontfile (string) The font definition file. By default, the name is
     * built from the family and style, in lower case with no spaces.
     * @param $subset (mixed) if true embedd only a subset of the font (stores only
     * the information related to the used characters); if false embedd full font; if
     * 'default' uses the default value set using setFontSubsetting(). This option is
     * valid only for TrueTypeUnicode fonts. If you want to enable users to change the
     * document, set this parameter to false. If you subset the font, the person who
     * receives your PDF would need to have your same font in order to make changes to
     * your PDF. The file size of the PDF would also be smaller because you are
     * embedding only part of a font.
     * @param $out (boolean) if true output the font size command, otherwise only
     * set the font properties.
     * @author Nicola Asuni
     * @public
     * @since 1.0
     * @see AddFont(), SetFontSize()
     * @return $this
     */
    public function setFont($family, $style = '', $size = null, $fontfile = '', $subset = 'default', $out = true)
    {
        $this->tcpdf->SetFont($family, $style, $size, $fontfile, $subset, $out);
        return $this;
    }

    /**
     * Defines the size of the current font.
     * @param $size (float) The font size in points.
     * @param $out (boolean) if true output the font size command, otherwise only
     * set the font properties.
     * @public
     * @since 1.0
     * @see SetFont()
     * @return $this
     */
    public function setFontSize($size, $out = true)
    {
        $this->tcpdf->SetFontSize($size, $out);
        return $this;
    }

    /**
     * Returns the bounding box of the current font in user units.
     * @return array
     * @public
     * @since 5.9.152 (2012-03-23)
     */
    public function getFontBBox()
    {
        return $this->tcpdf->getFontBBox();
    }

    /**
     * Convert a relative font measure into absolute value.
     * @param $s (int) Font measure.
     * @return float Absolute measure.
     * @since 5.9.186 (2012-09-13)
     */
    public function getAbsFontMeasure($s)
    {
        return $this->tcpdf->getAbsFontMeasure($s);
    }

    /**
     * Returns the glyph bounding box of the specified character in the current font
     * in user units.
     * @param $char (int) Input character code.
     * @return mixed array(xMin, yMin, xMax, yMax) or FALSE if not defined.
     * @since 5.9.186 (2012-09-13)
     */
    public function getCharBBox($char)
    {
        return $this->tcpdf->getCharBBox($char);
    }

    /**
     * Return the font descent value
     * @param $font (string) font name
     * @param $style (string) font style
     * @param $size (float) The size (in points)
     * @return int font descent
     * @public
     * @author Nicola Asuni
     * @since 4.9.003 (2010-03-30)
     */
    public function getFontDescent($font, $style = '', $size = 0)
    {
        return $this->tcpdf->getFontDescent($font, $style, $size);
    }

    /**
     * Return the font ascent value.
     * @param $font (string) font name
     * @param $style (string) font style
     * @param $size (float) The size (in points)
     * @return int font ascent
     * @public
     * @author Nicola Asuni
     * @since 4.9.003 (2010-03-30)
     */
    public function getFontAscent($font, $style = '', $size = 0)
    {
        return $this->tcpdf->getFontAscent($font, $style, $size);
    }

    /**
     * Return true in the character is present in the specified font.
     * @param $char (mixed) Character to check (integer value or string)
     * @param $font (string) Font name (family name).
     * @param $style (string) Font style.
     * @return (boolean) true if the char is defined, false otherwise.
     * @public
     * @since 5.9.153 (2012-03-28)
     */
    public function isCharDefined($char, $font = '', $style = '')
    {
        return $this->tcpdf->isCharDefined($char, $font, $style);
    }

    /**
     * Replace missing font characters on selected font with specified substitutions.
     * @param $text (string) Text to process.
     * @param $font (string) Font name (family name).
     * @param $style (string) Font style.
     * @param $subs (array) Array of possible character substitutions. The key is
     * the character to check (integer value) and the value is a single intege value or
     * an array of possible substitutes.
     * @return (string) Processed text.
     * @public
     * @since 5.9.153 (2012-03-28)
     */
    public function replaceMissingChars($text, $font = '', $style = '', $subs = array())
    {
        return $this->tcpdf->replaceMissingChars($text, $font, $style, $subs);
    }

    /**
     * Defines the default monospaced font.
     * @param $font (string) Font name.
     * @public
     * @since 4.5.025
     * @return $this
     */
    public function setDefaultMonospacedFont($font)
    {
        $this->tcpdf->SetDefaultMonospacedFont($font);
        return $this;
    }

    /**
     * Creates a new internal link and returns its identifier. An internal link is a
     * clickable area which directs to another place within the document.<br />
     * The identifier can then be passed to Cell(), Write(), Image() or Link(). The
     * destination is defined with SetLink().
     * @public
     * @since 1.5
     * @see Cell(), Write(), Image(), Link(), SetLink()
     * @return $this
     */
    public function addLink()
    {
        $this->tcpdf->AddLink();
        return $this;
    }

    /**
     * Defines the page and position a link points to.
     * @param $link (int) The link identifier returned by AddLink()
     * @param $y (float) Ordinate of target position; -1 indicates the current
     * position. The default value is 0 (top of page)
     * @param $page (int) Number of target page; -1 indicates the current page
     * (default value). If you prefix a page number with the * character, then this
     * page will not be changed when adding/deleting/moving pages. 
     * @public
     * @since 1.5
     * @see AddLink()
     * @return $this
     */
    public function setLink($link, $y = 0, $page = -1)
    {
        $this->tcpdf->SetLink($link, $y, $page);
        return $this;
    }

    /**
     * Puts a link on a rectangular area of the page.
     * Text or image links are generally put via Cell(), Write() or Image(), but
     * this method can be useful for instance to define a clickable area inside an
     * image.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $w (float) Width of the rectangle
     * @param $h (float) Height of the rectangle
     * @param $link (mixed) URL or identifier returned by AddLink()
     * @param $spaces (int) number of spaces on the text to link
     * @public
     * @since 1.5
     * @see AddLink(), Annotation(), Cell(), Write(), Image()
     * @return $this
     */
    public function link($x, $y, $w, $h, $link, $spaces = 0)
    {
        $this->tcpdf->Link($x, $y, $w, $h, $link, $spaces);
        return $this;
    }

    /**
     * Puts a markup annotation on a rectangular area of the page.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $w (float) Width of the rectangle
     * @param $h (float) Height of the rectangle
     * @param $text (string) annotation text or alternate content
     * @param $opt (array) array of options (see section 8.4 of PDF reference 1.7).
     * @param $spaces (int) number of spaces on the text to link
     * @public
     * @since 4.0.018 (2008-08-06)
     * @return $this
     */
    public function annotation($x, $y, $w, $h, $text, $opt = array('Subtype' => 'Text'), $spaces = 0)
    {
        $this->tcpdf->Annotation($x, $y, $w, $h, $text, $opt, $spaces);
        return $this;
    }

    /**
     * Prints a text cell at the specified position.
     * This method allows to place a string precisely on the page.
     * @param $x (float) Abscissa of the cell origin
     * @param $y (float) Ordinate of the cell origin
     * @param $txt (string) String to print
     * @param $fstroke (int) outline size in user units (false = disable)
     * @param $fclip (boolean) if true activate clipping mode (you must call
     * StartTransform() before this function and StopTransform() to stop the clipping
     * tranformation).
     * @param $ffill (boolean) if true fills the text
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $ln (int) Indicates where the current position should go after the
     * call. Possible values are:<ul><li>0: to the right (or left for RTL
     * languages)</li><li>1: to the beginning of the next line</li><li>2:
     * below</li></ul>Putting 1 is equivalent to putting 0 and calling Ln() just after.
     * Default value: 0.
     * @param $align (string) Allows to center or align the text. Possible values
     * are:<ul><li>L or empty string: left align (default value)</li><li>C:
     * center</li><li>R: right align</li><li>J: justify</li></ul>
     * @param $fill (boolean) Indicates if the cell background must be painted
     * (true) or transparent (false).
     * @param $link (mixed) URL or identifier returned by AddLink().
     * @param $stretch (int) font stretch mode: <ul><li>0 = disabled</li><li>1 =
     * horizontal scaling only if text is larger than cell width</li><li>2 = forced
     * horizontal scaling to fit cell width</li><li>3 = character spacing only if text
     * is larger than cell width</li><li>4 = forced character spacing to fit cell
     * width</li></ul> General font stretching and scaling values will be preserved
     * when possible.
     * @param $ignore_min_height (boolean) if true ignore automatic minimum height
     * value.
     * @param $calign (string) cell vertical alignment relative to the specified Y
     * value. Possible values are:<ul><li>T : cell top</li><li>A : font top</li><li>L :
     * font baseline</li><li>D : font bottom</li><li>B : cell bottom</li></ul>
     * @param $valign (string) text vertical alignment inside the cell. Possible
     * values are:<ul><li>T : top</li><li>C : center</li><li>B : bottom</li></ul>
     * @param $rtloff (boolean) if true uses the page top-left corner as origin of
     * axis for $x and $y initial position.
     * @public
     * @since 1.0
     * @see Cell(), Write(), MultiCell(), WriteHTML(), WriteHTMLCell()
     * @return $this
     */
    public function text($x, $y, $txt, $fstroke = false, $fclip = false, $ffill = true, $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M', $rtloff = false)
    {
        $this->tcpdf->Text($x, $y, $txt, $fstroke, $fclip, $ffill, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign, $rtloff);
        return $this;
    }

    /**
     * Whenever a page break condition is met, the method is called, and the break is
     * issued or not depending on the returned value.
     * The default implementation returns a value according to the mode selected by
     * SetAutoPageBreak().<br />
     * This method is called automatically and should not be called directly by the
     * application.
     * @return boolean
     * @public
     * @since 1.4
     * @see SetAutoPageBreak()
     */
    public function acceptPageBreak()
    {
        return $this->tcpdf->AcceptPageBreak();
    }

    /**
     * Prints a cell (rectangular area) with optional borders, background color and
     * character string. The upper-left corner of the cell corresponds to the current
     * position. The text can be aligned or centered. After the call, the current
     * position moves to the right or to the next line. It is possible to put a link on
     * the text.<br />
     * If automatic page breaking is enabled and the cell goes beyond the limit, a
     * page break is done before outputting.
     * @param $w (float) Cell width. If 0, the cell extends up to the right margin.
     * @param $h (float) Cell height. Default value: 0.
     * @param $txt (string) String to print. Default value: empty string.
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $ln (int) Indicates where the current position should go after the
     * call. Possible values are:<ul><li>0: to the right (or left for RTL
     * languages)</li><li>1: to the beginning of the next line</li><li>2:
     * below</li></ul> Putting 1 is equivalent to putting 0 and calling Ln() just
     * after. Default value: 0.
     * @param $align (string) Allows to center or align the text. Possible values
     * are:<ul><li>L or empty string: left align (default value)</li><li>C:
     * center</li><li>R: right align</li><li>J: justify</li></ul>
     * @param $fill (boolean) Indicates if the cell background must be painted
     * (true) or transparent (false).
     * @param $link (mixed) URL or identifier returned by AddLink().
     * @param $stretch (int) font stretch mode: <ul><li>0 = disabled</li><li>1 =
     * horizontal scaling only if text is larger than cell width</li><li>2 = forced
     * horizontal scaling to fit cell width</li><li>3 = character spacing only if text
     * is larger than cell width</li><li>4 = forced character spacing to fit cell
     * width</li></ul> General font stretching and scaling values will be preserved
     * when possible.
     * @param $ignore_min_height (boolean) if true ignore automatic minimum height
     * value.
     * @param $calign (string) cell vertical alignment relative to the specified Y
     * value. Possible values are:<ul><li>T : cell top</li><li>C : center</li><li>B :
     * cell bottom</li><li>A : font top</li><li>L : font baseline</li><li>D : font
     * bottom</li></ul>
     * @param $valign (string) text vertical alignment inside the cell. Possible
     * values are:<ul><li>T : top</li><li>C : center</li><li>B : bottom</li></ul>
     * @public
     * @since 1.0
     * @see SetFont(), SetDrawColor(), SetFillColor(), SetTextColor(),
     * SetLineWidth(), AddLink(), Ln(), MultiCell(), Write(), SetAutoPageBreak()
     * @return $this
     */
    public function cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M')
    {
        $this->tcpdf->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
        return $this;
    }

    /**
     * This method allows printing text with line breaks.
     * They can be automatic (as soon as the text reaches the right border of the
     * cell) or explicit (via the \n character). As many cells as necessary are output,
     * one below the other.<br />
     * Text can be aligned, centered or justified. The cell block can be framed and
     * the background painted.
     * @param $w (float) Width of cells. If 0, they extend up to the right margin
     * of the page.
     * @param $h (float) Cell minimum height. The cell extends automatically if
     * needed.
     * @param $txt (string) String to print
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $align (string) Allows to center or align the text. Possible values
     * are:<ul><li>L or empty string: left align</li><li>C: center</li><li>R: right
     * align</li><li>J: justification (default value when $ishtml=false)</li></ul>
     * @param $fill (boolean) Indicates if the cell background must be painted
     * (true) or transparent (false).
     * @param $ln (int) Indicates where the current position should go after the
     * call. Possible values are:<ul><li>0: to the right</li><li>1: to the beginning of
     * the next line [DEFAULT]</li><li>2: below</li></ul>
     * @param $x (float) x position in user units
     * @param $y (float) y position in user units
     * @param $reseth (boolean) if true reset the last cell height (default true).
     * @param $stretch (int) font stretch mode: <ul><li>0 = disabled</li><li>1 =
     * horizontal scaling only if text is larger than cell width</li><li>2 = forced
     * horizontal scaling to fit cell width</li><li>3 = character spacing only if text
     * is larger than cell width</li><li>4 = forced character spacing to fit cell
     * width</li></ul> General font stretching and scaling values will be preserved
     * when possible.
     * @param $ishtml (boolean) INTERNAL USE ONLY -- set to true if $txt is HTML
     * content (default = false). Never set this parameter to true, use instead
     * writeHTMLCell() or writeHTML() methods.
     * @param $autopadding (boolean) if true, uses internal padding and
     * automatically adjust it to account for line width.
     * @param $maxh (float) maximum height. It should be >= $h and less then
     * remaining space to the bottom of the page, or 0 for disable this feature. This
     * feature works only when $ishtml=false.
     * @param $valign (string) Vertical alignment of text (requires $maxh = $h >
     * 0). Possible values are:<ul><li>T: TOP</li><li>M: middle</li><li>B:
     * bottom</li></ul>. This feature works only when $ishtml=false and the cell must
     * fit in a single page.
     * @param $fitcell (boolean) if true attempt to fit all the text within the
     * cell by reducing the font size (do not work in HTML mode). $maxh must be greater
     * than 0 and equal to $h.
     * @return int Return the number of cells or 1 for html mode.
     * @public
     * @since 1.3
     * @see SetFont(), SetDrawColor(), SetFillColor(), SetTextColor(),
     * SetLineWidth(), Cell(), Write(), SetAutoPageBreak()
     */
    public function multiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false, $ln = 1, $x = '', $y = '', $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false)
    {
        return $this->tcpdf->MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell);
    }

    /**
     * This method return the estimated number of lines for print a simple text
     * string using Multicell() method.
     * @param $txt (string) String for calculating his height
     * @param $w (float) Width of cells. If 0, they extend up to the right margin
     * of the page.
     * @param $reseth (boolean) if true reset the last cell height (default false).
     * @param $autopadding (boolean) if true, uses internal padding and
     * automatically adjust it to account for line width (default true).
     * @param $cellpadding (float) Internal cell padding, if empty uses default
     * cell padding.
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @return float Return the minimal height needed for multicell method for
     * printing the $txt param.
     * @author Alexander Escalona Fern\E1ndez, Nicola Asuni
     * @public
     * @since 4.5.011
     */
    public function getNumLines($txt, $w = 0, $reseth = false, $autopadding = true, $cellpadding = '', $border = 0)
    {
        return $this->tcpdf->getNumLines($txt, $w, $reseth, $autopadding, $cellpadding, $border);
    }

    /**
     * This method return the estimated height needed for printing a simple text
     * string using the Multicell() method.
     * Generally, if you want to know the exact height for a block of content you
     * can use the following alternative technique:
     * @pre
     *  // store current object
     *  $pdf->startTransaction();
     *  // store starting values
     *  $start_y = $pdf->GetY();
     *  $start_page = $pdf->getPage();
     *  // call your printing functions with your parameters
     *  $pdf->MultiCell($w=0, $h=0, $txt, $border=1, $align='L', $fill=false,
     * $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true,
     * $maxh=0);
     *  // get the new Y
     *  $end_y = $pdf->GetY();
     *  $end_page = $pdf->getPage();
     *  // calculate height
     *  $height = 0;
     *  if ($end_page == $start_page) {
     *  	$height = $end_y - $start_y;
     *  } else {
     *  	for ($page=$start_page; $page <= $end_page; ++$page) {
     *  		\Osf\Pdf\Tc->setPage($page);
     *  		if ($page == $start_page) {
     *  			// first page
     *  			$height = \Osf\Pdf\Tc->h - $start_y - \Osf\Pdf\Tc->bMargin;
     *  		} elseif ($page == $end_page) {
     *  			// last page
     *  			$height = $end_y - \Osf\Pdf\Tc->tMargin;
     *  		} else {
     *  			$height = \Osf\Pdf\Tc->h - \Osf\Pdf\Tc->tMargin - \Osf\Pdf\Tc->bMargin;
     *  // restore previous object
     *  $pdf = $pdf->rollbackTransaction();
     * @param $w (float) Width of cells. If 0, they extend up to the right margin
     * of the page.
     * @param $txt (string) String for calculating his height
     * @param $reseth (boolean) if true reset the last cell height (default false).
     * @param $autopadding (boolean) if true, uses internal padding and
     * automatically adjust it to account for line width (default true).
     * @param $cellpadding (float) Internal cell padding, if empty uses default
     * cell padding.
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @return float Return the minimal height needed for multicell method for
     * printing the $txt param.
     * @author Nicola Asuni, Alexander Escalona Fern\E1ndez
     * @public
     */
    public function getStringHeight($w, $txt, $reseth = false, $autopadding = true, $cellpadding = '', $border = 0)
    {
        return $this->tcpdf->getStringHeight($w, $txt, $reseth, $autopadding, $cellpadding, $border);
    }

    /**
     * This method prints text from the current position.<br />
     * @param $h (float) Line height
     * @param $txt (string) String to print
     * @param $link (mixed) URL or identifier returned by AddLink()
     * @param $fill (boolean) Indicates if the cell background must be painted
     * (true) or transparent (false).
     * @param $align (string) Allows to center or align the text. Possible values
     * are:<ul><li>L or empty string: left align (default value)</li><li>C:
     * center</li><li>R: right align</li><li>J: justify</li></ul>
     * @param $ln (boolean) if true set cursor at the bottom of the line, otherwise
     * set cursor at the top of the line.
     * @param $stretch (int) font stretch mode: <ul><li>0 = disabled</li><li>1 =
     * horizontal scaling only if text is larger than cell width</li><li>2 = forced
     * horizontal scaling to fit cell width</li><li>3 = character spacing only if text
     * is larger than cell width</li><li>4 = forced character spacing to fit cell
     * width</li></ul> General font stretching and scaling values will be preserved
     * when possible.
     * @param $firstline (boolean) if true prints only the first line and return
     * the remaining string.
     * @param $firstblock (boolean) if true the string is the starting of a line.
     * @param $maxh (float) maximum height. It should be >= $h and less then
     * remaining space to the bottom of the page, or 0 for disable this feature.
     * @param $wadj (float) first line width will be reduced by this amount (used
     * in HTML mode).
     * @param $margin (array) margin array of the parent container
     * @return mixed Return the number of cells or the remaining string if
     * $firstline = true.
     * @public
     * @since 1.5
     */
    public function write($h, $txt, $link = '', $fill = false, $align = '', $ln = false, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0, $wadj = 0, $margin = '')
    {
        return $this->tcpdf->Write($h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh, $wadj, $margin);
    }

    /**
     * Puts an image in the page.
     * The upper-left corner must be given.
     * The dimensions can be specified in different ways:<ul>
     * <li>explicit width and height (expressed in user unit)</li>
     * <li>one explicit dimension, the other being calculated automatically in
     * order to keep the original proportions</li>
     * <li>no explicit dimension, in which case the image is put at 72
     * dpi</li></ul>
     * Supported formats are JPEG and PNG images whitout GD library and all images
     * supported by GD: GD, GD2, GD2PART, GIF, JPEG, PNG, BMP, XBM, XPM;
     * The format can be specified explicitly or inferred from the file
     * extension.<br />
     * It is possible to put a link on the image.<br />
     * Remark: if an image is used several times, only one copy will be embedded in
     * the file.<br />
     * @param $file (string) Name of the file containing the image or a '@'
     * character followed by the image data string. To link an image without embedding
     * it on the document, set an asterisk character before the URL (i.e.:
     * '*http://www.example.com/image.jpg').
     * @param $x (float) Abscissa of the upper-left corner (LTR) or upper-right
     * corner (RTL).
     * @param $y (float) Ordinate of the upper-left corner (LTR) or upper-right
     * corner (RTL).
     * @param $w (float) Width of the image in the page. If not specified or equal
     * to zero, it is automatically calculated.
     * @param $h (float) Height of the image in the page. If not specified or equal
     * to zero, it is automatically calculated.
     * @param $type (string) Image format. Possible values are (case insensitive):
     * JPEG and PNG (whitout GD library) and all images supported by GD: GD, GD2,
     * GD2PART, GIF, JPEG, PNG, BMP, XBM, XPM;. If not specified, the type is inferred
     * from the file extension.
     * @param $link (mixed) URL or identifier returned by AddLink().
     * @param $align (string) Indicates the alignment of the pointer next to image
     * insertion relative to image height. The value can be:<ul><li>T: top-right for
     * LTR or top-left for RTL</li><li>M: middle-right for LTR or middle-left for
     * RTL</li><li>B: bottom-right for LTR or bottom-left for RTL</li><li>N: next
     * line</li></ul>
     * @param $resize (mixed) If true resize (reduce) the image to fit $w and $h
     * (requires GD or ImageMagick library); if false do not resize; if 2 force resize
     * in all cases (upscaling and downscaling).
     * @param $dpi (int) dot-per-inch resolution used on resize
     * @param $palign (string) Allows to center or align the image on the current
     * line. Possible values are:<ul><li>L : left align</li><li>C : center</li><li>R :
     * right align</li><li>'' : empty string : left for LTR or right for RTL</li></ul>
     * @param $ismask (boolean) true if this image is a mask, false otherwise
     * @param $imgmask (mixed) image object returned by this function or false
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $fitbox (mixed) If not false scale image dimensions proportionally to
     * fit within the ($w, $h) box. $fitbox can be true or a 2 characters string
     * indicating the image alignment inside the box. The first character indicate the
     * horizontal alignment (L = left, C = center, R = right) the second character
     * indicate the vertical algnment (T = top, M = middle, B = bottom).
     * @param $hidden (boolean) If true do not display the image.
     * @param $fitonpage (boolean) If true the image is resized to not exceed page
     * dimensions.
     * @param $alt (boolean) If true the image will be added as alternative and not
     * directly printed (the ID of the image will be returned).
     * @param $altimgs (array) Array of alternate images IDs. Each alternative
     * image must be an array with two values: an integer representing the image ID
     * (the value returned by the Image method) and a boolean value to indicate if the
     * image is the default for printing.
     * @return image information
     * @public
     * @since 1.1
     */
    public function image($file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array())
    {
        return $this->tcpdf->Image($file, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi, $palign, $ismask, $imgmask, $border, $fitbox, $hidden, $fitonpage, $alt, $altimgs);
    }

    /**
     * Performs a line break.
     * The current abscissa goes back to the left margin and the ordinate increases
     * by the amount passed in parameter.
     * @param $h (float) The height of the break. By default, the value equals the
     * height of the last printed cell.
     * @param $cell (boolean) if true add the current left (or right o for RTL)
     * padding to the X coordinate
     * @public
     * @since 1.0
     * @see Cell()
     * @return $this
     */
    public function ln($h = '', $cell = false)
    {
        $this->tcpdf->Ln($h, $cell);
        return $this;
    }

    /**
     * Returns the relative X value of current position.
     * The value is relative to the left border for LTR languages and to the right
     * border for RTL languages.
     * @return float
     * @public
     * @since 1.2
     * @see SetX(), GetY(), SetY()
     */
    public function getX()
    {
        return $this->tcpdf->GetX();
    }

    /**
     * Returns the absolute X value of current position.
     * @return float
     * @public
     * @since 1.2
     * @see SetX(), GetY(), SetY()
     */
    public function getAbsX()
    {
        return $this->tcpdf->GetAbsX();
    }

    /**
     * Returns the ordinate of the current position.
     * @return float
     * @public
     * @since 1.0
     * @see SetY(), GetX(), SetX()
     */
    public function getY()
    {
        return $this->tcpdf->GetY();
    }

    /**
     * Defines the abscissa of the current position.
     * If the passed value is negative, it is relative to the right of the page (or
     * left if language is RTL).
     * @param $x (float) The value of the abscissa in user units.
     * @param $rtloff (boolean) if true always uses the page top-left corner as
     * origin of axis.
     * @public
     * @since 1.2
     * @see GetX(), GetY(), SetY(), SetXY()
     * @return $this
     */
    public function setX($x, $rtloff = false)
    {
        $this->tcpdf->SetX($x, $rtloff);
        return $this;
    }

    /**
     * Moves the current abscissa back to the left margin and sets the ordinate.
     * If the passed value is negative, it is relative to the bottom of the page.
     * @param $y (float) The value of the ordinate in user units.
     * @param $resetx (bool) if true (default) reset the X position.
     * @param $rtloff (boolean) if true always uses the page top-left corner as
     * origin of axis.
     * @public
     * @since 1.0
     * @see GetX(), GetY(), SetY(), SetXY()
     * @return $this
     */
    public function setY($y, $resetx = true, $rtloff = false)
    {
        $this->tcpdf->SetY($y, $resetx, $rtloff);
        return $this;
    }

    /**
     * Defines the abscissa and ordinate of the current position.
     * If the passed values are negative, they are relative respectively to the
     * right and bottom of the page.
     * @param $x (float) The value of the abscissa.
     * @param $y (float) The value of the ordinate.
     * @param $rtloff (boolean) if true always uses the page top-left corner as
     * origin of axis.
     * @public
     * @since 1.2
     * @see SetX(), SetY()
     * @return $this
     */
    public function setXY($x, $y, $rtloff = false)
    {
        $this->tcpdf->SetXY($x, $y, $rtloff);
        return $this;
    }

    /**
     * Set the absolute X coordinate of the current pointer.
     * @param $x (float) The value of the abscissa in user units.
     * @public
     * @since 5.9.186 (2012-09-13)
     * @see setAbsX(), setAbsY(), SetAbsXY()
     * @return $this
     */
    public function setAbsX($x)
    {
        $this->tcpdf->SetAbsX($x);
        return $this;
    }

    /**
     * Set the absolute Y coordinate of the current pointer.
     * @param $y (float) (float) The value of the ordinate in user units.
     * @public
     * @since 5.9.186 (2012-09-13)
     * @see setAbsX(), setAbsY(), SetAbsXY()
     * @return $this
     */
    public function setAbsY($y)
    {
        $this->tcpdf->SetAbsY($y);
        return $this;
    }

    /**
     * Set the absolute X and Y coordinates of the current pointer.
     * @param $x (float) The value of the abscissa in user units.
     * @param $y (float) (float) The value of the ordinate in user units.
     * @public
     * @since 5.9.186 (2012-09-13)
     * @see setAbsX(), setAbsY(), SetAbsXY()
     * @return $this
     */
    public function setAbsXY($x, $y)
    {
        $this->tcpdf->SetAbsXY($x, $y);
        return $this;
    }

    /**
     * Send the document to a given destination: string, local file or browser.
     * In the last case, the plug-in may be used (if present) or a download ("Save
     * as" dialog box) may be forced.<br />
     * The method first calls Close() if necessary to terminate the document.
     * @param $name (string) The name of the file when saved. Note that special
     * characters are removed and blanks characters are replaced with the underscore
     * character.
     * @param $dest (string) Destination where to send the document. It can take
     * one of the following values:<ul><li>I: send the file inline to the browser
     * (default). The plug-in is used if available. The name given by name is used when
     * one selects the "Save as" option on the link generating the PDF.</li><li>D: send
     * to the browser and force a file download with the name given by name.</li><li>F:
     * save to a local server file with the name given by name.</li><li>S: return the
     * document as a string (name is ignored).</li><li>FI: equivalent to F + I
     * option</li><li>FD: equivalent to F + D option</li><li>E: return the document as
     * base64 mime multi-part email attachment (RFC 2045)</li></ul>
     * @return string
     * @public
     * @since 1.0
     * @see Close()
     */
    public function output($name = 'doc.pdf', $dest = 'I')
    {
        return $this->tcpdf->Output($name, $dest);
    }

    /**
     * Unset all class variables except the following critical variables.
     * @param $destroyall (boolean) if true destroys all class variables, otherwise
     * preserves critical variables.
     * @param $preserve_objcopy (boolean) if true preserves the objcopy variable
     * @public
     * @since 4.5.016 (2009-02-24)
     */
//    public function _destroy($destroyall = false, $preserve_objcopy = false)
//    {
//        return $this->tcpdf->_destroy($destroyall, $preserve_objcopy);
//    }

    /**
     * Set additional XMP data to be added on the default XMP data just before the
     * end of "x:xmpmeta" tag.
     * IMPORTANT: This data is added as-is without controls, so you have to
     * validate your data before using this method!
     * @param $xmp (string) Custom XMP data.
     * @since 5.9.128 (2011-10-06)
     * @public
     * @return $this
     */
    public function setExtraXMP($xmp)
    {
        $this->tcpdf->setExtraXMP($xmp);
        return $this;
    }

    /**
     * Set the document creation timestamp
     * @param $time (mixed) Document creation timestamp in seconds or date-time
     * string.
     * @public
     * @since 5.9.152 (2012-03-23)
     * @return $this
     */
    public function setDocCreationTimestamp($time)
    {
        $this->tcpdf->setDocCreationTimestamp($time);
        return $this;
    }

    /**
     * Set the document modification timestamp
     * @param $time (mixed) Document modification timestamp in seconds or date-time
     * string.
     * @public
     * @since 5.9.152 (2012-03-23)
     * @return $this
     */
    public function setDocModificationTimestamp($time)
    {
        $this->tcpdf->setDocModificationTimestamp($time);
        return $this;
    }

    /**
     * Returns document creation timestamp in seconds.
     * @return (int) Creation timestamp in seconds.
     * @public
     * @since 5.9.152 (2012-03-23)
     */
    public function getDocCreationTimestamp()
    {
        return $this->tcpdf->getDocCreationTimestamp();
    }

    /**
     * Returns document modification timestamp in seconds.
     * @return (int) Modfication timestamp in seconds.
     * @public
     * @since 5.9.152 (2012-03-23)
     */
    public function getDocModificationTimestamp()
    {
        return $this->tcpdf->getDocModificationTimestamp();
    }

    /**
     * Set header font.
     * @param $font (array) Array describing the basic font parameters: (family,
     * style, size).
     * @public
     * @since 1.1
     * @return $this
     */
    public function setHeaderFont($font)
    {
        $this->tcpdf->setHeaderFont($font);
        return $this;
    }

    /**
     * Get header font.
     * @return array() Array describing the basic font parameters: (family, style,
     * size).
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getHeaderFont()
    {
        return $this->tcpdf->getHeaderFont();
    }

    /**
     * Set footer font.
     * @param $font (array) Array describing the basic font parameters: (family,
     * style, size).
     * @public
     * @since 1.1
     * @return $this
     */
    public function setFooterFont($font)
    {
        $this->tcpdf->setFooterFont($font);
        return $this;
    }

    /**
     * Get Footer font.
     * @return array() Array describing the basic font parameters: (family, style,
     * size).
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getFooterFont()
    {
        return $this->tcpdf->getFooterFont();
    }

    /**
     * Set language array.
     * @param $language (array)
     * @public
     * @since 1.1
     * @return $this
     */
    public function setLanguageArray($language)
    {
        $this->tcpdf->setLanguageArray($language);
        return $this;
    }

    /**
     * Returns the PDF data.
     * @public
     */
    public function getPDFData()
    {
        return $this->tcpdf->getPDFData();
    }

    /**
     * Output anchor link.
     * @param $url (string) link URL or internal link (i.e.: &lt;a
     * href="#23,4.5"&gt;link to page 23 at 4.5 Y position&lt;/a&gt;)
     * @param $name (string) link name
     * @param $fill (boolean) Indicates if the cell background must be painted
     * (true) or transparent (false).
     * @param $firstline (boolean) if true prints only the first line and return
     * the remaining string.
     * @param $color (array) array of RGB text color
     * @param $style (string) font style (U, D, B, I)
     * @param $firstblock (boolean) if true the string is the starting of a line.
     * @return the number of cells used or the remaining text if $firstline = true;
     * @public
     */
    public function addHtmlLink($url, $name, $fill = false, $firstline = false, $color = '', $style = -1, $firstblock = false)
    {
        return $this->tcpdf->addHtmlLink($url, $name, $fill, $firstline, $color, $style, $firstblock);
    }

    /**
     * Converts pixels to User's Units.
     * @param $px (int) pixels
     * @return float value in user's unit
     * @public
     * @see setImageScale(), getImageScale()
     */
    public function pixelsToUnits($px)
    {
        return $this->tcpdf->pixelsToUnits($px);
    }

    /**
     * Reverse function for htmlentities.
     * Convert entities in UTF-8.
     * @param $text_to_convert (string) Text to convert.
     * @return string converted text string
     * @public
     */
    public function unhtmlentities($text_to_convert)
    {
        return $this->tcpdf->unhtmlentities($text_to_convert);
    }

    /**
     * Set document protection
     * Remark: the protection against modification is for people who have the full
     * Acrobat product.
     * If you don't set any password, the document will open as usual. If you set a
     * user password, the PDF viewer will ask for it before displaying the document.
     * The master password, if different from the user one, can be used to get full
     * access.
     * Note: protecting a document requires to encrypt it, which increases the
     * processing time a lot. This can cause a PHP time-out in some cases, especially
     * if the document contains images or fonts.
     * @param $permissions (Array) the set of permissions (specify the ones you
     * want to block):<ul><li>print : Print the document;</li><li>modify : Modify the
     * contents of the document by operations other than those controlled by
     * 'fill-forms', 'extract' and 'assemble';</li><li>copy : Copy or otherwise extract
     * text and graphics from the document;</li><li>annot-forms : Add or modify text
     * annotations, fill in interactive form fields, and, if 'modify' is also set,
     * create or modify interactive form fields (including signature
     * fields);</li><li>fill-forms : Fill in existing interactive form fields
     * (including signature fields), even if 'annot-forms' is not
     * specified;</li><li>extract : Extract text and graphics (in support of
     * accessibility to users with disabilities or for other
     * purposes);</li><li>assemble : Assemble the document (insert, rotate, or delete
     * pages and create bookmarks or thumbnail images), even if 'modify' is not
     * set;</li><li>print-high : Print the document to a representation from which a
     * faithful digital copy of the PDF content could be generated. When this is not
     * set, printing is limited to a low-level representation of the appearance,
     * possibly of degraded quality.</li><li>owner : (inverted logic - only for
     * public-key) when set permits change of encryption and enables all other
     * permissions.</li></ul>
     * @param $user_pass (String) user password. Empty by default.
     * @param $owner_pass (String) owner password. If not specified, a random value
     * is used.
     * @param $mode (int) encryption strength: 0 = RC4 40 bit; 1 = RC4 128 bit; 2 =
     * AES 128 bit; 3 = AES 256 bit.
     * @param $pubkeys (String) array of recipients containing public-key
     * certificates ('c') and permissions ('p'). For example: array(array('c' =>
     * 'file://../examples/data/cert/tcpdf.crt', 'p' => array('print')))
     * @public
     * @since 2.0.000 (2008-01-02)
     * @author Nicola Asuni
     * @return $this
     */
    public function setProtection($permissions = array('print', 'modify', 'copy', 'annot-forms', 'fill-forms', 'extract', 'assemble', 'print-high'), $user_pass = '', $owner_pass = null, $mode = 0, $pubkeys = null)
    {
        $this->tcpdf->SetProtection($permissions, $user_pass, $owner_pass, $mode, $pubkeys);
        return $this;
    }

    /**
     * Starts a 2D tranformation saving current graphic state.
     * This function must be called before scaling, mirroring, translation,
     * rotation and skewing.
     * Use StartTransform() before, and StopTransform() after the transformations
     * to restore the normal behavior.
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function startTransform()
    {
        $this->tcpdf->StartTransform();
        return $this;
    }

    /**
     * Stops a 2D tranformation restoring previous graphic state.
     * This function must be called after scaling, mirroring, translation, rotation
     * and skewing.
     * Use StartTransform() before, and StopTransform() after the transformations
     * to restore the normal behavior.
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function stopTransform()
    {
        $this->tcpdf->StopTransform();
        return $this;
    }

    /**
     * Horizontal Scaling.
     * @param $s_x (float) scaling factor for width as percent. 0 is not allowed.
     * @param $x (int) abscissa of the scaling center. Default is current x
     * position
     * @param $y (int) ordinate of the scaling center. Default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function scaleX($s_x, $x = '', $y = '')
    {
        $this->tcpdf->ScaleX($s_x, $x, $y);
        return $this;
    }

    /**
     * Vertical Scaling.
     * @param $s_y (float) scaling factor for height as percent. 0 is not allowed.
     * @param $x (int) abscissa of the scaling center. Default is current x
     * position
     * @param $y (int) ordinate of the scaling center. Default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function scaleY($s_y, $x = '', $y = '')
    {
        $this->tcpdf->ScaleY($s_y, $x, $y);
        return $this;
    }

    /**
     * Vertical and horizontal proportional Scaling.
     * @param $s (float) scaling factor for width and height as percent. 0 is not
     * allowed.
     * @param $x (int) abscissa of the scaling center. Default is current x
     * position
     * @param $y (int) ordinate of the scaling center. Default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function scaleXY($s, $x = '', $y = '')
    {
        $this->tcpdf->ScaleXY($s, $x, $y);
        return $this;
    }

    /**
     * Vertical and horizontal non-proportional Scaling.
     * @param $s_x (float) scaling factor for width as percent. 0 is not allowed.
     * @param $s_y (float) scaling factor for height as percent. 0 is not allowed.
     * @param $x (int) abscissa of the scaling center. Default is current x
     * position
     * @param $y (int) ordinate of the scaling center. Default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function scale($s_x, $s_y, $x = '', $y = '')
    {
        $this->tcpdf->Scale($s_x, $s_y, $x, $y);
        return $this;
    }

    /**
     * Horizontal Mirroring.
     * @param $x (int) abscissa of the point. Default is current x position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function mirrorH($x = '')
    {
        $this->tcpdf->MirrorH($x);
        return $this;
    }

    /**
     * Verical Mirroring.
     * @param $y (int) ordinate of the point. Default is current y position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function mirrorV($y = '')
    {
        $this->tcpdf->MirrorV($y);
        return $this;
    }

    /**
     * Point reflection mirroring.
     * @param $x (int) abscissa of the point. Default is current x position
     * @param $y (int) ordinate of the point. Default is current y position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function mirrorP($x = '', $y = '')
    {
        $this->tcpdf->MirrorP($x, $y);
        return $this;
    }

    /**
     * Reflection against a straight line through point (x, y) with the gradient
     * angle (angle).
     * @param $angle (float) gradient angle of the straight line. Default is 0
     * (horizontal line).
     * @param $x (int) abscissa of the point. Default is current x position
     * @param $y (int) ordinate of the point. Default is current y position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function mirrorL($angle = 0, $x = '', $y = '')
    {
        $this->tcpdf->MirrorL($angle, $x, $y);
        return $this;
    }

    /**
     * Translate graphic object horizontally.
     * @param $t_x (int) movement to the right (or left for RTL)
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function translateX($t_x)
    {
        $this->tcpdf->TranslateX($t_x);
        return $this;
    }

    /**
     * Translate graphic object vertically.
     * @param $t_y (int) movement to the bottom
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function translateY($t_y)
    {
        $this->tcpdf->TranslateY($t_y);
        return $this;
    }

    /**
     * Translate graphic object horizontally and vertically.
     * @param $t_x (int) movement to the right
     * @param $t_y (int) movement to the bottom
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function translate($t_x, $t_y)
    {
        $this->tcpdf->Translate($t_x, $t_y);
        return $this;
    }

    /**
     * Rotate object.
     * @param $angle (float) angle in degrees for counter-clockwise rotation
     * @param $x (int) abscissa of the rotation center. Default is current x
     * position
     * @param $y (int) ordinate of the rotation center. Default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function rotate($angle, $x = '', $y = '')
    {
        $this->tcpdf->Rotate($angle, $x, $y);
        return $this;
    }

    /**
     * Skew horizontally.
     * @param $angle_x (float) angle in degrees between -90 (skew to the left) and
     * 90 (skew to the right)
     * @param $x (int) abscissa of the skewing center. default is current x
     * position
     * @param $y (int) ordinate of the skewing center. default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function skewX($angle_x, $x = '', $y = '')
    {
        $this->tcpdf->SkewX($angle_x, $x, $y);
        return $this;
    }

    /**
     * Skew vertically.
     * @param $angle_y (float) angle in degrees between -90 (skew to the bottom)
     * and 90 (skew to the top)
     * @param $x (int) abscissa of the skewing center. default is current x
     * position
     * @param $y (int) ordinate of the skewing center. default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function skewY($angle_y, $x = '', $y = '')
    {
        $this->tcpdf->SkewY($angle_y, $x, $y);
        return $this;
    }

    /**
     * Skew.
     * @param $angle_x (float) angle in degrees between -90 (skew to the left) and
     * 90 (skew to the right)
     * @param $angle_y (float) angle in degrees between -90 (skew to the bottom)
     * and 90 (skew to the top)
     * @param $x (int) abscissa of the skewing center. default is current x
     * position
     * @param $y (int) ordinate of the skewing center. default is current y
     * position
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see StartTransform(), StopTransform()
     * @return $this
     */
    public function skew($angle_x, $angle_y, $x = '', $y = '')
    {
        $this->tcpdf->Skew($angle_x, $angle_y, $x, $y);
        return $this;
    }

    /**
     * Defines the line width. By default, the value equals 0.2 mm. The method can be
     * called before the first page is created and the value is retained from page to
     * page.
     * @param $width (float) The width.
     * @public
     * @since 1.0
     * @see Line(), Rect(), Cell(), MultiCell()
     * @return $this
     */
    public function setLineWidth($width)
    {
        $this->tcpdf->SetLineWidth($width);
        return $this;
    }

    /**
     * Returns the current the line width.
     * @return int Line width
     * @public
     * @since 2.1.000 (2008-01-07)
     * @see Line(), SetLineWidth()
     */
    public function getLineWidth()
    {
        return $this->tcpdf->GetLineWidth();
    }

    /**
     * Set line style.
     * @param $style (array) Line style. Array with keys among the following:
     * <ul>
     * 	 *	 <li>width (float): Width of the line in user units.</li>
     * 	 *	 <li>cap (string): Type of cap to put on the line. Possible values are:
     * butt, round, square. The difference between "square" and "butt" is that
     * "square" projects a flat end past the end of the line.</li>
     * 	 *	 <li>join (string): Type of join. Possible values are: miter, round,
     * bevel.</li>
     * 	 *	 <li>dash (mixed): Dash pattern. Is 0 (without dash) or string with
     * series of length values, which are the lengths of the on and off dashes.
     * For example: "2" represents 2 on, 2 off, 2 on, 2 off, ...; "2,1" is 2 on,
     * 1 off, 2 on, 1 off, ...</li>
     * 	 *	 <li>phase (integer): Modifier on the dash pattern which is used to shift
     * the point at which the pattern starts.</li>
     * 	 *	 <li>color (array): Draw color. Format: array(GREY) or array(R,G,B) or
     * array(C,M,Y,K) or array(C,M,Y,K,SpotColorName).</li>
     * </ul>
     * @param $ret (boolean) if true do not send the command.
     * @return string the PDF command
     * @public
     * @since 2.1.000 (2008-01-08)
     */
    public function setLineStyle($style, $ret = false)
    {
        return $this->tcpdf->SetLineStyle($style, $ret);
    }

    /**
     * Draws a line between two points.
     * @param $x1 (float) Abscissa of first point.
     * @param $y1 (float) Ordinate of first point.
     * @param $x2 (float) Abscissa of second point.
     * @param $y2 (float) Ordinate of second point.
     * @param $style (array) Line style. Array like for SetLineStyle(). Default
     * value: default line style (empty array).
     * @public
     * @since 1.0
     * @see SetLineWidth(), SetDrawColor(), SetLineStyle()
     * @return $this
     */
    public function line($x1, $y1, $x2, $y2, $style = array())
    {
        $this->tcpdf->Line($x1, $y1, $x2, $y2, $style);
        return $this;
    }

    /**
     * Draws a rectangle.
     * @param $x (float) Abscissa of upper-left corner.
     * @param $y (float) Ordinate of upper-left corner.
     * @param $w (float) Width.
     * @param $h (float) Height.
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $border_style (array) Border style of rectangle. Array with keys
     * among the following:
     * <ul>
     * 	 *	 <li>all: Line style of all borders. Array like for SetLineStyle().</li>
     * 	 *	 <li>L, T, R, B or combinations: Line style of left, top, right or bottom
     * border. Array like for SetLineStyle().</li>
     * </ul>
     * If a key is not present or is null, the correspondent border is not drawn.
     * Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @public
     * @since 1.0
     * @see SetLineStyle()
     * @return $this
     */
    public function rect($x, $y, $w, $h, $style = '', $border_style = array(), $fill_color = array())
    {
        $this->tcpdf->Rect($x, $y, $w, $h, $style, $border_style, $fill_color);
        return $this;
    }

    /**
     * Draws a Bezier curve.
     * The Bezier curve is a tangent to the line between the control points at
     * either end of the curve.
     * @param $x0 (float) Abscissa of start point.
     * @param $y0 (float) Ordinate of start point.
     * @param $x1 (float) Abscissa of control point 1.
     * @param $y1 (float) Ordinate of control point 1.
     * @param $x2 (float) Abscissa of control point 2.
     * @param $y2 (float) Ordinate of control point 2.
     * @param $x3 (float) Abscissa of end point.
     * @param $y3 (float) Ordinate of end point.
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of curve. Array like for
     * SetLineStyle(). Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @public
     * @see SetLineStyle()
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function curve($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $style = '', $line_style = array(), $fill_color = array())
    {
        $this->tcpdf->Curve($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $style, $line_style, $fill_color);
        return $this;
    }

    /**
     * Draws a poly-Bezier curve.
     * Each Bezier curve segment is a tangent to the line between the control
     * points at
     * either end of the curve.
     * @param $x0 (float) Abscissa of start point.
     * @param $y0 (float) Ordinate of start point.
     * @param $segments (float) An array of bezier descriptions. Format: array(x1,
     * y1, x2, y2, x3, y3).
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of curve. Array like for
     * SetLineStyle(). Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @public
     * @see SetLineStyle()
     * @since 3.0008 (2008-05-12)
     * @return $this
     */
    public function polycurve($x0, $y0, $segments, $style = '', $line_style = array(), $fill_color = array())
    {
        $this->tcpdf->Polycurve($x0, $y0, $segments, $style, $line_style, $fill_color);
        return $this;
    }

    /**
     * Draws an ellipse.
     * An ellipse is formed from n Bezier curves.
     * @param $x0 (float) Abscissa of center point.
     * @param $y0 (float) Ordinate of center point.
     * @param $rx (float) Horizontal radius.
     * @param $ry (float) Vertical radius (if ry = 0 then is a circle, see
     * Circle()). Default value: 0.
     * @param $angle: (float) Angle oriented (anti-clockwise). Default value: 0.
     * @param $astart: (float) Angle start of draw line. Default value: 0.
     * @param $afinish: (float) Angle finish of draw line. Default value: 360.
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of ellipse. Array like for
     * SetLineStyle(). Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @param $nc (integer) Number of curves used to draw a 90 degrees portion of
     * ellipse.
     * @author Nicola Asuni
     * @public
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function ellipse($x0, $y0, $rx, $ry = '', $angle = 0, $astart = 0, $afinish = 360, $style = '', $line_style = array(), $fill_color = array(), $nc = 2)
    {
        $this->tcpdf->Ellipse($x0, $y0, $rx, $ry, $angle, $astart, $afinish, $style, $line_style, $fill_color, $nc);
        return $this;
    }

    /**
     * Draws a circle.
     * A circle is formed from n Bezier curves.
     * @param $x0 (float) Abscissa of center point.
     * @param $y0 (float) Ordinate of center point.
     * @param $r (float) Radius.
     * @param $angstr: (float) Angle start of draw line. Default value: 0.
     * @param $angend: (float) Angle finish of draw line. Default value: 360.
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of circle. Array like for
     * SetLineStyle(). Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(red, green, blue).
     * Default value: default color (empty array).
     * @param $nc (integer) Number of curves used to draw a 90 degrees portion of
     * circle.
     * @public
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function circle($x0, $y0, $r, $angstr = 0, $angend = 360, $style = '', $line_style = array(), $fill_color = array(), $nc = 2)
    {
        $this->tcpdf->Circle($x0, $y0, $r, $angstr, $angend, $style, $line_style, $fill_color, $nc);
        return $this;
    }

    /**
     * Draws a polygonal line
     * @param $p (array) Points 0 to ($np - 1). Array with values (x0, y0, x1,
     * y1,..., x(np-1), y(np - 1))
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of polygon. Array with keys among the
     * following:
     * <ul>
     * 	 *	 <li>all: Line style of all lines. Array like for SetLineStyle().</li>
     * 	 *	 <li>0 to ($np - 1): Line style of each line. Array like for
     * SetLineStyle().</li>
     * </ul>
     * If a key is not present or is null, not draws the line. Default value is
     * default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @since 4.8.003 (2009-09-15)
     * @public
     * @return $this
     */
    public function polyLine($p, $style = '', $line_style = array(), $fill_color = array())
    {
        $this->tcpdf->PolyLine($p, $style, $line_style, $fill_color);
        return $this;
    }

    /**
     * Draws a polygon.
     * @param $p (array) Points 0 to ($np - 1). Array with values (x0, y0, x1,
     * y1,..., x(np-1), y(np - 1))
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of polygon. Array with keys among the
     * following:
     * <ul>
     * 	 *	 <li>all: Line style of all lines. Array like for SetLineStyle().</li>
     * 	 *	 <li>0 to ($np - 1): Line style of each line. Array like for
     * SetLineStyle().</li>
     * </ul>
     * If a key is not present or is null, not draws the line. Default value is
     * default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @param $closed (boolean) if true the polygon is closes, otherwise will
     * remain open
     * @public
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function polygon($p, $style = '', $line_style = array(), $fill_color = array(), $closed = true)
    {
        $this->tcpdf->Polygon($p, $style, $line_style, $fill_color, $closed);
        return $this;
    }

    /**
     * Draws a regular polygon.
     * @param $x0 (float) Abscissa of center point.
     * @param $y0 (float) Ordinate of center point.
     * @param $r: (float) Radius of inscribed circle.
     * @param $ns (integer) Number of sides.
     * @param $angle (float) Angle oriented (anti-clockwise). Default value: 0.
     * @param $draw_circle (boolean) Draw inscribed circle or not. Default value:
     * false.
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of polygon sides. Array with keys
     * among the following:
     * <ul>
     * 	 *	 <li>all: Line style of all sides. Array like for SetLineStyle().</li>
     * 	 *	 <li>0 to ($ns - 1): Line style of each side. Array like for
     * SetLineStyle().</li>
     * </ul>
     * If a key is not present or is null, not draws the side. Default value is
     * default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(red, green, blue).
     * Default value: default color (empty array).
     * @param $circle_style (string) Style of rendering of inscribed circle (if
     * draws). Possible values are:
     * <ul>
     * 	 *	 <li>D or empty string: Draw (default).</li>
     * 	 *	 <li>F: Fill.</li>
     * 	 *	 <li>DF or FD: Draw and fill.</li>
     * 	 *	 <li>CNZ: Clipping mode (using the even-odd rule to determine which regions
     * lie inside the clipping path).</li>
     * 	 *	 <li>CEO: Clipping mode (using the nonzero winding number rule to determine
     * which regions lie inside the clipping path).</li>
     * </ul>
     * @param $circle_outLine_style (array) Line style of inscribed circle (if
     * draws). Array like for SetLineStyle(). Default value: default line style (empty
     * array).
     * @param $circle_fill_color (array) Fill color of inscribed circle (if draws).
     * Format: array(red, green, blue). Default value: default color (empty array).
     * @public
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function regularPolygon($x0, $y0, $r, $ns, $angle = 0, $draw_circle = false, $style = '', $line_style = array(), $fill_color = array(), $circle_style = '', $circle_outLine_style = array(), $circle_fill_color = array())
    {
        $this->tcpdf->RegularPolygon($x0, $y0, $r, $ns, $angle, $draw_circle, $style, $line_style, $fill_color, $circle_style, $circle_outLine_style, $circle_fill_color);
        return $this;
    }

    /**
     * Draws a star polygon
     * @param $x0 (float) Abscissa of center point.
     * @param $y0 (float) Ordinate of center point.
     * @param $r (float) Radius of inscribed circle.
     * @param $nv (integer) Number of vertices.
     * @param $ng (integer) Number of gap (if ($ng % $nv = 1) then is a regular
     * polygon).
     * @param $angle: (float) Angle oriented (anti-clockwise). Default value: 0.
     * @param $draw_circle: (boolean) Draw inscribed circle or not. Default value
     * is false.
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $line_style (array) Line style of polygon sides. Array with keys
     * among the following:
     * <ul>
     * 	 *	 <li>all: Line style of all sides. Array like for
     * SetLineStyle().</li>
     * 	 *	 <li>0 to (n - 1): Line style of each side. Array like for
     * SetLineStyle().</li>
     * </ul>
     * If a key is not present or is null, not draws the side. Default value is
     * default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(red, green, blue).
     * Default value: default color (empty array).
     * @param $circle_style (string) Style of rendering of inscribed circle (if
     * draws). Possible values are:
     * <ul>
     * 	 *	 <li>D or empty string: Draw (default).</li>
     * 	 *	 <li>F: Fill.</li>
     * 	 *	 <li>DF or FD: Draw and fill.</li>
     * 	 *	 <li>CNZ: Clipping mode (using the even-odd rule to determine which regions
     * lie inside the clipping path).</li>
     * 	 *	 <li>CEO: Clipping mode (using the nonzero winding number rule to determine
     * which regions lie inside the clipping path).</li>
     * </ul>
     * @param $circle_outLine_style (array) Line style of inscribed circle (if
     * draws). Array like for SetLineStyle(). Default value: default line style (empty
     * array).
     * @param $circle_fill_color (array) Fill color of inscribed circle (if draws).
     * Format: array(red, green, blue). Default value: default color (empty array).
     * @public
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function starPolygon($x0, $y0, $r, $nv, $ng, $angle = 0, $draw_circle = false, $style = '', $line_style = array(), $fill_color = array(), $circle_style = '', $circle_outLine_style = array(), $circle_fill_color = array())
    {
        $this->tcpdf->StarPolygon($x0, $y0, $r, $nv, $ng, $angle, $draw_circle, $style, $line_style, $fill_color, $circle_style, $circle_outLine_style, $circle_fill_color);
        return $this;
    }

    /**
     * Draws a rounded rectangle.
     * @param $x (float) Abscissa of upper-left corner.
     * @param $y (float) Ordinate of upper-left corner.
     * @param $w (float) Width.
     * @param $h (float) Height.
     * @param $r (float) the radius of the circle used to round off the corners of
     * the rectangle.
     * @param $round_corner (string) Draws rounded corner or not. String with a 0
     * (not rounded i-corner) or 1 (rounded i-corner) in i-position. Positions are, in
     * order and begin to 0: top right, bottom right, bottom left and top left. Default
     * value: all rounded corner ("1111").
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $border_style (array) Border style of rectangle. Array like for
     * SetLineStyle(). Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @public
     * @since 2.1.000 (2008-01-08)
     * @return $this
     */
    public function roundedRect($x, $y, $w, $h, $r, $round_corner = '1111', $style = '', $border_style = array(), $fill_color = array())
    {
        $this->tcpdf->RoundedRect($x, $y, $w, $h, $r, $round_corner, $style, $border_style, $fill_color);
        return $this;
    }

    /**
     * Draws a rounded rectangle.
     * @param $x (float) Abscissa of upper-left corner.
     * @param $y (float) Ordinate of upper-left corner.
     * @param $w (float) Width.
     * @param $h (float) Height.
     * @param $rx (float) the x-axis radius of the ellipse used to round off the
     * corners of the rectangle.
     * @param $ry (float) the y-axis radius of the ellipse used to round off the
     * corners of the rectangle.
     * @param $round_corner (string) Draws rounded corner or not. String with a 0
     * (not rounded i-corner) or 1 (rounded i-corner) in i-position. Positions are, in
     * order and begin to 0: top right, bottom right, bottom left and top left. Default
     * value: all rounded corner ("1111").
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $border_style (array) Border style of rectangle. Array like for
     * SetLineStyle(). Default value: default line style (empty array).
     * @param $fill_color (array) Fill color. Format: array(GREY) or array(R,G,B)
     * or array(C,M,Y,K) or array(C,M,Y,K,SpotColorName). Default value: default color
     * (empty array).
     * @public
     * @since 4.9.019 (2010-04-22)
     * @return $this
     */
    public function roundedRectXY($x, $y, $w, $h, $rx, $ry, $round_corner = '1111', $style = '', $border_style = array(), $fill_color = array())
    {
        $this->tcpdf->RoundedRectXY($x, $y, $w, $h, $rx, $ry, $round_corner, $style, $border_style, $fill_color);
        return $this;
    }

    /**
     * Draws a grahic arrow.
     * @param $x0 (float) Abscissa of first point.
     * @param $y0 (float) Ordinate of first point.
     * @param $x1 (float) Abscissa of second point.
     * @param $y1 (float) Ordinate of second point.
     * @param $head_style (int) (0 = draw only arrowhead arms, 1 = draw closed
     * arrowhead, but no fill, 2 = closed and filled arrowhead, 3 = filled arrowhead)
     * @param $arm_size (float) length of arrowhead arms
     * @param $arm_angle (int) angle between an arm and the shaft
     * @author Piotr Galecki, Nicola Asuni, Andy Meier
     * @since 4.6.018 (2009-07-10)
     * @return $this
     */
    public function arrow($x0, $y0, $x1, $y1, $head_style = 0, $arm_size = 5, $arm_angle = 15)
    {
        $this->tcpdf->Arrow($x0, $y0, $x1, $y1, $head_style, $arm_size, $arm_angle);
        return $this;
    }

    /**
     * Add a Named Destination.
     * NOTE: destination names are unique, so only last entry will be saved.
     * @param $name (string) Destination name.
     * @param $y (float) Y position in user units of the destiantion on the
     * selected page (default = -1 = current position; 0 = page start;).
     * @param $page (int|string) Target page number (leave empty for current page).
     * If you prefix a page number with the * character, then this page will not be
     * changed when adding/deleting/moving pages.
     * @param $x (float) X position in user units of the destiantion on the
     * selected page (default = -1 = current position;).
     * @return (string) Stripped named destination identifier or false in case of
     * error.
     * @public
     * @author Christian Deligant, Nicola Asuni
     * @since 5.9.097 (2011-06-23)
     */
    public function setDestination($name, $y = -1, $page = '', $x = -1)
    {
        return $this->tcpdf->setDestination($name, $y, $page, $x);
    }

    /**
     * Return the Named Destination array.
     * @return (array) Named Destination array.
     * @public
     * @author Nicola Asuni
     * @since 5.9.097 (2011-06-23)
     */
    public function getDestination()
    {
        return $this->tcpdf->getDestination();
    }

    /**
     * Adds a bookmark - alias for Bookmark().
     * @param $txt (string) Bookmark description.
     * @param $level (int) Bookmark level (minimum value is 0).
     * @param $y (float) Y position in user units of the bookmark on the selected
     * page (default = -1 = current position; 0 = page start;).
     * @param $page (int|string) Target page number (leave empty for current page).
     * If you prefix a page number with the * character, then this page will not be
     * changed when adding/deleting/moving pages.
     * @param $style (string) Font style: B = Bold, I = Italic, BI = Bold + Italic.
     * @param $color (array) RGB color array (values from 0 to 255).
     * @param $x (float) X position in user units of the bookmark on the selected
     * page (default = -1 = current position;).
     * @param $link (mixed) URL, or numerical link ID, or named destination (#
     * character followed by the destination name), or embedded file (* character
     * followed by the file name).
     * @public
     * @return $this
     */
    public function setBookmark($txt, $level = 0, $y = -1, $page = '', $style = '', $color = array(0, 0, 0), $x = -1, $link = '')
    {
        $this->tcpdf->setBookmark($txt, $level, $y, $page, $style, $color, $x, $link);
        return $this;
    }

    /**
     * Adds a bookmark.
     * @param $txt (string) Bookmark description.
     * @param $level (int) Bookmark level (minimum value is 0).
     * @param $y (float) Y position in user units of the bookmark on the selected
     * page (default = -1 = current position; 0 = page start;).
     * @param $page (int|string) Target page number (leave empty for current page).
     * If you prefix a page number with the * character, then this page will not be
     * changed when adding/deleting/moving pages.
     * @param $style (string) Font style: B = Bold, I = Italic, BI = Bold + Italic.
     * @param $color (array) RGB color array (values from 0 to 255).
     * @param $x (float) X position in user units of the bookmark on the selected
     * page (default = -1 = current position;).
     * @param $link (mixed) URL, or numerical link ID, or named destination (#
     * character followed by the destination name), or embedded file (* character
     * followed by the file name).
     * @public
     * @since 2.1.002 (2008-02-12)
     * @return $this
     */
    public function bookmark($txt, $level = 0, $y = -1, $page = '', $style = '', $color = array(0, 0, 0), $x = -1, $link = '')
    {
        $this->tcpdf->Bookmark($txt, $level, $y, $page, $style, $color, $x, $link);
        return $this;
    }

    /**
     * Adds a javascript
     * @param $script (string) Javascript code
     * @public
     * @author Johannes G\FCntert, Nicola Asuni
     * @since 2.1.002 (2008-02-12)
     * @return $this
     */
    public function includeJS($script)
    {
        $this->tcpdf->IncludeJS($script);
        return $this;
    }

    /**
     * Adds a javascript object and return object ID
     * @param $script (string) Javascript code
     * @param $onload (boolean) if true executes this object when opening the
     * document
     * @return int internal object ID
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     */
    public function addJavascriptObject($script, $onload = false)
    {
        return $this->tcpdf->addJavascriptObject($script, $onload);
    }

    /**
     * Set default properties for form fields.
     * @param $prop (array) javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-06)
     * @return $this
     */
    public function setFormDefaultProp($prop = array())
    {
        $this->tcpdf->setFormDefaultProp($prop);
        return $this;
    }

    /**
     * Return the default properties for form fields.
     * @return array $prop javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-06)
     */
    public function getFormDefaultProp()
    {
        return $this->tcpdf->getFormDefaultProp();
    }

    /**
     * Creates a text field
     * @param $name (string) field name
     * @param $w (float) Width of the rectangle
     * @param $h (float) Height of the rectangle
     * @param $prop (array) javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @param $opt (array) annotation parameters. Possible values are described on
     * official PDF32000_2008 reference.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $js (boolean) if true put the field using JavaScript (requires
     * Acrobat Writer to be rendered).
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     * @return $this
     */
    public function textField($name, $w, $h, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
    {
        $this->tcpdf->TextField($name, $w, $h, $prop, $opt, $x, $y, $js);
        return $this;
    }

    /**
     * Creates a RadioButton field.
     * @param $name (string) Field name.
     * @param $w (int) Width of the radio button.
     * @param $prop (array) Javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @param $opt (array) Annotation parameters. Possible values are described on
     * official PDF32000_2008 reference.
     * @param $onvalue (string) Value to be returned if selected.
     * @param $checked (boolean) Define the initial state.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $js (boolean) If true put the field using JavaScript (requires
     * Acrobat Writer to be rendered).
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     * @return $this
     */
    public function radioButton($name, $w, $prop = array(), $opt = array(), $onvalue = 'On', $checked = false, $x = '', $y = '', $js = false)
    {
        $this->tcpdf->RadioButton($name, $w, $prop, $opt, $onvalue, $checked, $x, $y, $js);
        return $this;
    }

    /**
     * Creates a List-box field
     * @param $name (string) field name
     * @param $w (int) width
     * @param $h (int) height
     * @param $values (array) array containing the list of values.
     * @param $prop (array) javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @param $opt (array) annotation parameters. Possible values are described on
     * official PDF32000_2008 reference.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $js (boolean) if true put the field using JavaScript (requires
     * Acrobat Writer to be rendered).
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     * @return $this
     */
    public function listBox($name, $w, $h, $values, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
    {
        $this->tcpdf->ListBox($name, $w, $h, $values, $prop, $opt, $x, $y, $js);
        return $this;
    }

    /**
     * Creates a Combo-box field
     * @param $name (string) field name
     * @param $w (int) width
     * @param $h (int) height
     * @param $values (array) array containing the list of values.
     * @param $prop (array) javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @param $opt (array) annotation parameters. Possible values are described on
     * official PDF32000_2008 reference.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $js (boolean) if true put the field using JavaScript (requires
     * Acrobat Writer to be rendered).
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     * @return $this
     */
    public function comboBox($name, $w, $h, $values, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
    {
        $this->tcpdf->ComboBox($name, $w, $h, $values, $prop, $opt, $x, $y, $js);
        return $this;
    }

    /**
     * Creates a CheckBox field
     * @param $name (string) field name
     * @param $w (int) width
     * @param $checked (boolean) define the initial state.
     * @param $prop (array) javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @param $opt (array) annotation parameters. Possible values are described on
     * official PDF32000_2008 reference.
     * @param $onvalue (string) value to be returned if selected.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $js (boolean) if true put the field using JavaScript (requires
     * Acrobat Writer to be rendered).
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     * @return $this
     */
    public function checkBox($name, $w, $checked = false, $prop = array(), $opt = array(), $onvalue = 'Yes', $x = '', $y = '', $js = false)
    {
        $this->tcpdf->CheckBox($name, $w, $checked, $prop, $opt, $onvalue, $x, $y, $js);
        return $this;
    }

    /**
     * Creates a button field
     * @param $name (string) field name
     * @param $w (int) width
     * @param $h (int) height
     * @param $caption (string) caption.
     * @param $action (mixed) action triggered by pressing the button. Use a string
     * to specify a javascript action. Use an array to specify a form action options as
     * on section 12.7.5 of PDF32000_2008.
     * @param $prop (array) javascript field properties. Possible values are
     * described on official Javascript for Acrobat API reference.
     * @param $opt (array) annotation parameters. Possible values are described on
     * official PDF32000_2008 reference.
     * @param $x (float) Abscissa of the upper-left corner of the rectangle
     * @param $y (float) Ordinate of the upper-left corner of the rectangle
     * @param $js (boolean) if true put the field using JavaScript (requires
     * Acrobat Writer to be rendered).
     * @public
     * @author Nicola Asuni
     * @since 4.8.000 (2009-09-07)
     * @return $this
     */
    public function button($name, $w, $h, $caption, $action, $prop = array(), $opt = array(), $x = '', $y = '', $js = false)
    {
        $this->tcpdf->Button($name, $w, $h, $caption, $action, $prop, $opt, $x, $y, $js);
        return $this;
    }

    /**
     * Set User's Rights for PDF Reader
     * WARNING: This is experimental and currently do not work.
     * Check the PDF Reference 8.7.1 Transform Methods,
     * Table 8.105 Entries in the UR transform parameters dictionary
     * @param $enable (boolean) if true enable user's rights on PDF reader
     * @param $document (string) Names specifying additional document-wide usage
     * rights for the document. The only defined value is "/FullSave", which permits a
     * user to save the document along with modified form and/or annotation data.
     * @param $annots (string) Names specifying additional annotation-related usage
     * rights for the document. Valid names in PDF 1.5 and later are
     * /Create/Delete/Modify/Copy/Import/Export, which permit the user to perform the
     * named operation on annotations.
     * @param $form (string) Names specifying additional form-field-related usage
     * rights for the document. Valid names are:
     * /Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate
     * @param $signature (string) Names specifying additional signature-related
     * usage rights for the document. The only defined value is /Modify, which permits
     * a user to apply a digital signature to an existing signature form field or clear
     * a signed signature form field.
     * @param $ef (string) Names specifying additional usage rights for named
     * embedded files in the document. Valid names are /Create/Delete/Modify/Import,
     * which permit the user to perform the named operation on named embedded files
     * 	 Names specifying additional embedded-files-related usage rights for the
     * document.
     * @param $formex (string) Names specifying additional form-field-related usage
     * rights. The only valid name is BarcodePlaintext, which permits text form field
     * data to be encoded as a plaintext two-dimensional barcode.
     * @public
     * @author Nicola Asuni
     * @since 2.9.000 (2008-03-26)
     * @return $this
     */
    public function setUserRights($enable = true, $document = '/FullSave', $annots = '/Create/Delete/Modify/Copy/Import/Export', $form = '/Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate', $signature = '/Modify', $ef = '/Create/Delete/Modify/Import', $formex = '')
    {
        $this->tcpdf->setUserRights($enable, $document, $annots, $form, $signature, $ef, $formex);
        return $this;
    }

    /**
     * Enable document signature (requires the OpenSSL Library).
     * The digital signature improve document authenticity and integrity and allows
     * o enable extra features on Acrobat Reader.
     * To create self-signed signature: openssl req -x509 -nodes -days 365000
     * -newkey rsa:1024 -keyout tcpdf.crt -out tcpdf.crt
     * To export crt to p12: openssl pkcs12 -export -in tcpdf.crt -out tcpdf.p12
     * To convert pfx certificate to pem: openssl pkcs12 -in tcpdf.pfx -out
     * tcpdf.crt -nodes
     * @param $signing_cert (mixed) signing certificate (string or filename
     * prefixed with 'file://')
     * @param $private_key (mixed) private key (string or filename prefixed with
     * 'file://')
     * @param $private_key_password (string) password
     * @param $extracerts (string) specifies the name of a file containing a bunch
     * of extra certificates to include in the signature which can for example be used
     * to help the recipient to verify the certificate that you used.
     * @param $cert_type (int) The access permissions granted for this document.
     * Valid values shall be: 1 = No changes to the document shall be permitted; any
     * change to the document shall invalidate the signature; 2 = Permitted changes
     * shall be filling in forms, instantiating page templates, and signing; other
     * changes shall invalidate the signature; 3 = Permitted changes shall be the same
     * as for 2, as well as annotation creation, deletion, and modification; other
     * changes shall invalidate the signature.
     * @param $info (array) array of option information: Name, Location, Reason,
     * ContactInfo.
     * @param $approval (string) Enable approval signature eg. for PDF incremental
     * update
     * @public
     * @author Nicola Asuni
     * @since 4.6.005 (2009-04-24)
     * @return $this
     */
    public function setSignature($signing_cert = '', $private_key = '', $private_key_password = '', $extracerts = '', $cert_type = 2, $info = array(), $approval = '')
    {
        $this->tcpdf->setSignature($signing_cert, $private_key, $private_key_password, $extracerts, $cert_type, $info, $approval);
        return $this;
    }

    /**
     * Set the digital signature appearance (a cliccable rectangle area to get
     * signature properties)
     * @param $x (float) Abscissa of the upper-left corner.
     * @param $y (float) Ordinate of the upper-left corner.
     * @param $w (float) Width of the signature area.
     * @param $h (float) Height of the signature area.
     * @param $page (int) option page number (if < 0 the current page is used).
     * @param $name (string) Name of the signature.
     * @public
     * @author Nicola Asuni
     * @since 5.3.011 (2010-06-17)
     * @return $this
     */
    public function setSignatureAppearance($x = 0, $y = 0, $w = 0, $h = 0, $page = -1, $name = '')
    {
        $this->tcpdf->setSignatureAppearance($x, $y, $w, $h, $page, $name);
        return $this;
    }

    /**
     * Add an empty digital signature appearance (a cliccable rectangle area to get
     * signature properties)
     * @param $x (float) Abscissa of the upper-left corner.
     * @param $y (float) Ordinate of the upper-left corner.
     * @param $w (float) Width of the signature area.
     * @param $h (float) Height of the signature area.
     * @param $page (int) option page number (if < 0 the current page is used).
     * @param $name (string) Name of the signature.
     * @public
     * @author Nicola Asuni
     * @since 5.9.101 (2011-07-06)
     * @return $this
     */
    public function addEmptySignatureAppearance($x = 0, $y = 0, $w = 0, $h = 0, $page = -1, $name = '')
    {
        $this->tcpdf->addEmptySignatureAppearance($x, $y, $w, $h, $page, $name);
        return $this;
    }

    /**
     * Enable document timestamping (requires the OpenSSL Library).
     * The trusted timestamping improve document security that means that no one
     * should be able to change the document once it has been recorded.
     * Use with digital signature only!
     * @param $tsa_host (string) Time Stamping Authority (TSA) server (prefixed
     * with 'https://')
     * @param $tsa_username (string) Specifies the username for TSA authorization
     * (optional) OR specifies the TSA authorization PEM file (see: example_66.php,
     * optional)
     * @param $tsa_password (string) Specifies the password for TSA authorization
     * (optional)
     * @param $tsa_cert (string) Specifies the location of TSA certificate for
     * authorization (optional for cURL)
     * @public
     * @author Richard Stockinger
     * @since 6.0.090 (2014-06-16)
     * @return $this
     */
    public function setTimeStamp($tsa_host = '', $tsa_username = '', $tsa_password = '', $tsa_cert = '')
    {
        $this->tcpdf->setTimeStamp($tsa_host, $tsa_username, $tsa_password, $tsa_cert);
        return $this;
    }

    /**
     * Create a new page group.
     * NOTE: call this function before calling AddPage()
     * @param $page (int) starting group page (leave empty for next page).
     * @public
     * @since 3.0.000 (2008-03-27)
     * @return $this
     */
    public function startPageGroup($page = '')
    {
        $this->tcpdf->startPageGroup($page);
        return $this;
    }

    /**
     * Set the starting page number.
     * @param $num (int) Starting page number.
     * @since 5.9.093 (2011-06-16)
     * @public
     * @return $this
     */
    public function setStartingPageNumber($num = 1)
    {
        $this->tcpdf->setStartingPageNumber($num);
        return $this;
    }

    /**
     * Returns the string alias used right align page numbers.
     * If the current font is unicode type, the returned string wil contain an
     * additional open curly brace.
     * @return string
     * @since 5.9.099 (2011-06-27)
     * @public
     */
    public function getAliasRightShift()
    {
        return $this->tcpdf->getAliasRightShift();
    }

    /**
     * Returns the string alias used for the total number of pages.
     * If the current font is unicode type, the returned string is surrounded by
     * additional curly braces.
     * This alias will be replaced by the total number of pages in the document.
     * @return string
     * @since 4.0.018 (2008-08-08)
     * @public
     */
    public function getAliasNbPages()
    {
        return $this->tcpdf->getAliasNbPages();
    }

    /**
     * Returns the string alias used for the page number.
     * If the current font is unicode type, the returned string is surrounded by
     * additional curly braces.
     * This alias will be replaced by the page number.
     * @return string
     * @since 4.5.000 (2009-01-02)
     * @public
     */
    public function getAliasNumPage()
    {
        return $this->tcpdf->getAliasNumPage();
    }

    /**
     * Return the alias for the total number of pages in the current page group.
     * If the current font is unicode type, the returned string is surrounded by
     * additional curly braces.
     * This alias will be replaced by the total number of pages in this group.
     * @return alias of the current page group
     * @public
     * @since 3.0.000 (2008-03-27)
     */
    public function getPageGroupAlias()
    {
        return $this->tcpdf->getPageGroupAlias();
    }

    /**
     * Return the alias for the page number on the current page group.
     * If the current font is unicode type, the returned string is surrounded by
     * additional curly braces.
     * This alias will be replaced by the page number (relative to the belonging
     * group).
     * @return alias of the current page group
     * @public
     * @since 4.5.000 (2009-01-02)
     */
    public function getPageNumGroupAlias()
    {
        return $this->tcpdf->getPageNumGroupAlias();
    }

    /**
     * Return the current page in the group.
     * @return current page in the group
     * @public
     * @since 3.0.000 (2008-03-27)
     */
    public function getGroupPageNo()
    {
        return $this->tcpdf->getGroupPageNo();
    }

    /**
     * Returns the current group page number formatted as a string.
     * @public
     * @since 4.3.003 (2008-11-18)
     * @see PaneNo(), formatPageNumber()
     */
    public function getGroupPageNoFormatted()
    {
        return $this->tcpdf->getGroupPageNoFormatted();
    }

    /**
     * Returns the current page number formatted as a string.
     * @public
     * @since 4.2.005 (2008-11-06)
     * @see PaneNo(), formatPageNumber()
     * @return $this
     */
    public function pageNoFormatted()
    {
        $this->tcpdf->PageNoFormatted();
        return $this;
    }

    /**
     * Start a new pdf layer.
     * @param $name (string) Layer name (only a-z letters and numbers). Leave empty
     * for automatic name.
     * @param $print (boolean|null) Set to TRUE to print this layer, FALSE to not
     * print and NULL to not set this option
     * @param $view (boolean) Set to true to view this layer.
     * @param $lock (boolean) If true lock the layer
     * @public
     * @since 5.9.102 (2011-07-13)
     * @return $this
     */
    public function startLayer($name = '', $print = true, $view = true, $lock = true)
    {
        $this->tcpdf->startLayer($name, $print, $view, $lock);
        return $this;
    }

    /**
     * End the current PDF layer.
     * @public
     * @since 5.9.102 (2011-07-13)
     * @return $this
     */
    public function endLayer()
    {
        $this->tcpdf->endLayer();
        return $this;
    }

    /**
     * Set the visibility of the successive elements.
     * This can be useful, for instance, to put a background
     * image or color that will show on screen but won't print.
     * @param $v (string) visibility mode. Legal values are: all, print, screen or
     * view.
     * @public
     * @since 3.0.000 (2008-03-27)
     * @return $this
     */
    public function setVisibility($v)
    {
        $this->tcpdf->setVisibility($v);
        return $this;
   }

    /**
     * Set overprint mode for stroking (OP) and non-stroking (op) painting
     * operations.
     * (Check the "Entries in a Graphics State Parameter Dictionary" on PDF
     * @param $stroking (boolean) If true apply overprint for stroking operations.
     * @param $nonstroking (boolean) If true apply overprint for painting
     * operations other than stroking.
     * @param $mode (integer) Overprint mode: (0 = each source colour component
     * value replaces the value previously painted for the corresponding device
     * colorant; 1 = a tint value of 0.0 for a source colour component shall leave the
     * corresponding component of the previously painted colour unchanged).
     * @public
     * @since 5.9.152 (2012-03-23)
     * @return $this
     */
    public function setOverprint($stroking = true, $nonstroking = '', $mode = 0)
    {
        $this->tcpdf->setOverprint($stroking, $nonstroking, $mode);
        return $this;
    }

    /**
     * Get the overprint mode array (OP, op, OPM).
     * (Check the "Entries in a Graphics State Parameter Dictionary" on PDF
     * @return array.
     * @public
     * @since 5.9.152 (2012-03-23)
     */
    public function getOverprint()
    {
        return $this->tcpdf->getOverprint();
    }

    /**
     * Set alpha for stroking (CA) and non-stroking (ca) operations.
     * @param $stroking (float) Alpha value for stroking operations: real value
     * from 0 (transparent) to 1 (opaque).
     * @param $bm (string) blend mode, one of the following: Normal, Multiply,
     * Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn, HardLight, SoftLight,
     * Difference, Exclusion, Hue, Saturation, Color, Luminosity
     * @param $nonstroking (float) Alpha value for non-stroking operations: real
     * value from 0 (transparent) to 1 (opaque).
     * @param $ais (boolean)
     * @public
     * @since 3.0.000 (2008-03-27)
     * @return $this
     */
    public function setAlpha($stroking = 1, $bm = 'Normal', $nonstroking = '', $ais = false)
    {
        $this->tcpdf->setAlpha($stroking, $bm, $nonstroking, $ais);
        return $this;
    }

    /**
     * Get the alpha mode array (CA, ca, BM, AIS).
     * (Check the "Entries in a Graphics State Parameter Dictionary" on PDF
     * @return array.
     * @public
     * @since 5.9.152 (2012-03-23)
     */
    public function getAlpha()
    {
        return $this->tcpdf->getAlpha();
    }

    /**
     * Set the default JPEG compression quality (1-100)
     * @param $quality (int) JPEG quality, integer between 1 and 100
     * @public
     * @since 3.0.000 (2008-03-27)
     * @return $this
     */
    public function setJPEGQuality($quality)
    {
        $this->tcpdf->setJPEGQuality($quality);
        return $this;
    }

    /**
     * Set the default number of columns in a row for HTML tables.
     * @param $cols (int) number of columns
     * @public
     * @since 3.0.014 (2008-06-04)
     * @return $this
     */
    public function setDefaultTableColumns($cols = 4)
    {
        $this->tcpdf->setDefaultTableColumns($cols);
        return $this;
    }

    /**
     * Set the height of the cell (line height) respect the font height.
     * @param $h (int) cell proportion respect font height (typical value = 1.25).
     * @public
     * @since 3.0.014 (2008-06-04)
     * @return $this
     */
    public function setCellHeightRatio($h)
    {
        $this->tcpdf->setCellHeightRatio($h);
        return $this;
    }

    /**
     * return the height of cell repect font height.
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getCellHeightRatio()
    {
        return $this->tcpdf->getCellHeightRatio();
    }

    /**
     * Set the PDF version (check PDF reference for valid values).
     * @param $version (string) PDF document version.
     * @public
     * @since 3.1.000 (2008-06-09)
     * @return $this
     */
    public function setPDFVersion($version = '1.7')
    {
        $this->tcpdf->setPDFVersion($version);
        return $this;
    }

    /**
     * Set the viewer preferences dictionary controlling the way the document is to
     * be presented on the screen or in print.
     * (see Section 8.1 of PDF reference, "Viewer Preferences").
     * <ul><li>HideToolbar boolean (Optional) A flag specifying whether to hide the
     * viewer application's tool bars when the document is active. Default value:
     * false.</li><li>HideMenubar boolean (Optional) A flag specifying whether to hide
     * the viewer application's menu bar when the document is active. Default value:
     * false.</li><li>HideWindowUI boolean (Optional) A flag specifying whether to hide
     * user interface elements in the document's window (such as scroll bars and
     * navigation controls), leaving only the document's contents displayed. Default
     * value: false.</li><li>FitWindow boolean (Optional) A flag specifying whether to
     * resize the document's window to fit the size of the first displayed page.
     * Default value: false.</li><li>CenterWindow boolean (Optional) A flag specifying
     * whether to position the document's window in the center of the screen. Default
     * value: false.</li><li>DisplayDocTitle boolean (Optional; PDF 1.4) A flag
     * specifying whether the window's title bar should display the document title
     * taken from the Title entry of the document information dictionary (see Section
     * 10.2.1, "Document Information Dictionary"). If false, the title bar should
     * instead display the name of the PDF file containing the document. Default value:
     * false.</li><li>NonFullScreenPageMode name (Optional) The document's page mode,
     * specifying how to display the document on exiting full-screen
     * mode:<ul><li>UseNone Neither document outline nor thumbnail images
     * visible</li><li>UseOutlines Document outline visible</li><li>UseThumbs Thumbnail
     * images visible</li><li>UseOC Optional content group panel visible</li></ul>This
     * entry is meaningful only if the value of the PageMode entry in the catalog
     * dictionary (see Section 3.6.1, "Document Catalog") is FullScreen; it is ignored
     * otherwise. Default value: UseNone.</li><li>ViewArea name (Optional; PDF 1.4) The
     * name of the page boundary representing the area of a page to be displayed when
     * viewing the document on the screen. Valid values are (see Section 10.10.1, "Page
     * Boundaries").:<ul><li>MediaBox</li><li>CropBox
     * (default)</li><li>BleedBox</li><li>TrimBox</li><li>ArtBox</li></ul></li><li>ViewClip
     * name (Optional; PDF 1.4) The name of the page boundary to which the contents of
     * a page are to be clipped when viewing the document on the screen. Valid values
     * are (see Section 10.10.1, "Page Boundaries").:<ul><li>MediaBox</li><li>CropBox
     * (default)</li><li>BleedBox</li><li>TrimBox</li><li>ArtBox</li></ul></li><li>PrintArea
     * name (Optional; PDF 1.4) The name of the page boundary representing the area of
     * a page to be rendered when printing the document. Valid values are (see Section
     * 10.10.1, "Page Boundaries").:<ul><li>MediaBox</li><li>CropBox
     * (default)</li><li>BleedBox</li><li>TrimBox</li><li>ArtBox</li></ul></li><li>PrintClip
     * name (Optional; PDF 1.4) The name of the page boundary to which the contents of
     * a page are to be clipped when printing the document. Valid values are (see
     * Section 10.10.1, "Page Boundaries").:<ul><li>MediaBox</li><li>CropBox
     * (default)</li><li>BleedBox</li><li>TrimBox</li><li>ArtBox</li></ul></li><li>PrintScaling
     * name (Optional; PDF 1.6) The page scaling option to be selected when a print
     * dialog is displayed for this document. Valid values are: <ul><li>None, which
     * indicates that the print dialog should reflect no page
     * scaling</li><li>AppDefault (default), which indicates that applications should
     * use the current print scaling</li></ul></li><li>Duplex name (Optional; PDF 1.7)
     * The paper handling option to use when printing the file from the print dialog.
     * The following values are valid:<ul><li>Simplex - Print
     * single-sided</li><li>DuplexFlipShortEdge - Duplex and flip on the short edge of
     * the sheet</li><li>DuplexFlipLongEdge - Duplex and flip on the long edge of the
     * sheet</li></ul>Default value: none</li><li>PickTrayByPDFSize boolean (Optional;
     * PDF 1.7) A flag specifying whether the PDF page size is used to select the input
     * paper tray. This setting influences only the preset values used to populate the
     * print dialog presented by a PDF viewer application. If PickTrayByPDFSize is
     * true, the check box in the print dialog associated with input paper tray is
     * checked. Note: This setting has no effect on Mac OS systems, which do not
     * provide the ability to pick the input tray by size.</li><li>PrintPageRange array
     * (Optional; PDF 1.7) The page numbers used to initialize the print dialog box
     * when the file is printed. The first page of the PDF file is denoted by 1. Each
     * pair consists of the first and last pages in the sub-range. An odd number of
     * integers causes this entry to be ignored. Negative numbers cause the entire
     * array to be ignored. Default value: as defined by PDF viewer
     * application</li><li>NumCopies integer (Optional; PDF 1.7) The number of copies
     * to be printed when the print dialog is opened for this file. Supported values
     * are the integers 2 through 5. Values outside this range are ignored. Default
     * value: as defined by PDF viewer application, but typically 1</li></ul>
     * @param $preferences (array) array of options.
     * @author Nicola Asuni
     * @public
     * @since 3.1.000 (2008-06-09)
     * @return $this
     */
    public function setViewerPreferences($preferences)
    {
        $this->tcpdf->setViewerPreferences($preferences);
        return $this;
    }

    /**
     * Paints color transition registration bars
     * @param $x (float) abscissa of the top left corner of the rectangle.
     * @param $y (float) ordinate of the top left corner of the rectangle.
     * @param $w (float) width of the rectangle.
     * @param $h (float) height of the rectangle.
     * @param $transition (boolean) if true prints tcolor transitions to white.
     * @param $vertical (boolean) if true prints bar vertically.
     * @param $colors (string) colors to print separated by comma. Valid values
     * are: A,W,R,G,B,C,M,Y,K,RGB,CMYK,ALL,ALLSPOT,<SPOT_COLOR_NAME>. Where: A =
     * grayscale black, W = grayscale white, R = RGB red, G RGB green, B RGB blue, C =
     * CMYK cyan, M = CMYK magenta, Y = CMYK yellow, K = CMYK key/black, RGB = RGB
     * registration color, CMYK = CMYK registration color, ALL = Spot registration
     * color, ALLSPOT = print all defined spot colors, <SPOT_COLOR_NAME> = name of the
     * spot color to print.
     * @author Nicola Asuni
     * @since 4.9.000 (2010-03-26)
     * @public
     * @return $this
     */
    public function colorRegistrationBar($x, $y, $w, $h, $transition = true, $vertical = false, $colors = 'A,R,G,B,C,M,Y,K')
    {
        $this->tcpdf->colorRegistrationBar($x, $y, $w, $h, $transition, $vertical, $colors);
        return $this;
    }

    /**
     * Paints crop marks.
     * @param $x (float) abscissa of the crop mark center.
     * @param $y (float) ordinate of the crop mark center.
     * @param $w (float) width of the crop mark.
     * @param $h (float) height of the crop mark.
     * @param $type (string) type of crop mark, one symbol per type separated by
     * comma: T = TOP, F = BOTTOM, L = LEFT, R = RIGHT, TL = A = TOP-LEFT, TR = B =
     * @param $color (array) crop mark color (default spot registration color).
     * @author Nicola Asuni
     * @since 4.9.000 (2010-03-26)
     * @public
     * @return $this
     */
    public function cropMark($x, $y, $w, $h, $type = 'T,R,B,L', $color = array(100, 100, 100, 100, 'All'))
    {
        $this->tcpdf->cropMark($x, $y, $w, $h, $type, $color);
        return $this;
    }

    /**
     * Paints a registration mark
     * @param $x (float) abscissa of the registration mark center.
     * @param $y (float) ordinate of the registration mark center.
     * @param $r (float) radius of the crop mark.
     * @param $double (boolean) if true print two concentric crop marks.
     * @param $cola (array) crop mark color (default spot registration color
     * 'All').
     * @param $colb (array) second crop mark color (default spot registration color
     * 'None').
     * @author Nicola Asuni
     * @since 4.9.000 (2010-03-26)
     * @public
     * @return $this
     */
    public function registrationMark($x, $y, $r, $double = false, $cola = array(100, 100, 100, 100, 'All'), $colb = array(0, 0, 0, 0, 'None'))
    {
        $this->tcpdf->registrationMark($x, $y, $r, $double, $cola, $colb);
        return $this;
    }

    /**
     * Paints a CMYK registration mark
     * @param $x (float) abscissa of the registration mark center.
     * @param $y (float) ordinate of the registration mark center.
     * @param $r (float) radius of the crop mark.
     * @author Nicola Asuni
     * @since 6.0.038 (2013-09-30)
     * @public
     * @return $this
     */
    public function registrationMarkCMYK($x, $y, $r)
    {
        $this->tcpdf->registrationMarkCMYK($x, $y, $r);
        return $this;
    }

    /**
     * Paints a linear colour gradient.
     * @param $x (float) abscissa of the top left corner of the rectangle.
     * @param $y (float) ordinate of the top left corner of the rectangle.
     * @param $w (float) width of the rectangle.
     * @param $h (float) height of the rectangle.
     * @param $col1 (array) first color (Grayscale, RGB or CMYK components).
     * @param $col2 (array) second color (Grayscale, RGB or CMYK components).
     * @param $coords (array) array of the form (x1, y1, x2, y2) which defines the
     * gradient vector (see linear_gradient_coords.jpg). The default value is from left
     * to right (x1=0, y1=0, x2=1, y2=0).
     * @author Andreas W\FCrmser, Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function linearGradient($x, $y, $w, $h, $col1 = array(), $col2 = array(), $coords = array(0, 0, 1, 0))
    {
        $this->tcpdf->LinearGradient($x, $y, $w, $h, $col1, $col2, $coords);
        return $this;
    }

    /**
     * Paints a radial colour gradient.
     * @param $x (float) abscissa of the top left corner of the rectangle.
     * @param $y (float) ordinate of the top left corner of the rectangle.
     * @param $w (float) width of the rectangle.
     * @param $h (float) height of the rectangle.
     * @param $col1 (array) first color (Grayscale, RGB or CMYK components).
     * @param $col2 (array) second color (Grayscale, RGB or CMYK components).
     * @param $coords (array) array of the form (fx, fy, cx, cy, r) where (fx, fy)
     * is the starting point of the gradient with color1, (cx, cy) is the center of the
     * circle with color2, and r is the radius of the circle (see
     * radial_gradient_coords.jpg). (fx, fy) should be inside the circle, otherwise
     * some areas will not be defined.
     * @author Andreas W\FCrmser, Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function radialGradient($x, $y, $w, $h, $col1 = array(), $col2 = array(), $coords = array(0.5, 0.5, 0.5, 0.5, 1))
    {
        $this->tcpdf->RadialGradient($x, $y, $w, $h, $col1, $col2, $coords);
        return $this;
    }

    /**
     * Paints a coons patch mesh.
     * @param $x (float) abscissa of the top left corner of the rectangle.
     * @param $y (float) ordinate of the top left corner of the rectangle.
     * @param $w (float) width of the rectangle.
     * @param $h (float) height of the rectangle.
     * @param $col1 (array) first color (lower left corner) (RGB components).
     * @param $col2 (array) second color (lower right corner) (RGB components).
     * @param $col3 (array) third color (upper right corner) (RGB components).
     * @param $col4 (array) fourth color (upper left corner) (RGB components).
     * @param $coords (array) <ul><li>for one patch mesh: array(float x1, float y1,
     * .... float x12, float y12): 12 pairs of coordinates (normally from 0 to 1) which
     * specify the Bezier control points that define the patch. First pair is the lower
     * left edge point, next is its right control point (control point 2). Then the
     * other points are defined in the order: control point 1, edge point, control
     * point 2 going counter-clockwise around the patch. Last (x12, y12) is the first
     * edge point's left control point (control point 1).</li><li>for two or more patch
     * meshes: array[number of patches]: arrays with the following keys for each patch:
     * f: where to put that patch (0 = first patch, 1, 2, 3 = right, top and left of
     * precedent patch - I didn't figure this out completely - just try and error ;-)
     * points: 12 pairs of coordinates of the Bezier control points as above for the
     * first patch, 8 pairs of coordinates for the following patches, ignoring the
     * coordinates already defined by the precedent patch (I also didn't figure out the
     * order of these - also: try and see what's happening) colors: must be 4 colors
     * for the first patch, 2 colors for the following patches</li></ul>
     * @param $coords_min (array) minimum value used by the coordinates. If a
     * coordinate's value is smaller than this it will be cut to coords_min. default: 0
     * @param $coords_max (array) maximum value used by the coordinates. If a
     * coordinate's value is greater than this it will be cut to coords_max. default: 1
     * @param $antialias (boolean) A flag indicating whether to filter the shading
     * function to prevent aliasing artifacts.
     * @author Andreas W\FCrmser, Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function coonsPatchMesh($x, $y, $w, $h, $col1 = array(), $col2 = array(), $col3 = array(), $col4 = array(), $coords = array(0, 0, 0.33, 0, 0.67, 0, 1, 0, 1, 0.33, 1, 0.67, 1, 1, 0.67, 1, 0.33, 1, 0, 1, 0, 0.67, 0, 0.33), $coords_min = 0, $coords_max = 1, $antialias = false)
    {
        $this->tcpdf->CoonsPatchMesh($x, $y, $w, $h, $col1, $col2, $col3, $col4, $coords, $coords_min, $coords_max, $antialias);
        return $this;
    }

    /**
     * Output gradient.
     * @param $type (int) type of gradient (1 Function-based shading; 2 Axial
     * shading; 3 Radial shading; 4 Free-form Gouraud-shaded triangle mesh; 5
     * Lattice-form Gouraud-shaded triangle mesh; 6 Coons patch mesh; 7 Tensor-product
     * patch mesh). (Not all types are currently supported)
     * @param $coords (array) array of coordinates.
     * @param $stops (array) array gradient color components: color = array of
     * GRAY, RGB or CMYK color components; offset = (0 to 1) represents a location
     * along the gradient vector; exponent = exponent of the exponential interpolation
     * function (default = 1).
     * @param $background (array) An array of colour components appropriate to the
     * colour space, specifying a single background colour value.
     * @param $antialias (boolean) A flag indicating whether to filter the shading
     * function to prevent aliasing artifacts.
     * @author Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function gradient($type, $coords, $stops, $background = array(), $antialias = false)
    {
        $this->tcpdf->Gradient($type, $coords, $stops, $background, $antialias);
        return $this;
    }

    /**
     * Output gradient shaders.
     * @author Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @protected
     * @return $this
     */
    public function _putshaders()
    {
        $this->tcpdf->_putshaders();
        return $this;
    }

    /**
     * Draw the sector of a circle.
     * It can be used for instance to render pie charts.
     * @param $xc (float) abscissa of the center.
     * @param $yc (float) ordinate of the center.
     * @param $r (float) radius.
     * @param $a (float) start angle (in degrees).
     * @param $b (float) end angle (in degrees).
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $cw: (float) indicates whether to go clockwise (default: true).
     * @param $o: (float) origin of angles (0 for 3 o'clock, 90 for noon, 180 for 9
     * o'clock, 270 for 6 o'clock). Default: 90.
     * @author Maxime Delorme, Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function pieSector($xc, $yc, $r, $a, $b, $style = 'FD', $cw = true, $o = 90)
    {
        $this->tcpdf->PieSector($xc, $yc, $r, $a, $b, $style, $cw, $o);
        return $this;
    }

    /**
     * Draw the sector of an ellipse.
     * It can be used for instance to render pie charts.
     * @param $xc (float) abscissa of the center.
     * @param $yc (float) ordinate of the center.
     * @param $rx (float) the x-axis radius.
     * @param $ry (float) the y-axis radius.
     * @param $a (float) start angle (in degrees).
     * @param $b (float) end angle (in degrees).
     * @param $style (string) Style of rendering. See the getPathPaintOperator()
     * function for more information.
     * @param $cw: (float) indicates whether to go clockwise.
     * @param $o: (float) origin of angles (0 for 3 o'clock, 90 for noon, 180 for 9
     * o'clock, 270 for 6 o'clock).
     * @param $nc (integer) Number of curves used to draw a 90 degrees portion of
     * arc.
     * @author Maxime Delorme, Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function pieSectorXY($xc, $yc, $rx, $ry, $a, $b, $style = 'FD', $cw = false, $o = 0, $nc = 2)
    {
        $this->tcpdf->PieSectorXY($xc, $yc, $rx, $ry, $a, $b, $style, $cw, $o, $nc);
        return $this;
    }

    /**
     * Embed vector-based Adobe Illustrator (AI) or AI-compatible EPS files.
     * NOTE: EPS is not yet fully implemented, use the setRasterizeVectorImages()
     * method to enable/disable rasterization of vector images using ImageMagick
     * library.
     * Only vector drawing is supported, not text or bitmap.
     * Although the script was successfully tested with various AI format versions,
     * best results are probably achieved with files that were exported in the AI3
     * format (tested with Illustrator CS2, Freehand MX and Photoshop CS2).
     * @param $file (string) Name of the file containing the image or a '@'
     * character followed by the EPS/AI data string.
     * @param $x (float) Abscissa of the upper-left corner.
     * @param $y (float) Ordinate of the upper-left corner.
     * @param $w (float) Width of the image in the page. If not specified or equal
     * to zero, it is automatically calculated.
     * @param $h (float) Height of the image in the page. If not specified or equal
     * to zero, it is automatically calculated.
     * @param $link (mixed) URL or identifier returned by AddLink().
     * @param $useBoundingBox (boolean) specifies whether to position the bounding
     * box (true) or the complete canvas (false) at location (x,y). Default value is
     * true.
     * @param $align (string) Indicates the alignment of the pointer next to image
     * insertion relative to image height. The value can be:<ul><li>T: top-right for
     * LTR or top-left for RTL</li><li>M: middle-right for LTR or middle-left for
     * RTL</li><li>B: bottom-right for LTR or bottom-left for RTL</li><li>N: next
     * line</li></ul>
     * @param $palign (string) Allows to center or align the image on the current
     * line. Possible values are:<ul><li>L : left align</li><li>C : center</li><li>R :
     * right align</li><li>'' : empty string : left for LTR or right for RTL</li></ul>
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $fitonpage (boolean) if true the image is resized to not exceed page
     * dimensions.
     * @param $fixoutvals (boolean) if true remove values outside the bounding box.
     * @author Valentin Schmidt, Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function imageEps($file, $x = '', $y = '', $w = 0, $h = 0, $link = '', $useBoundingBox = true, $align = '', $palign = '', $border = 0, $fitonpage = false, $fixoutvals = false)
    {
        $this->tcpdf->ImageEps($file, $x, $y, $w, $h, $link, $useBoundingBox, $align, $palign, $border, $fitonpage, $fixoutvals);
        return $this;
    }

    /**
     * Set document barcode.
     * @param $bc (string) barcode
     * @public
     * @return $this
     */
    public function setBarcode($bc = '')
    {
        $this->tcpdf->setBarcode($bc);
        return $this;
    }

    /**
     * Get current barcode.
     * @return string
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getBarcode()
    {
        return $this->tcpdf->getBarcode();
    }

    /**
     * Print a Linear Barcode.
     * @param $code (string) code to print
     * @param $type (string) type of barcode (see tcpdf_barcodes_1d.php for
     * supported formats).
     * @param $x (int) x position in user units (empty string = current x position)
     * @param $y (int) y position in user units (empty string = current y position)
     * @param $w (int) width in user units (empty string = remaining page width)
     * @param $h (int) height in user units (empty string = remaining page height)
     * @param $xres (float) width of the smallest bar in user units (empty string =
     * default value = 0.4mm)
     * @param $style (array) array of options:<ul>
     * <li>boolean $style['border'] if true prints a border</li>
     * <li>int $style['padding'] padding to leave around the barcode in user units
     * (set to 'auto' for automatic padding)</li>
     * <li>int $style['hpadding'] horizontal padding in user units (set to 'auto'
     * for automatic padding)</li>
     * <li>int $style['vpadding'] vertical padding in user units (set to 'auto' for
     * automatic padding)</li>
     * <li>array $style['fgcolor'] color array for bars and text</li>
     * <li>mixed $style['bgcolor'] color array for background (set to false for
     * transparent)</li>
     * <li>boolean $style['text'] if true prints text below the barcode</li>
     * <li>string $style['label'] override default label</li>
     * <li>string $style['font'] font name for text</li><li>int $style['fontsize']
     * font size for text</li>
     * <li>int $style['stretchtext']: 0 = disabled; 1 = horizontal scaling only if
     * necessary; 2 = forced horizontal scaling; 3 = character spacing only if
     * necessary; 4 = forced character spacing.</li>
     * <li>string $style['position'] horizontal position of the containing barcode
     * cell on the page: L = left margin; C = center; R = right margin.</li>
     * <li>string $style['align'] horizontal position of the barcode on the
     * containing rectangle: L = left; C = center; R = right.</li>
     * <li>string $style['stretch'] if true stretch the barcode to best fit the
     * available width, otherwise uses $xres resolution for a single bar.</li>
     * <li>string $style['fitwidth'] if true reduce the width to fit the barcode
     * width + padding. When this option is enabled the 'stretch' option is
     * automatically disabled.</li>
     * <li>string $style['cellfitalign'] this option works only when 'fitwidth' is
     * true and 'position' is unset or empty. Set the horizontal position of the
     * containing barcode cell inside the specified rectangle: L = left; C = center; R
     * = right.</li></ul>
     * @param $align (string) Indicates the alignment of the pointer next to
     * barcode insertion relative to barcode height. The value can be:<ul><li>T:
     * top-right for LTR or top-left for RTL</li><li>M: middle-right for LTR or
     * middle-left for RTL</li><li>B: bottom-right for LTR or bottom-left for
     * RTL</li><li>N: next line</li></ul>
     * @author Nicola Asuni
     * @since 3.1.000 (2008-06-09)
     * @public
     * @return $this
     */
    public function write1DBarcode($code, $type, $x = '', $y = '', $w = '', $h = '', $xres = '', $style = '', $align = '')
    {
        $this->tcpdf->write1DBarcode($code, $type, $x, $y, $w, $h, $xres, $style, $align);
        return $this;
    }

    /**
     * Print 2D Barcode.
     * @param $code (string) code to print
     * @param $type (string) type of barcode (see tcpdf_barcodes_2d.php for
     * supported formats).
     * @param $x (int) x position in user units
     * @param $y (int) y position in user units
     * @param $w (int) width in user units
     * @param $h (int) height in user units
     * @param $style (array) array of options:<ul>
     * <li>boolean $style['border'] if true prints a border around the barcode</li>
     * <li>int $style['padding'] padding to leave around the barcode in barcode
     * units (set to 'auto' for automatic padding)</li>
     * <li>int $style['hpadding'] horizontal padding in barcode units (set to
     * 'auto' for automatic padding)</li>
     * <li>int $style['vpadding'] vertical padding in barcode units (set to 'auto'
     * for automatic padding)</li>
     * <li>int $style['module_width'] width of a single module in points</li>
     * <li>int $style['module_height'] height of a single module in points</li>
     * <li>array $style['fgcolor'] color array for bars and text</li>
     * <li>mixed $style['bgcolor'] color array for background or false for
     * transparent</li>
     * <li>string $style['position'] barcode position on the page: L = left margin;
     * C = center; R = right margin; S = stretch</li><li>$style['module_width'] width
     * of a single module in points</li>
     * <li>$style['module_height'] height of a single module in points</li></ul>
     * @param $align (string) Indicates the alignment of the pointer next to
     * barcode insertion relative to barcode height. The value can be:<ul><li>T:
     * top-right for LTR or top-left for RTL</li><li>M: middle-right for LTR or
     * middle-left for RTL</li><li>B: bottom-right for LTR or bottom-left for
     * RTL</li><li>N: next line</li></ul>
     * @param $distort (boolean) if true distort the barcode to fit width and
     * height, otherwise preserve aspect ratio
     * @author Nicola Asuni
     * @since 4.5.037 (2009-04-07)
     * @public
     * @return $this
     */
    public function write2DBarcode($code, $type, $x = '', $y = '', $w = '', $h = '', $style = '', $align = '', $distort = false)
    {
        $this->tcpdf->write2DBarcode($code, $type, $x, $y, $w, $h, $style, $align, $distort);
        return $this;
    }

    /**
     * Returns an array containing current margins:
     * <ul>
     * 			<li>$ret['left'] = left margin</li>
     * 			<li>$ret['right'] = right margin</li>
     * 			<li>$ret['top'] = top margin</li>
     * 			<li>$ret['bottom'] = bottom margin</li>
     * 			<li>$ret['header'] = header margin</li>
     * 			<li>$ret['footer'] = footer margin</li>
     * 			<li>$ret['cell'] = cell padding array</li>
     * 			<li>$ret['padding_left'] = cell left padding</li>
     * 			<li>$ret['padding_top'] = cell top padding</li>
     * 			<li>$ret['padding_right'] = cell right padding</li>
     * 			<li>$ret['padding_bottom'] = cell bottom padding</li>
     * </ul>
     * @return array containing all margins measures
     * @public
     * @since 3.2.000 (2008-06-23)
     */
    public function getMargins()
    {
        return $this->tcpdf->getMargins();
    }

    /**
     * Returns an array containing original margins:
     * <ul>
     * 			<li>$ret['left'] = left margin</li>
     * 			<li>$ret['right'] = right margin</li>
     * </ul>
     * @return array containing all margins measures
     * @public
     * @since 4.0.012 (2008-07-24)
     */
    public function getOriginalMargins()
    {
        return $this->tcpdf->getOriginalMargins();
    }

    /**
     * Returns the current font size.
     * @return current font size
     * @public
     * @since 3.2.000 (2008-06-23)
     */
    public function getFontSize()
    {
        return $this->tcpdf->getFontSize();
    }

    /**
     * Returns the current font size in points unit.
     * @return current font size in points unit
     * @public
     * @since 3.2.000 (2008-06-23)
     */
    public function getFontSizePt()
    {
        return $this->tcpdf->getFontSizePt();
    }

    /**
     * Returns the current font family name.
     * @return string current font family name
     * @public
     * @since 4.3.008 (2008-12-05)
     */
    public function getFontFamily()
    {
        return $this->tcpdf->getFontFamily();
    }

    /**
     * Returns the current font style.
     * @return string current font style
     * @public
     * @since 4.3.008 (2008-12-05)
     */
    public function getFontStyle()
    {
        return $this->tcpdf->getFontStyle();
    }

    /**
     * Cleanup HTML code (requires HTML Tidy library).
     * @param $html (string) htmlcode to fix
     * @param $default_css (string) CSS commands to add
     * @param $tagvs (array) parameters for setHtmlVSpace method
     * @param $tidy_options (array) options for tidy_parse_string function
     * @return string XHTML code cleaned up
     * @author Nicola Asuni
     * @public
     * @since 5.9.017 (2010-11-16)
     * @see setHtmlVSpace()
     */
    public function fixHTMLCode($html, $default_css = '', $tagvs = '', $tidy_options = '')
    {
        return $this->tcpdf->fixHTMLCode($html, $default_css, $tagvs, $tidy_options);
    }

    /**
     * Get the internal Cell padding from CSS attribute.
     * @param $csspadding (string) padding properties
     * @param $width (float) width of the containing element
     * @return array of cell paddings
     * @public
     * @since 5.9.000 (2010-10-04)
     */
    public function getCSSPadding($csspadding, $width = 0)
    {
        return $this->tcpdf->getCSSPadding($csspadding, $width);
    }

    /**
     * Get the internal Cell margin from CSS attribute.
     * @param $cssmargin (string) margin properties
     * @param $width (float) width of the containing element
     * @return array of cell margins
     * @public
     * @since 5.9.000 (2010-10-04)
     */
    public function getCSSMargin($cssmargin, $width = 0)
    {
        return $this->tcpdf->getCSSMargin($cssmargin, $width);
    }

    /**
     * Get the border-spacing from CSS attribute.
     * @param $cssbspace (string) border-spacing CSS properties
     * @param $width (float) width of the containing element
     * @return array of border spacings
     * @public
     * @since 5.9.010 (2010-10-27)
     */
    public function getCSSBorderMargin($cssbspace, $width = 0)
    {
        return $this->tcpdf->getCSSBorderMargin($cssbspace, $width);
    }

    /**
     * Convert HTML string containing font size value to points
     * @param $val (string) String containing font size value and unit.
     * @param $refsize (float) Reference font size in points.
     * @param $parent_size (float) Parent font size in points.
     * @param $defaultunit (string) Default unit (can be one of the following: %,
     * em, ex, px, in, mm, pc, pt).
     * @return float value in points
     * @public
     */
    public function getHTMLFontUnits($val, $refsize = 12, $parent_size = 12, $defaultunit = 'pt')
    {
        return $this->tcpdf->getHTMLFontUnits($val, $refsize, $parent_size, $defaultunit);
    }

    /**
     * Serialize an array of parameters to be used with TCPDF tag in HTML code.
     * @param $data (array) parameters array
     * @return string containing serialized data
     * @public static
     */
    public function serializeTCPDFtagParameters($data)
    {
        return $this->tcpdf->serializeTCPDFtagParameters($data);
    }

    /**
     * Prints a cell (rectangular area) with optional borders, background color and
     * html text string.
     * The upper-left corner of the cell corresponds to the current position. After
     * the call, the current position moves to the right or to the next line.<br />
     * If automatic page breaking is enabled and the cell goes beyond the limit, a
     * page break is done before outputting.
     * IMPORTANT: The HTML must be well formatted - try to clean-up it using an
     * application like HTML-Tidy before submitting.
     * Supported tags are: a, b, blockquote, br, dd, del, div, dl, dt, em, font,
     * h1, h2, h3, h4, h5, h6, hr, i, img, li, ol, p, pre, small, span, strong, sub,
     * sup, table, tcpdf, td, th, thead, tr, tt, u, ul
     * NOTE: all the HTML attributes must be enclosed in double-quote.
     * @param $w (float) Cell width. If 0, the cell extends up to the right margin.
     * @param $h (float) Cell minimum height. The cell extends automatically if
     * needed.
     * @param $x (float) upper-left corner X coordinate
     * @param $y (float) upper-left corner Y coordinate
     * @param $html (string) html text to print. Default value: empty string.
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $ln (int) Indicates where the current position should go after the
     * call. Possible values are:<ul><li>0: to the right (or left for RTL
     * language)</li><li>1: to the beginning of the next line</li><li>2:
     * below</li></ul>
     * Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value:
     * @param $fill (boolean) Indicates if the cell background must be painted
     * (true) or transparent (false).
     * @param $reseth (boolean) if true reset the last cell height (default true).
     * @param $align (string) Allows to center or align the text. Possible values
     * are:<ul><li>L : left align</li><li>C : center</li><li>R : right align</li><li>''
     * : empty string : left for LTR or right for RTL</li></ul>
     * @param $autopadding (boolean) if true, uses internal padding and
     * automatically adjust it to account for line width.
     * @see Multicell(), writeHTML()
     * @public
     * @return $this
     */
    public function writeHTMLCell($w, $h, $x, $y, $html = '', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '', $autopadding = true)
    {
        $this->tcpdf->writeHTMLCell($w, $h, $x, $y, $html, $border, $ln, $fill, $reseth, $align, $autopadding);
        return $this;
    }

    /**
     * Allows to preserve some HTML formatting (limited support).<br />
     * IMPORTANT: The HTML must be well formatted - try to clean-up it using an
     * application like HTML-Tidy before submitting.
     * Supported tags are: a, b, blockquote, br, dd, del, div, dl, dt, em, font,
     * h1, h2, h3, h4, h5, h6, hr, i, img, li, ol, p, pre, small, span, strong, sub,
     * sup, table, tcpdf, td, th, thead, tr, tt, u, ul
     * NOTE: all the HTML attributes must be enclosed in double-quote.
     * @param $html (string) text to display
     * @param $ln (boolean) if true add a new line after text (default = true)
     * @param $fill (boolean) Indicates if the background must be painted (true) or
     * transparent (false).
     * @param $reseth (boolean) if true reset the last cell height (default false).
     * @param $cell (boolean) if true add the current left (or right for RTL)
     * padding to each Write (default false).
     * @param $align (string) Allows to center or align the text. Possible values
     * are:<ul><li>L : left align</li><li>C : center</li><li>R : right align</li><li>''
     * : empty string : left for LTR or right for RTL</li></ul>
     * @public
     * @return $this
     */
    public function writeHTML($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '')
    {
        $this->tcpdf->writeHTML($html, $ln, $fill, $reseth, $cell, $align);
        return $this;
    }

    /**
     * Set the default bullet to be used as LI bullet symbol
     * @param $symbol (string) character or string to be used (legal values are: ''
     * = automatic, '!' = auto bullet, '#' = auto numbering, 'disc', 'disc', 'circle',
     * 'square', '1', 'decimal', 'decimal-leading-zero', 'i', 'lower-roman', 'I',
     * 'upper-roman', 'a', 'lower-alpha', 'lower-latin', 'A', 'upper-alpha',
     * 'upper-latin', 'lower-greek', 'img|type|width|height|image.ext')
     * @public
     * @since 4.0.028 (2008-09-26)
     * @return $this
     */
    public function setLIsymbol($symbol = '!')
    {
        $this->tcpdf->setLIsymbol($symbol);
        return $this;
    }

    /**
     * Set the booklet mode for double-sided pages.
     * @param $booklet (boolean) true set the booklet mode on, false otherwise.
     * @param $inner (float) Inner page margin.
     * @param $outer (float) Outer page margin.
     * @public
     * @since 4.2.000 (2008-10-29)
     * @return $this
     */
    public function setBooklet($booklet = true, $inner = -1, $outer = -1)
    {
        $this->tcpdf->SetBooklet($booklet, $inner, $outer);
        return $this;
    }

    /**
     * Set the vertical spaces for HTML tags.
     * The array must have the following structure (example):
     * $tagvs = array('h1' => array(0 => array('h' => '', 'n' => 2), 1 => array('h'
     * => 1.3, 'n' => 1)));
     * The first array level contains the tag names,
     * the second level contains 0 for opening tags or 1 for closing tags,
     * the third level contains the vertical space unit (h) and the number spaces
     * to add (n).
     * If the h parameter is not specified, default values are used.
     * @param $tagvs (array) array of tags and relative vertical spaces.
     * @public
     * @since 4.2.001 (2008-10-30)
     * @return $this
     */
    public function setHtmlVSpace($tagvs)
    {
        $this->tcpdf->setHtmlVSpace($tagvs);
        return $this;
    }

    /**
     * Set custom width for list indentation.
     * @param $width (float) width of the indentation. Use negative value to
     * disable it.
     * @public
     * @since 4.2.007 (2008-11-12)
     * @return $this
     */
    public function setListIndentWidth($width)
    {
        $this->tcpdf->setListIndentWidth($width);
        return $this;
    }

    /**
     * Set the top/bottom cell sides to be open or closed when the cell cross the
     * page.
     * @param $isopen (boolean) if true keeps the top/bottom border open for the
     * cell sides that cross the page.
     * @public
     * @since 4.2.010 (2008-11-14)
     * @return $this
     */
    public function setOpenCell($isopen)
    {
        $this->tcpdf->setOpenCell($isopen);
        return $this;
    }

    /**
     * Set the color and font style for HTML links.
     * @param $color (array) RGB array of colors
     * @param $fontstyle (string) additional font styles to add
     * @public
     * @since 4.4.003 (2008-12-09)
     * @return $this
     */
    public function setHtmlLinksStyle($color = array(0, 0, 255), $fontstyle = 'U')
    {
        $this->tcpdf->setHtmlLinksStyle($color, $fontstyle);
        return $this;
    }

    /**
     * Convert HTML string containing value and unit of measure to user's units or
     * points.
     * @param $htmlval (string) String containing values and unit.
     * @param $refsize (string) Reference value in points.
     * @param $defaultunit (string) Default unit (can be one of the following: %,
     * em, ex, px, in, mm, pc, pt).
     * @param $points (boolean) If true returns points, otherwise returns value in
     * user's units.
     * @return float value in user's unit or point if $points=true
     * @public
     * @since 4.4.004 (2008-12-10)
     */
    public function getHTMLUnitToUnits($htmlval, $refsize = 1, $defaultunit = 'px', $points = false)
    {
        return $this->tcpdf->getHTMLUnitToUnits($htmlval, $refsize, $defaultunit, $points);
    }

    /**
     * Move a page to a previous position.
     * @param $frompage (int) number of the source page
     * @param $topage (int) number of the destination page (must be less than
     * $frompage)
     * @return true in case of success, false in case of error.
     * @public
     * @since 4.5.000 (2009-01-02)
     */
    public function movePage($frompage, $topage)
    {
        return $this->tcpdf->movePage($frompage, $topage);
    }

    /**
     * Remove the specified page.
     * @param $page (int) page to remove
     * @return true in case of success, false in case of error.
     * @public
     * @since 4.6.004 (2009-04-23)
     */
    public function deletePage($page)
    {
        return $this->tcpdf->deletePage($page);
    }

    /**
     * Clone the specified page to a new page.
     * @param $page (int) number of page to copy (0 = current page)
     * @return true in case of success, false in case of error.
     * @public
     * @since 4.9.015 (2010-04-20)
     */
    public function copyPage($page = 0)
    {
        return $this->tcpdf->copyPage($page);
    }

    /**
     * Output a Table of Content Index (TOC).
     * This method must be called after all Bookmarks were set.
     * Before calling this method you have to open the page using the addTOCPage()
     * method.
     * After calling this method you have to call endTOCPage() to close the TOC
     * page.
     * You can override this method to achieve different styles.
     * @param $page (int) page number where this TOC should be inserted (leave
     * empty for current page).
     * @param $numbersfont (string) set the font for page numbers (please use
     * monospaced font for better alignment).
     * @param $filler (string) string used to fill the space between text and page
     * number.
     * @param $toc_name (string) name to use for TOC bookmark.
     * @param $style (string) Font style for title: B = Bold, I = Italic, BI = Bold
     * + Italic.
     * @param $color (array) RGB color array for bookmark title (values from 0 to
     * @public
     * @author Nicola Asuni
     * @since 4.5.000 (2009-01-02)
     * @see addTOCPage(), endTOCPage(), addHTMLTOC()
     * @return $this
     */
    public function addTOC($page = '', $numbersfont = '', $filler = '.', $toc_name = 'TOC', $style = '', $color = array(0, 0, 0))
    {
        $this->tcpdf->addTOC($page, $numbersfont, $filler, $toc_name, $style, $color);
        return $this;
    }

    /**
     * Output a Table Of Content Index (TOC) using HTML templates.
     * This method must be called after all Bookmarks were set.
     * Before calling this method you have to open the page using the addTOCPage()
     * method.
     * After calling this method you have to call endTOCPage() to close the TOC
     * page.
     * @param $page (int) page number where this TOC should be inserted (leave
     * empty for current page).
     * @param $toc_name (string) name to use for TOC bookmark.
     * @param $templates (array) array of html templates. Use: "#TOC_DESCRIPTION#"
     * for bookmark title, "#TOC_PAGE_NUMBER#" for page number.
     * @param $correct_align (boolean) if true correct the number alignment
     * (numbers must be in monospaced font like courier and right aligned on LTR, or
     * left aligned on RTL)
     * @param $style (string) Font style for title: B = Bold, I = Italic, BI = Bold
     * + Italic.
     * @param $color (array) RGB color array for title (values from 0 to 255).
     * @public
     * @author Nicola Asuni
     * @since 5.0.001 (2010-05-06)
     * @see addTOCPage(), endTOCPage(), addTOC()
     * @return $this
     */
    public function addHTMLTOC($page = '', $toc_name = 'TOC', $templates = array(), $correct_align = true, $style = '', $color = array(0, 0, 0))
    {
        $this->tcpdf->addHTMLTOC($page, $toc_name, $templates, $correct_align, $style, $color);
        return $this;
    }

    /**
     * Stores a copy of the current TCPDF object used for undo operation.
     * @public
     * @since 4.5.029 (2009-03-19)
     * @return $this
     */
    public function startTransaction()
    {
        $this->tcpdfSaved = clone $this->tcpdf;
        return $this;
    }

    /**
     * Delete the copy of the current TCPDF object used for undo operation.
     * @public
     * @since 4.5.029 (2009-03-19)
     * @return $this
     */
    public function commitTransaction()
    {
        $this->tcpdfSaved = null;
        return $this;
    }

    /**
     * This method allows to undo the latest transaction by returning the latest
     * saved TCPDF object with startTransaction().
     * @param $self (boolean) if true restores current class object to previous
     * state without the need of reassignment via the returned value.
     * @return TCPDF object.
     * @public
     * @since 4.5.029 (2009-03-19)
     * @return $this
     */
    public function rollbackTransaction($self = false)
    {
        if ($self instanceof Tcpdf) {
            $this->tcpdf = $self;
        } else if ($this->tcpdfSaved instanceof Tcpdf) {
            $this->tcpdf = clone $this->tcpdfSaved;
        } else {
            trigger_error('You must start transaction before rollback');
        }
        return $this;
    }
    
    /**
     * A transaction is started and not rollbacked or committed
     * @return bool
     */
    public function transactionInProgress()
    {
        return (bool) $this->tcpdfSaved;
    }

    /**
     * Set multiple columns of the same size
     * @param $numcols (int) number of columns (set to zero to disable columns
     * mode)
     * @param $width (int) column width
     * @param $y (int) column starting Y position (leave empty for current Y
     * position)
     * @public
     * @since 4.9.001 (2010-03-28)
     */
    public function setEqualColumns($numcols = 0, $width = 0, $y = '')
    {
        return $this->tcpdf->setEqualColumns($numcols, $width, $y);
    }

    /**
     * Remove columns and reset page margins.
     * @public
     * @since 5.9.072 (2011-04-26)
     * @return $this
     */
    public function resetColumns()
    {
        $this->tcpdf->resetColumns();
        return $this;
    }

    /**
     * Set columns array.
     * Each column is represented by an array of arrays with the following keys: (w
     * = width, s = space between columns, y = column top position).
     * @param $columns (array)
     * @public
     * @since 4.9.001 (2010-03-28)
     * @return $this
     */
    public function setColumnsArray($columns)
    {
        $this->tcpdf->setColumnsArray($columns);
        return $this;
    }

    /**
     * Set position at a given column
     * @param $col (int) column number (from 0 to getNumberOfColumns()-1); empty
     * string = current column.
     * @public
     * @since 4.9.001 (2010-03-28)
     * @return $this
     */
    public function selectColumn($col = '')
    {
        $this->tcpdf->selectColumn($col);
        return $this;
    }

    /**
     * Return the current column number
     * @return int current column number
     * @public
     * @since 5.5.011 (2010-07-08)
     */
    public function getColumn()
    {
        return $this->tcpdf->getColumn();
    }

    /**
     * Return the current number of columns.
     * @return int number of columns
     * @public
     * @since 5.8.018 (2010-08-25)
     */
    public function getNumberOfColumns()
    {
        return $this->tcpdf->getNumberOfColumns();
    }

    /**
     * Set Text rendering mode.
     * @param $stroke (int) outline size in user units (0 = disable).
     * @param $fill (boolean) if true fills the text (default).
     * @param $clip (boolean) if true activate clipping mode
     * @public
     * @since 4.9.008 (2009-04-02)
     * @return $this
     */
    public function setTextRenderingMode($stroke = 0, $fill = true, $clip = false)
    {
        $this->tcpdf->setTextRenderingMode($stroke, $fill, $clip);
        return $this;
    }

    /**
     * Set parameters for drop shadow effect for text.
     * @param $params (array) Array of parameters: enabled (boolean) set to true to
     * enable shadow; depth_w (float) shadow width in user units; depth_h (float)
     * shadow height in user units; color (array) shadow color or false to use the
     * stroke color; opacity (float) Alpha value: real value from 0 (transparent) to 1
     * (opaque); blend_mode (string) blend mode, one of the following: Normal,
     * Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn, HardLight,
     * SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity.
     * @since 5.9.174 (2012-07-25)
     * @public
     * @return $this
     */
    public function setTextShadow($params = array('enabled' => false, 'depth_w' => 0, 'depth_h' => 0, 'color' => false, 'opacity' => 1, 'blend_mode' => 'Normal'))
    {
        $this->tcpdf->setTextShadow($params);
        return $this;
    }

    /**
     * Return the text shadow parameters array.
     * @return Array of parameters.
     * @since 5.9.174 (2012-07-25)
     * @public
     */
    public function getTextShadow()
    {
        return $this->tcpdf->getTextShadow();
    }

    /**
     * Returns text with soft hyphens.
     * @param $text (string) text to process
     * @param $patterns (mixed) Array of hypenation patterns or a TEX file
     * containing hypenation patterns. TEX patterns can be downloaded from
     * http://www.ctan.org/tex-archive/language/hyph-utf8/tex/generic/hyph-utf8/patterns/
     * @param $dictionary (array) Array of words to be returned without applying
     * the hyphenation algorithm.
     * @param $leftmin (int) Minimum number of character to leave on the left of
     * the word without applying the hyphens.
     * @param $rightmin (int) Minimum number of character to leave on the right of
     * the word without applying the hyphens.
     * @param $charmin (int) Minimum word length to apply the hyphenation
     * algorithm.
     * @param $charmax (int) Maximum length of broken piece of word.
     * @return array text with soft hyphens
     * @author Nicola Asuni
     * @since 4.9.012 (2010-04-12)
     * @public
     */
    public function hyphenateText($text, $patterns, $dictionary = array(), $leftmin = 1, $rightmin = 2, $charmin = 1, $charmax = 8)
    {
        return $this->tcpdf->hyphenateText($text, $patterns, $dictionary, $leftmin, $rightmin, $charmin, $charmax);
    }

    /**
     * Enable/disable rasterization of vector images using ImageMagick library.
     * @param $mode (boolean) if true enable rasterization, false otherwise.
     * @public
     * @since 5.0.000 (2010-04-27)
     * @return $this
     */
    public function setRasterizeVectorImages($mode)
    {
        $this->tcpdf->setRasterizeVectorImages($mode);
        return $this;
    }

    /**
     * Enable or disable default option for font subsetting.
     * @param $enable (boolean) if true enable font subsetting by default.
     * @author Nicola Asuni
     * @public
     * @since 5.3.002 (2010-06-07)
     * @return $this
     */
    public function setFontSubsetting($enable = true)
    {
        $this->tcpdf->setFontSubsetting($enable);
        return $this;
    }

    /**
     * Return the default option for font subsetting.
     * @return boolean default font subsetting state.
     * @author Nicola Asuni
     * @public
     * @since 5.3.002 (2010-06-07)
     */
    public function getFontSubsetting()
    {
        return $this->tcpdf->getFontSubsetting();
    }

    /**
     * Left trim the input string
     * @param $str (string) string to trim
     * @param $replace (string) string that replace spaces.
     * @return left trimmed string
     * @author Nicola Asuni
     * @public
     * @since 5.8.000 (2010-08-11)
     */
    public function stringLeftTrim($str, $replace = '')
    {
        return $this->tcpdf->stringLeftTrim($str, $replace);
    }

    /**
     * Right trim the input string
     * @param $str (string) string to trim
     * @param $replace (string) string that replace spaces.
     * @return right trimmed string
     * @author Nicola Asuni
     * @public
     * @since 5.8.000 (2010-08-11)
     */
    public function stringRightTrim($str, $replace = '')
    {
        return $this->tcpdf->stringRightTrim($str, $replace);
    }

    /**
     * Trim the input string
     * @param $str (string) string to trim
     * @param $replace (string) string that replace spaces.
     * @return trimmed string
     * @author Nicola Asuni
     * @public
     * @since 5.8.000 (2010-08-11)
     */
    public function stringTrim($str, $replace = '')
    {
        return $this->tcpdf->stringTrim($str, $replace);
    }

    /**
     * Return true if the current font is unicode type.
     * @return true for unicode font, false otherwise.
     * @author Nicola Asuni
     * @public
     * @since 5.8.002 (2010-08-14)
     */
    public function isUnicodeFont()
    {
        return $this->tcpdf->isUnicodeFont();
    }

    /**
     * Return normalized font name
     * @param $fontfamily (string) property string containing font family names
     * @return string normalized font name
     * @author Nicola Asuni
     * @public
     * @since 5.8.004 (2010-08-17)
     */
    public function getFontFamilyName($fontfamily)
    {
        return $this->tcpdf->getFontFamilyName($fontfamily);
    }

    /**
     * Start a new XObject Template.
     * An XObject Template is a PDF block that is a self-contained description of
     * any sequence of graphics objects (including path objects, text objects, and
     * sampled images).
     * An XObject Template may be painted multiple times, either on several pages
     * or at several locations on the same page and produces the same results each
     * time, subject only to the graphics state at the time it is invoked.
     * Note: X,Y coordinates will be reset to 0,0.
     * @param $w (int) Template width in user units (empty string or zero = page
     * width less margins).
     * @param $h (int) Template height in user units (empty string or zero = page
     * height less margins).
     * @param $group (mixed) Set transparency group. Can be a boolean value or an
     * array specifying optional parameters: 'CS' (solour space name), 'I' (boolean
     * flag to indicate isolated group) and 'K' (boolean flag to indicate knockout
     * group).
     * @return int the XObject Template ID in case of success or false in case of
     * error.
     * @author Nicola Asuni
     * @public
     * @since 5.8.017 (2010-08-24)
     * @see endTemplate(), printTemplate()
     */
    public function startTemplate($w = 0, $h = 0, $group = false)
    {
        return $this->tcpdf->startTemplate($w, $h, $group);
    }

    /**
     * End the current XObject Template started with startTemplate() and restore the
     * previous graphic state.
     * An XObject Template is a PDF block that is a self-contained description of
     * any sequence of graphics objects (including path objects, text objects, and
     * sampled images).
     * An XObject Template may be painted multiple times, either on several pages
     * or at several locations on the same page and produces the same results each
     * time, subject only to the graphics state at the time it is invoked.
     * @return int the XObject Template ID in case of success or false in case of
     * error.
     * @author Nicola Asuni
     * @public
     * @since 5.8.017 (2010-08-24)
     * @see startTemplate(), printTemplate()
     */
    public function endTemplate()
    {
        return $this->tcpdf->endTemplate();
    }

    /**
     * Print an XObject Template.
     * You can print an XObject Template inside the currently opened Template.
     * An XObject Template is a PDF block that is a self-contained description of
     * any sequence of graphics objects (including path objects, text objects, and
     * sampled images).
     * An XObject Template may be painted multiple times, either on several pages
     * or at several locations on the same page and produces the same results each
     * time, subject only to the graphics state at the time it is invoked.
     * @param $id (string) The ID of XObject Template to print.
     * @param $x (int) X position in user units (empty string = current x position)
     * @param $y (int) Y position in user units (empty string = current y position)
     * @param $w (int) Width in user units (zero = remaining page width)
     * @param $h (int) Height in user units (zero = remaining page height)
     * @param $align (string) Indicates the alignment of the pointer next to
     * template insertion relative to template height. The value can be:<ul><li>T:
     * top-right for LTR or top-left for RTL</li><li>M: middle-right for LTR or
     * middle-left for RTL</li><li>B: bottom-right for LTR or bottom-left for
     * RTL</li><li>N: next line</li></ul>
     * @param $palign (string) Allows to center or align the template on the
     * current line. Possible values are:<ul><li>L : left align</li><li>C :
     * center</li><li>R : right align</li><li>'' : empty string : left for LTR or right
     * for RTL</li></ul>
     * @param $fitonpage (boolean) If true the template is resized to not exceed
     * page dimensions.
     * @author Nicola Asuni
     * @public
     * @since 5.8.017 (2010-08-24)
     * @see startTemplate(), endTemplate()
     */
    public function printTemplate($id, $x = '', $y = '', $w = 0, $h = 0, $align = '', $palign = '', $fitonpage = false)
    {
        return $this->tcpdf->printTemplate($id, $x, $y, $w, $h, $align, $palign, $fitonpage);
    }

    /**
     * Set the percentage of character stretching.
     * @param $perc (int) percentage of stretching (100 = no stretching)
     * @author Nicola Asuni
     * @public
     * @since 5.9.000 (2010-09-29)
     * @return $this
     */
    public function setFontStretching($perc = 100)
    {
        $this->tcpdf->setFontStretching($perc);
        return $this;
    }

    /**
     * Get the percentage of character stretching.
     * @return float stretching value
     * @author Nicola Asuni
     * @public
     * @since 5.9.000 (2010-09-29)
     */
    public function getFontStretching()
    {
        return $this->tcpdf->getFontStretching();
    }

    /**
     * Set the amount to increase or decrease the space between characters in a text.
     * @param $spacing (float) amount to increase or decrease the space between
     * characters in a text (0 = default spacing)
     * @author Nicola Asuni
     * @public
     * @since 5.9.000 (2010-09-29)
     * @return $this
     */
    public function setFontSpacing($spacing = 0)
    {
        $this->tcpdf->setFontSpacing($spacing);
        return $this;
    }

    /**
     * Get the amount to increase or decrease the space between characters in a text.
     * @return int font spacing (tracking) value
     * @author Nicola Asuni
     * @public
     * @since 5.9.000 (2010-09-29)
     */
    public function getFontSpacing()
    {
        return $this->tcpdf->getFontSpacing();
    }

    /**
     * Return an array of no-write page regions
     * @return array of no-write page regions
     * @author Nicola Asuni
     * @public
     * @since 5.9.003 (2010-10-13)
     * @see setPageRegions(), addPageRegion()
     */
    public function getPageRegions()
    {
        return $this->tcpdf->getPageRegions();
    }

    /**
     * Set no-write regions on page.
     * A no-write region is a portion of the page with a rectangular or trapezium
     * shape that will not be covered when writing text or html code.
     * A region is always aligned on the left or right side of the page ad is
     * defined using a vertical segment.
     * You can set multiple regions for the same page.
     * @param $regions (array) array of no-write regions. For each region you can
     * define an array as follow: ('page' => page number or empy for current page, 'xt'
     * => X top, 'yt' => Y top, 'xb' => X bottom, 'yb' => Y bottom, 'side' => page side
     * 'L' = left or 'R' = right). Omit this parameter to remove all regions.
     * @author Nicola Asuni
     * @public
     * @since 5.9.003 (2010-10-13)
     * @see addPageRegion(), getPageRegions()
     * @return $this
     */
    public function setPageRegions($regions = array())
    {
        $this->tcpdf->setPageRegions($regions);
        return $this;
    }

    /**
     * Add a single no-write region on selected page.
     * A no-write region is a portion of the page with a rectangular or trapezium
     * shape that will not be covered when writing text or html code.
     * A region is always aligned on the left or right side of the page ad is
     * defined using a vertical segment.
     * You can set multiple regions for the same page.
     * @param $region (array) array of a single no-write region array: ('page' =>
     * page number or empy for current page, 'xt' => X top, 'yt' => Y top, 'xb' => X
     * bottom, 'yb' => Y bottom, 'side' => page side 'L' = left or 'R' = right).
     * @author Nicola Asuni
     * @public
     * @since 5.9.003 (2010-10-13)
     * @see setPageRegions(), getPageRegions()
     * @return $this
     */
    public function addPageRegion($region)
    {
        $this->tcpdf->addPageRegion($region);
        return $this;
    }

    /**
     * Remove a single no-write region.
     * @param $key (int) region key
     * @author Nicola Asuni
     * @public
     * @since 5.9.003 (2010-10-13)
     * @see setPageRegions(), getPageRegions()
     * @return $this
     */
    public function removePageRegion($key)
    {
        $this->tcpdf->removePageRegion($key);
        return $this;
    }

    /**
     * Embedd a Scalable Vector Graphics (SVG) image.
     * NOTE: SVG standard is not yet fully implemented, use the
     * setRasterizeVectorImages() method to enable/disable rasterization of vector
     * images using ImageMagick library.
     * @param $file (string) Name of the SVG file or a '@' character followed by
     * the SVG data string.
     * @param $x (float) Abscissa of the upper-left corner.
     * @param $y (float) Ordinate of the upper-left corner.
     * @param $w (float) Width of the image in the page. If not specified or equal
     * to zero, it is automatically calculated.
     * @param $h (float) Height of the image in the page. If not specified or equal
     * to zero, it is automatically calculated.
     * @param $link (mixed) URL or identifier returned by AddLink().
     * @param $align (string) Indicates the alignment of the pointer next to image
     * insertion relative to image height. The value can be:<ul><li>T: top-right for
     * LTR or top-left for RTL</li><li>M: middle-right for LTR or middle-left for
     * RTL</li><li>B: bottom-right for LTR or bottom-left for RTL</li><li>N: next
     * line</li></ul> If the alignment is an empty string, then the pointer will be
     * restored on the starting SVG position.
     * @param $palign (string) Allows to center or align the image on the current
     * line. Possible values are:<ul><li>L : left align</li><li>C : center</li><li>R :
     * right align</li><li>'' : empty string : left for LTR or right for RTL</li></ul>
     * @param $border (mixed) Indicates if borders must be drawn around the cell.
     * The value can be a number:<ul><li>0: no border (default)</li><li>1:
     * frame</li></ul> or a string containing some or all of the following characters
     * (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B:
     * bottom</li></ul> or an array of line styles for each border group - for example:
     * array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash'
     * => 0, 'color' => array(0, 0, 0)))
     * @param $fitonpage (boolean) if true the image is resized to not exceed page
     * dimensions.
     * @author Nicola Asuni
     * @since 5.0.000 (2010-05-02)
     * @public
     * @return $this
     */
    public function imageSVG($file, $x = '', $y = '', $w = 0, $h = 0, $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false)
    {
        $this->tcpdf->ImageSVG($file, $x, $y, $w, $h, $link, $align, $palign, $border, $fitonpage);
        return $this;
    }
}
