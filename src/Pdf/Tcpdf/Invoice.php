<?php
namespace Osf\Pdf\Tcpdf;

use Osf\Pdf\Tcpdf\Document;
use Osf\Pdf\Document\Bean\InvoiceBean;
use Osf\Pdf\Document\Bean\ProductBean;
use Osf\Pdf\Document\Config\InvoiceConfig;
use Osf\Pdf\Document\Bean\BankDetailsBean;
use Osf\Pdf\Document\Bean\BaseDocumentBean;
use Osf\Exception\ArchException;
use Osf\Stream\Text as T;
use Osf\Controller\Router;
use Osf\Stream\Html;

/**
 * Invoice generator with tcpdf
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class Invoice extends Document
{
    const FONT_SIZE = 10;
    const DEBUG_BORDER = 0;
    const FOOTER_MARGIN = 30;
    const BLOCK_MARGIN = 4;  // Marge entre les blocs
    
    const COLORED_LINES = false;
    
    /**
     * @var \Osf\Pdf\Document\Bean\InvoiceBean
     */
    protected $bean;
    
    // Hauteur de cellule avec la police standard, calculé au début du document
    protected $cellHeight;
    
    public function __construct(InvoiceBean $bean)
    {
        $this->bean = $bean;
        parent::__construct();
        // $this->setParams($bean->getParams());
    }
    
    /**
     * @return $this
     */
    public function setBean(BaseDocumentBean $bean)
    {
        throw new ArchException('Set the bean in constructor only please.');
    }

    /**
     * @return \Osf\Pdf\Document\Bean\InvoiceBean
     */
    public function getBean()
    {
        return parent::getBean();
    }
    
    /**
     * Main build class
     */
    protected function buildInvoice()
    {
        // Configuration
        $b = $this->bean;
        $c = $b->getConfig();
        $this->buildConfiguration($c);
        if (!$b->getProvider()->getAddress()->getTitle()) {
            $b->getProvider()->getAddress()->setTitle($b->getProvider()->getComputedTitle());
        }
        if (!$b->getRecipient()->getAddress()->getTitle()) {
            $b->getRecipient()->getAddress()->setTitle($b->getRecipient()->getComputedTitle());
        }
        
        // Titles
        $titleLines = [];
        $computedValidity = $b->getComputedValidity();
        if ($computedValidity) {
            $validity  = $b->getType() === InvoiceBean::TYPE_INVOICE ? __("Date limite de paiement :") : __("Fin de validité :");
            $validity .= ' ' . $computedValidity;
            $titleLines[] = $validity;
        }
        $this->setTitles($c->getInvoiceTypeTitle($b->getType(), $b->isCredit()), $b->getCode(), $titleLines);
        
        // Meta, header, footer, address window
        $this->setSubject($b->getSubject());
        $this->setTitle($b->getTitle());
        $this->setHeadFootInfo($b->getProvider(), null, T::formatDate($b->getDateSending()), $b->getConfidential(), $b->getDisplayCreatedBy())
             ->setDateLabel($b->getType() === InvoiceBean::TYPE_INVOICE ? __("Date de facturation :") : null)
             ->addPage()
             ->addAddressWindow($b->getRecipient()->getAddress(), 11);

        // Body
        $this->buildBody($b, $c);
    }
    
    /**
     * Prepare document from configuration bean
     * @param InvoiceConfig $c
     */
    protected function buildConfiguration(InvoiceConfig $c)
    {
        if ($c->getFontFamily()) {
            $this->setDefaultFont($c->getFontFamily(), $c->getFontFamilyLight());
        }
        $this->setMargins($c->getMarginLeft(), $c->getMarginTop(), $c->getMarginRight(), true);
        $this->setHeaderMargin($c->getMarginTop());
    }
    
    /**
     * Build invoice content
     * @param InvoiceBean $b
     * @param InvoiceConfig $c
     * @throws \Osf\Exception\DisplayedException
     */
    protected function buildBody(InvoiceBean $b, InvoiceConfig $c)
    {
        $size = $c->getFontSize();
        $this->setFont($this->getDefaultFontLight(), '', $size);
        
        // Libelles
        $libs = $b->getLibs();
        if ($b->getRecipient()->getAddressDelivery()) {
            $libs[__("Livraison :")] = $b->getRecipient()->getAddressDelivery()->getComputedAddress(false);
        }
        $this->writeLibs($libs);

        // Texte à insérer en début de page
        if ($b->getMdBefore()) {
            $this->setY($this->getY() + 2);
            $txt = $b->getMdBefore(true);
            $this->writeHTML($txt, true, false, false, false, $b->getMdBeforeAlign());
        }
        
        // Pour la génération des produits, je gère moi-même la pagination.
        $this->setY($this->getY() + 4);
        $this->setAutoPageBreak(false);
        
        // Détermine les colonnes à afficher + configuration
        $hasDiscount = false;
        $hasTax      = false;
        $hasQuantity = false;
        $noDisplayTax = $b->getTaxFranchise() || $b->getDisplayTax() === $b::DISPLAY_TAX_NO || (!$b->getRecipient()->getChargeWithTax() && $b->getDisplayTax() === $b::DISPLAY_TAX_AUTO);
        $totalHT  = 0;
        $totalTTC = 0;
        foreach ($b->getProducts() as $product) {
            if (!($product instanceof ProductBean)) {
                throw new ArchException('Bad product type');
            }
            $hasDiscount = $hasDiscount || $product->getDiscount();
            $hasTax = !$noDisplayTax && ($hasTax || $product->getTax());
            $hasQuantity = $hasQuantity || $product->getQuantity() !== 1;
            $totalHT  += $product->getTotalPriceHT();
            $totalTTC += $noDisplayTax ? $product->getTotalPriceHT() : $product->getTotalPriceTTC();
        }
        if ($b->isCredit()) {
            $totalHT = -$totalHT;
            $totalTTC = -$totalTTC;
        }
        
        $cols = $c->getColsParams($hasTax && !$noDisplayTax);
        if (!$hasQuantity) { unset($cols['quantity']); }
        if (!$hasQuantity) { unset($cols['ttc']); }
        if (!$hasDiscount) { unset($cols['discount']); }
        if (!$hasTax || $noDisplayTax) { unset($cols['tax']); }
        if (!$hasTax || $noDisplayTax) { unset($cols['ttc']); }
        if ((!$hasTax || $noDisplayTax) && !$hasDiscount && !$hasQuantity && !$b->isCredit()) { unset($cols['tttc']); }
        $cols['title']['size'] = $this->getPageWidthWithoutMargins() - $this->colsSum($cols);
        
        // Labels & Produits
        if ($b->getProducts()) {
            $this->setCellPaddings(0, 0, 0, 0);
            $this->writeLabels($cols);
            $this->writeProducts($b, $c, $cols, $noDisplayTax);
        }
        
        // Facture vide
        else {
            $yBegin = $this->getY();
            $this->setCellPaddings(0, 1, 0, 1);
            $this->cell($this->getPageWidthWithoutMargins(), 0, __("Ce document ne contient aucun produit."), 0, 1, 'L');
            $this->cellHeight = $this->getY() - $yBegin;
        }
        
        // Changement de page si le tableau des totaux n'a pas assez de place
        $footerHeight = $b->hasRib() ? $this->cellHeight * 7 : $this->cellHeight * 4;
        if ($this->getY() > ($this->getPageHeight() - self::FOOTER_MARGIN - $footerHeight)) {
            $this->addPage();
            $this->setY($this->getMargins()['top']);
        }
        
        // Tableau des totaux
        $wLabel = 35;
        $wValue = 25;
        $wTotal = $wLabel + $wValue;
        $this->setLineWidth(0.05);
        $this->setCellPaddings(2, 1, 2, 1);
        $tabX = $this->getPageWidth() - $this->getMargins()['right'] - $wTotal;
        $tabY = $this->getY() + 6;
        $this->setY($tabY);
        
        // Totaux HT + taxe
        if ($hasTax && !$noDisplayTax) {
            $this->setX($tabX);
            $this->cell($wLabel, 0, __("Total HT"), 1, 0);
            $this->cell($wValue, 0, T::currencyFormat($totalHT), 1, 1, 'R');
            $this->setX($tabX);
            $this->cell($wLabel, 0, __("Total TVA "), 1, 0);
            $this->cell($wValue, 0, T::currencyFormat($totalTTC - $totalHT), 1, 1, 'R');
        }
        
        // Total général
        $this->setX($tabX);
        $this->setFont($this->getDefaultFont(), 'B');
        $this->cell($wLabel, 0, ($hasTax && !$noDisplayTax ? __("Total TTC") : __("Total")) . // __("Net") . 
                           ($b->isInvoice() ? ' ' . ($b->isCredit() ? __("à déduire") : __("à payer")) : ''), 
                           1, 0);
        $this->cell($wValue, 0, T::currencyFormat($noDisplayTax ? $totalHT : $totalTTC), 1, 1, 'R');
        $this->setFont($this->getDefaultFontLight(), '');
        
        // Franchise ne base de TVA : mention légale
        if ($b->getTaxFranchise()) {
            $this->setX($tabX);
            $this->setFontSize(8);
            $this->cell($wTotal, 0, __("TVA non applicable, article 293 B du C.G.I."), 0, 1, 'C');
            $this->setFontSize($c->getFontSize());
        }
        
        // Facturation HT : mention légale
        else if ($noDisplayTax && $b->getNoTaxArticle()) {
            $this->setX($tabX);
            $this->setFontSize(8);
            $this->cell($wTotal, 0, $b->getNoTaxArticle(), 0, 1, 'C');
            $this->setFontSize($c->getFontSize());
        }
        
        // QR Code
        $weight = max($this->cellHeight * 3, $this->getY() - $tabY);
        if ($b->getQrCode()) {
            $tabX = $tabX - $weight - self::BLOCK_MARGIN;
            $url = Router::getHttpHost() . '/check/' . $b->buildHash();
            $this->qrCode($url, $tabX, $tabY, $weight, $weight);
            $this->link($tabX, $tabY, $weight, $weight, $url);
        }

        // Informations de gauche : RIB ou emplacement signature ou notes
        $width = $tabX - $this->getMargins()['left'] - self::BLOCK_MARGIN;
        if ($b->hasRib()) {
            $this->writeBankDetails($b->getProvider()->getBankDetails(), $c, $tabY, $width, $tabX, $weight);
            $weight = $this->getY() - $tabY;
        } else {
            $this->writeLeftBox($b, $c, $tabY, $width, $weight);
        }
        
        // Rétablissement des coordonnées et du saut de page automatique
        $this->setXY($this->getMargins()['left'], $tabY + $weight + self::BLOCK_MARGIN);
        $this->setAutoPageBreak(true, self::FOOTER_MARGIN);
        
        $mdAfter = '';
        
        // Ajout de la TVA du client si HT
        if (!$b->getTaxFranchise() && !$b->getRecipient()->getChargeWithTax() && $b->getRecipient()->getTvaIntra()) {
            $mdAfter .= trim($b->getMdAfter() . "\n\n" . sprintf(__("TVA Intracommunautaire de %s : %s"), $b->getRecipient()->getComputedTitle(), $b->getRecipient()->getTvaIntra())) . "<br>";
        }

        // Informations sur le document lié
        $linkedCode = $b->getLinkedDocumentCode();
        if ($linkedCode) {
            if ($b->isCredit() && $b->isInvoice() && $b->getLinkedDocumentType() === $b::TYPE_INVOICE) {
                $mdAfter .= sprintf(__("Note de crédit pour remboursement de la facture %s."), Html::escape($linkedCode));
            } else {
                $mdAfter .= sprintf(__("%s basé%s sur %s %s."), 
                        $b->getTypeName(true), 
                        $b->isQuote() ? '' : 'e',
                        $b->getLinkedDocumentType() ? $b::getTypeNameFromType($b->getLinkedDocumentType(), false, $b::getPrefixesSingular()) : __("le document"),
                        Html::escape($linkedCode));
            }
            $mdAfter .= "<br>";
        }
        
        // Texte à insérer en fin de page
        $mdAfter .= $b->getMdAfter(true);
        if ($mdAfter) {
            $this->writeHTML($mdAfter, true, false, false, false, $b->getMdAfterAlign());
        }
        
        // Pénalités de retard
        if ($b->getPenaltyMention()) {
            $this->ln();
            [$cRatio, $fSize] = [$this->getCellHeightRatio(), $this->getFontSize()];
            $this->setCellHeightRatio(0.6);
            $this->setFontSize(7);
            $this->write(0, $b->getPenaltyMention() . "\n", '', false, 'J', true, 1);
            $this->setCellHeightRatio($cRatio);
            $this->setFontSize($fSize);
        }
    }
    
    /**
     * @param array $cols
     * @return $this
     */
    protected function writeLabels(array $cols)
    {
        $this->hr(null, self::COLORED_LINES);
        $this->setFont($this->getDefaultFont(), 'B');
        $yBegin = $this->getY();
        foreach ($cols as $col) {
            $this->cell($col['size'], 0, $col['label'], self::DEBUG_BORDER, false, $col['align']);
        }
        $this->ln();
        $this->cellHeight = $this->getY() - $yBegin + 2;
        return $this;
    }
    
    /**
     * @param InvoiceBean $b
     * @param InvoiceConfig $c
     * @param array $cols
     * @return $this
     */
    protected function writeProducts(InvoiceBean $b, InvoiceConfig $c, array $cols, bool $noDisplayTax)
    {
        /* @var $product ProductBean */
        foreach ($b->getProducts() as $product) {
            $this->startTransaction();
            while ($this->transactionInProgress()) {
                
                // Début de ligne
                $this->hr(null, self::COLORED_LINES);
                $y = $this->getY();
                $x = $this->getMargins()['left'];
                $maxY = $y;
                
                // Ecriture des colonnes
                foreach ($cols as $key => $col) {
                    $data = $this->getProductPart($product, $key, $noDisplayTax, $b->isCredit());
                    $this->setFont($key === 'code' ? 'courier' : $this->getDefaultFontLight(), '');
                    $lastX = $this->getX();
                    $this->multiCell($col['size'], 0, $data, self::DEBUG_BORDER, $col['align'], false, 1);
                    if ($key === 'title' && ($product->getComment() || $product->getDescription())) {
                        $br = $product->getDescription() && $product->getComment() ? '<br />' : '';
                        $comment = $product->getDescription(true) . $br . $product->getComment(true);
                        $this->setFontSize($c->getFontSize() - 1);
                        $this->writeHTMLCell($col['size'], 0, $lastX, $this->getY(), $comment, self::DEBUG_BORDER, 1);
                        $this->setFontSize($c->getFontSize());
                    }
                    $maxY = max($this->getY(), $maxY);
                    $x += $col['size'];
                    $this->setXY($x, $y);
                }
                
                // Si la ligne dépasse en bas, on change de page
                //$this->text(0, $this->getY(), '[' . round($maxY) . ' - ' . round($this->getPageHeight() - self::FOOTER_MARGIN) . ']');
                if ($maxY > ($this->getPageHeight() - self::FOOTER_MARGIN)) {
                    $this->rollbackTransaction();
                    $this->hr(null, self::COLORED_LINES);
                    $this->addPage();
                    $this->setY($this->getMargins()['top']);
                    $this->writeLabels($cols);
                    $maxY = $this->getY();
                }
                
                // Sinon on reste sur la page
                else {
                    $this->commitTransaction();
                    $this->setY($maxY);
                }
            }
        }
        $this->hr(null, self::COLORED_LINES);
        return $this;
    }
    
    /**
     * Recovers and formats an item in the given product 
     * @param ProductBean $product
     * @param string $key
     * @return string
     * @throws ArchException
     */
    protected function getProductPart(ProductBean $product, string $key, bool $hasTaxFranchise, bool $isCredit)
    {
        switch ($key) {
            case 'code' :
                return $product->getCode();
            case 'title' :
                return $product->getTitle();
            case 'quantity' :
                return $product->getQuantity() . $product->getUnit();
            case 'price' :
                return T::currencyFormat($product->getPriceHT(), null, false);
            case 'discount' :
                return $product->getDiscount() ? T::percentageFormat($product->getDiscount()) : '';
            case 'tax' :
                return $hasTaxFranchise ? '' : ((int) $product->getTax() ? T::percentageFormat($product->getTax(), true, 1) : '');
            case 'ttc' :
                $ttc = $hasTaxFranchise ? $product->getPriceHT() : $product->getPriceTTC();
                // $ttc = $isCredit ? -$ttc : $ttc;
                return T::currencyFormat($ttc, null, false);
            case 'tttc' :
                $tttc = $hasTaxFranchise ? $product->getTotalPriceHT() : $product->getTotalPriceTTC();
                $tttc = $isCredit ? -$tttc : $tttc;
                return T::currencyFormat($tttc, null, false);
            default :
                throw new ArchException('Unknown product key [' . $key . ']');
        }
    }
    
    protected function colsSum(array $cols)
    {
        $sum = 0;
        foreach ($cols as $col) {
            $sum += $col['size'];
        }
        return $sum;
    }
    
    /**
     * @param InvoiceBean $b
     * @param InvoiceConfig $c
     * @return $this
     */
    public function writeInvoiceHeader(InvoiceBean $b, InvoiceConfig $c)
    {
        // Entête
        $this->setFont($this->getDefaultFont(), 'B');
        $this->setFontSize(22);
        $x = $this->getPageWidth() - $this->getMargins()['left'] - 99;
        $this->setAbsXY($x, $this->getHeaderMargin() + 8);
        $this->cell(100, 8, T::toUpper(__($b->getType())), 0, 1, 'R', false);
        $this->setFont($this->getDefaultFont(), '');
        $this->setFontSize(16);
        $this->setX($x);
        $this->cell(100, 6, $b->getCode(), 0, 1, 'R', false);
        return $this;
    }
    
    /**
     * @param BankDetailsBean $bank
     * @param InvoiceConfig $c
     * @param int $y
     * @param int $width
     * @param int $weight
     * @return $this
     */
    protected function writeBankDetails(BankDetailsBean $bank, InvoiceConfig $c, float $y, float $width, float $tabX, float $weight)
    {
        $initialFontFamily = $this->getFontFamily();
        $labels = [
            __("ÉTAB.")     => 13,
            __("GUICHET")   => 13,
            __("N° COMPTE") => 20,
            __("CLÉ")       => 9,
        ];
        $widths = array_values($labels);
        $labelsSum = array_sum($widths);
        $labelW = 21;
        
        $this->setY($y);
        $this->setFont($this->getDefaultFont(), 'B', 8);
        $this->cell($width, 0, __("Coordonnées Bancaires"), 1, 1, 'C');
        $yBegin = $this->getY();
        
        // Titulaire, domiciliation, iban, bic
        $this->setFont($this->getDefaultFont(), '', 8);
        $this->cell($labelW, 0, __("Titulaire :"), 0, 0, 'R');
        $this->setFont('courier', '', 8);
        $this->cell($width - $labelW, 0, $bank->getAccountOwnerName(), 0, 1, 'L');
        $this->setY($this->getY() - 1);
        
        $this->setFont($this->getDefaultFont(), '', 8);
        $this->cell($labelW, 0, __("Domiciliation :"), 0, 0, 'R');
        $this->setFont('courier', '', 8);
        $this->cell($width - $labelW, 0, $bank->getDomiciliation(), 0, 1, 'L');
        $this->setY($this->getY() - 1);
        
        $this->setFont($this->getDefaultFont(), '', 8);
        $this->cell($labelW, 0, __("IBAN :"), 0, 0, 'R');
        $this->setFont('courier', '', 8);
        $this->cell($width - $labelW, 0, $bank->getIban(true), 0, 1, 'L');
        $this->setY($this->getY() - 1);
        
        $this->setFont($this->getDefaultFont(), '', 8);
        $this->cell($labelW, 0, __("BIC (SWIFT) :"), 0, 0, 'R');
        $this->setFont('courier', '', 8);
        $this->cell($width - $labelW, 0, $bank->getBic(), 0, 1, 'L');
        $yMid = $this->getY();
        
        // RIB
        $this->setFont($this->getDefaultFont(), '', 8);
        foreach ($labels as $label => $labW) {
            $cWidth = $labW;
            $this->cell($cWidth, 0, $label, 0, 0, 'C');
        }
        $this->setFont('courier', '', 8);
        $rib = $bank->getRibFrArray();
        $this->ln();
        $this->setY($this->getY() - 1);
        foreach ($widths as $index => $labW) {
            $cWidth = $labW;
            $this->cell($cWidth, 0, $rib[$index], 0, 0, 'C');
        }
        $this->ln();
        $yEnd = $this->getY();
        
        // Code bar du RIB
        $style = [
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 1,
            'vpadding' => 1,
            'fgcolor' => [0,0,0],
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'courier',
            'fontsize' => 5,
        ];
        $this->write1DBarcode($bank->getRibFr(), 'C128', $this->getMargins()['left'] + $labelsSum, $yMid, $width - $labelsSum, $yEnd - $yMid + 3, '', $style);
        
        // Décorations
        $this->line($this->getMargins()['left'], $yBegin, $this->getMargins()['left'], $yEnd);
        $this->line($this->getMargins()['left'] + $width, $yBegin, $this->getMargins()['left'] + $width, $yEnd);
        $this->line($this->getMargins()['left'], $yMid, $this->getMargins()['left'] + $width, $yMid);
        $this->line($this->getMargins()['left'], $yEnd, $this->getMargins()['left'] + $width, $yEnd);
        $this->line($this->getMargins()['left'] + $labelsSum, $yMid, $this->getMargins()['left'] + $labelsSum, $yEnd);
        
        // Notes
        $this->setFont($initialFontFamily, '', $c->getFontSize());
        $rectY = $y + $weight + 4;
        $rectWidth = $this->getPageWidth() - $tabX - $this->getMargins()['right'];
        $this->setXY($tabX, $rectY);
        $this->cell($rectWidth, 0, __("Notes :"), 0);
        $this->rect($tabX, $rectY, $rectWidth, $yEnd - $rectY);
        $this->setY($yEnd);
        
        return $this;
    }
    
    /**
     * @param InvoiceBean $b
     * @param InvoiceConfig $c
     * @param int $y
     * @param int $width
     * @param int $weight
     * @return $this
     */
    protected function writeLeftBox(InvoiceBean $b, InvoiceConfig $c, float $y, float $width, float $weight)
    {
        $this->rect($this->getMargins()['left'], $y, $width, $weight);
        $this->setFontSize(8);
        $txt = $b->isOrder() ? __("Signature client :") : __("Notes :");
        $this->text($this->getMargins()['left'], $y, $txt);
        $this->setFontSize($c->getFontSize());
        return $this;
    }
    
    /**
     * TCPDF output extension
     * @param string $name
     * @param string $dest
     * @return string
     */
    public function output($name = 'doc.pdf', $dest = 'I')
    {
        $this->buildInvoice();
        return parent::output($name, $dest);
    }
    
    public function __toString()
    {
        return $this->output();
    }
}
