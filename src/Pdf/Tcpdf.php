<?php
namespace Osf\Pdf;

use TCPDF as OriginalTcpdf;
use Osf\Pdf\Document\Bean\ContactBean;
use Osf\Pdf\Document\Bean\AddressBean;
use Osf\Exception\ArchException;
use Osf\Stream\Text as T;

/**
 * Tcpdf main class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class Tcpdf extends OriginalTcpdf
{
    const DEBUG_BORDER = 0;
    
    /**
     * @var ContactBean
     */
    protected $contact;
    
    protected $defaultFont = 'latolight';
    protected $defaultFontLight = 'latolight';
    protected $place;
    protected $date;
    protected $dateLabel = null;
    protected $confidential = true;
    protected $createdBy = true;
    protected $defaultColor = ['r' => 0, 'g' => 0, 'b' => 0];
    protected $linkColor    = ['r' => 0, 'g' => 0, 'b' => 255];
    
    protected $headTitle;           // Grand titre "FACTURE"
    protected $headSubTitle;        // Sous-Titre "F01"
    protected $headTitleLines = []; // Lignes sous les titres (dates)
    
    protected $footerTop; // Y du haut du footer pour la pagination
    
    protected $params = false; // Paramètres de l'utilisateur courant
    
    /**
     * @param string $regular
     * @param string $light
     * @return $this
     */
    public function setDefaultFont(string $regular, $light = null)
    {
        $this->defaultFont = $regular;
        $this->defaultFontLight = (string) ($light ?: $regular);
        return $this;
    }
    
    public function getDefaultFont()
    {
        return $this->defaultFont;
    }

    public function getDefaultFontLight()
    {
        return $this->defaultFontLight;
    }
    
    public function getFooterTop()
    {
        return $this->footerTop;
    }
    
    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @return $this
     */
    public function setDefaultColor(int $r, int $g, int $b)
    {
        $this->defaultColor = ['r' => $r, 'g' => $g, 'b' => $b];
        return $this;
    }

    public function getDefaultColor()
    {
        return $this->defaultColor;
    }
    
    public function setLinkColor(int $r, int $g, int $b)
    {
        $this->linkColor = ['r' => $r, 'g' => $g, 'b' => $b];
        return $this;
    }
    
    public function getlinkColor()
    {
        return $this->linkColor;
    }
    
    public function setHeadFootInfo(ContactBean $contact, $place = null, $date = null, bool $confidential = true, bool $createdBy = true)
    {
        $this->contact = $contact;
        $this->place = (string) ($place ?: ($contact->getAddress() && $contact->getAddress()->getCity(true) ? $contact->getAddress()->getCity(true) : '')); 
        $this->date  = (string) ($date ?: date('d/m/Y'));
        $this->confidential = $confidential;
        $this->createdBy = $createdBy;
        return $this;
    }
    
    /**
     * @param $dateLabel string|null
     * @return $this
     */
    public function setDateLabel($dateLabel)
    {
        $this->dateLabel = $dateLabel === null ? null : (string) $dateLabel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateLabel()
    {
        return $this->dateLabel;
    }
    
    public function setTitles($title, $subTitle = null, array $titleLines = [])
    {
        $this->headTitle = $title === null ? null : (string) $title;
        $this->headSubTitle = $subTitle === null ? null : (string) $subTitle;
        $this->headTitleLines = $titleLines;
    }
    
    /**
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = is_array($params) ? $params : false;
        return $this;
    }

    /**
     * array (size=8)
     * 'color' => string 'logo' (length=4)
     * 'coloralt' => string '#1d46ad' (length=7)
     * 'madesma' => string '1' (length=1)
     * 'confidential' => boolean false
     * 'qrcode' => string '1' (length=1)
     * 'header' => 
     *   array (size=9)
     *     0 => string 'title' (length=5)
     *     1 => string 'desc' (length=4)
     *     2 => string 'address' (length=7)
     *     3 => string 'email' (length=5)
     *     4 => string 'url' (length=3)
     *     5 => string 'tel' (length=3)
     *     6 => string 'gsm' (length=3)
     *     7 => string 'fax' (length=3)
     *     8 => string 'siret' (length=3)
     *     9 => string 'user'
     * 'footer1' => 
     *   array (size=2)
     *     0 => string 'title' (length=5)
     *     1 => string 'address' (length=7)
     * 'footer2' => 
     *   array (size=3)
     *     0 => string 'tel' (length=3)
     *     1 => string 'gsm' (length=3)
     *     2 => string 'fax' (length=3)
     * 
     * @return type
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * Récupère des paramètres de configuration avec ou non un test sur une valeur hasValue
     * @param type $param
     * @param type $hasValue
     * @param type $returnIfParamIsNull
     * @return boolean
     */
    protected function param($param, $hasValue = null, $returnIfParamIsNull = true)
    {
        if (!$this->params) {
            return $returnIfParamIsNull;
        }
        if (isset($this->params[$param])) {
            if ($hasValue === null) {
                return $this->params[$param];
            }
            if (is_array($this->params[$param])) {
                return in_array($hasValue, $this->params[$param]);
            }
            return $this->params[$param] == $hasValue;
        }
        return false;
    }
    
    protected function getFooterLine($keys)
    {
        if (!is_array($keys) || !$keys) {
            return null;
        }
        $values = [];
        foreach ($keys as $key) {
            $values[] = $this->getContactValue($key);
        }
        return $this->implode($values);
    }
    
    protected function getContactValue($key)
    {
        $c = $this->contact;
        switch ($key) {
            case 'title'   : return $c->getCompanyName(); // Nom de société
            case 'user'    : return $c->getComputedFullname();
            case 'desc'    : return $c->getCompanyDesc(); // Slogan
            case 'address' : return $c->getAddress()->getComputedAddress(false, false, true, ' - ');
            case 'email'   : return $c->getComputedEmail();
            case 'url'     : return $c->getUrl();
            case 'tel'     : return $c->getComputedTel() ? __("tél.") . ' ' . T::phoneFormat($c->getComputedTel()) : '';
            case 'gsm'     : return $c->getGsm() ? __("mob.") . ' ' . T::phoneFormat($c->getGsm()) : '';
            case 'fax'     : return $c->getComputedFax() ? __("fax.") . ' ' . T::phoneFormat($c->getComputedFax()) : '';
            case 'siret'   : return $c->getCompanySiret() ? __("Siret:") . ' ' . T::formatSiret($c->getCompanySiret()) : '';
            case 'siren'   : return $c->getCompanySiret() ? __("Siren:") . ' ' . T::siretToSiren($c->getCompanySiret(), true) : '';
            case 'logo'    : return $c->getCompanyLogo();
            case 'intro'   : return $c->getCompanyIntro();
            case 'rcs'     : return $c->getCompanyRegistration();
            case 'tvai'    : return $c->getCompanyTvaIntra() ? __("TVA Intra:") . ' ' . T::formatTvaIntra($c->getCompanyTvaIntra()) : '';
            case 'ape'     : return $c->getCompanyApe() ? __("Code APE:") . ' ' . $c->getCompanyApe() : '';
            // case 'tvai'    : return $c->get
            default : throw new ArchException('Unknown contact key [' . $key . ']');
        }
    }

    public function Header() {
        
        // Récupération des marges et du contact
        $margins = $this->getMargins();
        $margins['top'] = $this->getHeaderMargin();
        if (!$this->contact) {
            $this->contact = new ContactBean();
        }
        $c = $this->contact;
        if (!$c->getAddress()) {
            $c->setAddress(new AddressBean());
        }
        
        // Logo : récupération de l'image, calcul des dimension 
        $logo = $this->param('header', 'logo') && $c->getCompanyLogo() ? $c->getCompanyLogo() : false;
        if ($logo) {
            [$iw, $ih] = getimagesize($logo->getImageFile());
            
            $minW = 20;
            $minH = 15;
            $maxW = 80;
            $maxH = 40;

            if ($iw > ($ih * 2)) {
                $orientation = 'h';
                $w = min($maxW, ($iw / $ih) * $minW);
                $logoSize = ['w' => $w, 'h' => min($minH, $ih * ($w / $iw))];
            } else {
                $orientation = 'v';
                $h = min($maxH, ($ih / $iw) * $minW);
                $logoSize = ['w' => min($minW, $iw * ($h / $ih)), 'h' => $h];
            }
        }

        // Logo : détection de l'orientation et calcul des marges
        $padding = 1;
        if (!isset($orientation)) {
            $x = $margins['left'];
            $y = $margins['top'];
        } else if ($orientation == 'v') {
            $x = $margins['left'] + $logoSize['w'] + 5;
            $y = $margins['top'] + $padding - 1;
        } else {
            $x = $margins['left'];
            $y = $margins['top'] + $logoSize['h'] + 4;
        }

        // Affichage du logo
        if ($logo) {
            $this->Image($logo->getImageFile(), $margins['left'], $margins['top'], $logoSize['w'], $logoSize['h'], 'PNG', '', 'T', false, 300, '', false, false, 0, true, false, false);
        }
        
        // Initialisation pour écriture du reste
        $color = $this->defaultColor;
        $title = $this->param('header', 'title') ? $c->getComputedTitle() : false;
        $strap = 0;
        $this->SetY($y);
        $this->SetX($x);
        
        // Titre (nom de société)
        if ($title) {
            $this->SetFont($this->defaultFont, 'B', 10);
            $this->setColor('text', $color['r'], $color['g'], $color['b']);
            $this->Cell(80, 0, $title, self::DEBUG_BORDER, true, 'L', false, '', false, false, 'A', 'T');
            $strap = 2;
            $y = $this->GetY();
        }
        
        $this->SetFont($this->defaultFontLight, '', 10);
        $this->setColor('text', 0, 0, 0);
        $this->SetY($margins['top']);
        
        // Date et lieu
        $headTitleLines = $this->headTitleLines;
        if ($this->place || $this->date) {
            if (!$this->headTitle) {
                $text = $this->place . ($this->place && $this->date ? __(", le ") : '') . $this->date;
                $this->Cell(0, 0, $text, self::DEBUG_BORDER, true, 'R', false, '', false, false, 'A', 'T');
                $this->SetY($this->GetY() + 1);
            } else if ($this->date) {
                $text = ($this->getDateLabel() ?? __("Date :")) . ' ' . $this->date;
                array_unshift($headTitleLines, $text);
            }
        }
        
        // Grand titre + Sous-titres
        if ($this->headTitle) {
            $this->SetFont($this->defaultFont, 'B');
            $this->SetFontSize(18);
            $this->SetY($this->GetY() - 0.8);
            $this->Cell(0, 0, $this->headTitle, self::DEBUG_BORDER, true, 'R', false, '', 0, false, 'T', 'T');
        }
        if ($this->headSubTitle) {
            $this->SetFont($this->defaultFont, '', 14);
            $this->cell(0, 8, $this->headSubTitle, self::DEBUG_BORDER, 1, 'R', false, '', 0, false, 'T', 'T');
        }
        if ($headTitleLines) {
            $this->SetFont($this->defaultFontLight, '', 8);
            $this->setColor('text', 0, 0, 0);
            foreach ($headTitleLines as $line) {
                $this->Cell(0, 0, $line, self::DEBUG_BORDER, true, 'R');
            }
        }
        
        // Initialisation de la fonte et du curseur...
        $this->SetFont($this->defaultFontLight, '', 9);
        $this->SetY($y);
        
        // Slogan
        if ($this->param('header', 'desc') && $c->getCompanyDesc()) {
            $this->SetX($x);
            $this->Cell(120, 0, $c->getCompanyDesc(), self::DEBUG_BORDER, 1, 'L', false, '', false, false, 'A', 'T');
            $y = $this->GetY();
        }
        
        // Siret
        if ($this->param('header', 'siret') && $c->getCompanySiret()) {
            $this->SetX($x);
            $this->Cell(120, 0, __("Siret:") . ' ' . T::formatSiret($c->getCompanySiret()), self::DEBUG_BORDER, 1, 'L', false, '', false, false, 'A', 'T');
            $y = $this->GetY();
        }
        
        // Siren
        if ($this->param('header', 'siren') && $c->getCompanySiret()) {
            $this->SetX($x);
            $this->Cell(120, 0, __("Siren:") . ' ' . T::siretToSiren($c->getCompanySiret(), true), self::DEBUG_BORDER, 1, 'L', false, '', false, false, 'A', 'T');
            $y = $this->GetY();
        }
        
        // Nom de l'utilisateur si celui-ci n'est pas le nom de la société
        if ($this->param('header', 'user') && $c->getComputedFullname() !== $title) {
            $this->SetX($x);
            $this->Cell(120, 0, $c->getComputedFullname(), self::DEBUG_BORDER, 1, 'L', false, '', false, false, 'A', 'T');
            $y = $this->GetY();
        }
        
        // Adresse
        $address = $c->getAddress();
        if ($this->param('header', 'address') && ($address->getAddress() || $address->getCity())) {
            $addTxt = $address->getComputedAddress(false, false);
            $this->MultiCell(120, 0, $addTxt, self::DEBUG_BORDER, 'L', false, true, $x, $y, true, 1, false, true, 0, 'T', true);
            $strap = 2;
        }
        $spacing = 4;
        $y = $this->GetY() - $spacing;
        
        // Email & Url
        if ($c->getComputedEmail()
        ||  $c->getUrl()) {
            $y += $strap;
            $strap = 2;
            if ($this->param('header', 'email') && $c->getComputedEmail()) {
                $this->SetY($y = $y + $spacing);
                $this->SetX($x);
                $this->setColor('text', $this->linkColor['r'], $this->linkColor['g'], $this->linkColor['b']);
                $this->Cell(120, 0, $c->getComputedEmail(), self::DEBUG_BORDER, true, 'L', false, 'mailto:' . $c->getComputedEmail(), true, true, 'A', 'T');
                $this->setColor('text', 0, 0, 0);
            }
            if ($this->param('header', 'url') && $c->getUrl()) {
                $this->SetY($y = $y + $spacing - 0.5);
                $this->SetX($x);
                $this->setColor('text', $this->linkColor['r'], $this->linkColor['g'], $this->linkColor['b']);
                $this->Cell(120, 0, $c->getUrl(), self::DEBUG_BORDER, true, 'L', false, $c->getUrl(), true, true, 'A', 'T');
                $this->setColor('text', 0, 0, 0);
            }
        }
        
        // Tel, Fax, Gsm
        if ($c->getComputedTel()
        ||  $c->getComputedFax()
        ||  $c->getGsm()
        ) {
            $y += $strap;
            $labelSpace = 8;
            if ($this->param('header', 'tel') && $c->getComputedTel()) {
                $this->SetY($y = $y + $spacing);
                $this->SetX($x);
                $this->Cell($labelSpace, 0, __("tél."), self::DEBUG_BORDER, false, 'L', false, '', true, true, 'A', 'T');
                $this->Cell(120 - $labelSpace, 0, T::phoneFormat($c->getComputedTel()), self::DEBUG_BORDER, true, 'L', false, '', true, true, 'A', 'T');
            }
            if ($this->param('header', 'gsm') && $c->getGsm()) {
                $this->SetY($y = $y + $spacing);
                $this->SetX($x);
                $this->Cell($labelSpace, 0, __("mob."), self::DEBUG_BORDER, false, 'L', false, '', true, true, 'A', 'T');
                $this->Cell(120 - $labelSpace, 0, T::phoneFormat($c->getGsm()), self::DEBUG_BORDER, true, 'L', false, '', true, true, 'A', 'T');
            }
            if ($this->param('header', 'fax') && $c->getComputedFax()) {
                $this->SetY($y = $y + $spacing);
                $this->SetX($x);
                $this->Cell($labelSpace, 0, __("fax."), self::DEBUG_BORDER, false, 'L', false, '', true, true, 'A', 'T');
                $this->Cell(120 - $labelSpace, 0, T::phoneFormat($c->getComputedFax()), self::DEBUG_BORDER, true, 'L', false, '', true, true, 'A', 'T');
            }
        }
        
        // Redéfinition des mages du document en fonction de la taille de l'entête
        $this->SetMargins($margins['left'], $this->GetY() + 20, $margins['right'], true);
        //$this->SetAutoPageBreak(true, 30);
    }

    public function Footer() {
        
        // Remplissage des lignes
        $firstLine  = $this->getFooterLine($this->param('footer1'));
        $secondLine = $this->getFooterLine($this->param('footer2'));
        $thirdLine  = $this->getFooterLine($this->param('footer3'));
        
//        $c = $this->contact;
//        $data = explode("\n", $c->getAddress()->getAddress());
//        //array_unshift($data, $c->getComputedTitle());
//        //array_unshift($data, $c->getCompanyName());
//        array_push($data, $c->getAddress()->getPostalCode() . ' ' . $c->getAddress()->getCity());
//        $firstLine = $this->implode($data);
//        $data = [
//            $c->getComputedTel() ? __("tél.") . ' ' . T::phoneFormat($c->getComputedTel()) : '',
//            $c->getGsm() ? __("mob.") . ' ' . T::phoneFormat($c->getGsm()) : '',
//            $c->getComputedFax() ? __("fax.") . ' ' . T::phoneFormat($c->getComputedFax()) : ''
//        ];
//        $secondLine = $this->implode($data);
        
        // Calcul de marges et écriture
        $margins = $this->getMargins();
        if (!$this->footerTop) {
            $nblines = max(1, (int) (bool) $firstLine 
                     + (int) (bool) $secondLine
                     + (int) (bool) $thirdLine
                     + (int) $this->confidential);
            $this->footerTop = -16 - (3.086 * $nblines);
        }
        $this->SetY($this->footerTop);
        $this->SetLineStyle(['width' => 0.1, 'dash' => 0, 'color' => array_values($this->defaultColor)]);
        $this->Line($margins['left'], $this->GetY(), $this->getPageWidth() - $margins['left'], $this->GetY());
        $initialY = $this->GetY() + 1;
        $this->SetY($initialY);
        $this->SetFont($this->defaultFontLight, '', 7);
        
        $firstLine  && $this->Cell(0, 0, $firstLine,  self::DEBUG_BORDER, true, 'C', 0, '', 0, false, 'T', 'M');
        $secondLine && $this->Cell(0, 0, $secondLine, self::DEBUG_BORDER, true, 'C', 0, '', 0, false, 'T', 'M');
        $thirdLine  && $this->Cell(0, 0, $thirdLine,  self::DEBUG_BORDER, true, 'C', 0, '', 0, false, 'T', 'M');
        if ($this->confidential) {
            $this->SetFont($this->defaultFont, 'B', 7);
            $this->Cell(0, 0, __("document confidentiel"), self::DEBUG_BORDER, true, 'C', 0, '', 0, false, 'T', 'M');
            $this->SetFont($this->defaultFontLight, '', 7);
        }
        $this->SetY(max($initialY, $this->GetY() - 3.086));
        $w = $this->getPageWidth() - $margins['left'] - $margins['right'] + 6.2; // Offset de correction, erreur d'alias
        $this->Cell($w, 0, 'page ' . $this->PageNo() . '/' . $this->getAliasNbPages(), self::DEBUG_BORDER, false, 'R');
        if ($this->createdBy) {
            $this->SetFont($this->defaultFont, '', 5);
            $this->setColor('text', $this->linkColor['r'], $this->linkColor['g'], $this->linkColor['b']);
            $this->SetX($margins['left']);
            $this->SetY(max($initialY, $this->GetY() - 1.5));
            $appName = defined('APP_NAME') ? APP_NAME : __("SimpleManager");
            $appUrl = defined('APP_HOST') ? 'https://www.' . APP_HOST : 'https://www.simplemanager.fr';
            $this->Cell(0, 0, sprintf(__("Généré par %s"), $appName), self::DEBUG_BORDER, true, 'L', 0, $appUrl, 0, false, 'T', 'M');
            $this->Cell(0, 0, $appUrl, self::DEBUG_BORDER, true, 'L', 0, $appUrl, 0, false, 'T', 'M');
            $this->setColor('text', 0, 0, 0);
        }
        //$this->setFooterMargin($this->getFooterTop() + 10);
    }
    
    protected function implode($values, string $separator = ' - ')
    {
        $sep = '';
        $retVal = '';
        foreach ($values as $value) {
            $value = trim($value);
            if (!$value) {
                continue;
            }
            $retVal .= $sep . $value;
            $sep = $separator;
        }
        return $retVal;
    }
}
