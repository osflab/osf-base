<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Pdf\Document\Config\BaseDocumentConfig;
use Osf\Pdf\Document\Bean\BaseDocumentBean;
use Osf\Pdf\Document\Config\InvoiceConfig;
use Osf\Exception\ArchException;
use Osf\Bean\BeanHelper as BH;
use Osf\Helper\DateTime as DT;
use Osf\Stream\Text as T;
use DateTime;

/**
 * Invoice bean
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class InvoiceBean extends BaseDocumentBean
{
    use Addon\Products;
    use Addon\Description;
    
    const TYPE_ALL      = 'invoices';
    
    const TYPES = [
         self::TYPE_QUOTE  ,
         self::TYPE_ORDER  ,
         self::TYPE_INVOICE,
    ];
    
    const VALIDITY_TYPE_EMPTY    = 'no';
    const VALIDITY_TYPE_CASH     = 'cash';
    const VALIDITY_TYPE_DELIVERY = 'delivery';
    const VALIDITY_TYPE_DELAY    = 'delay';
    const VALIDITY_TYPE_FM45     = 'fm45';
    const VALIDITY_TYPE_45FM     = '45fm';
    const VALIDITY_TYPE_PERIODIC = 'periodic';
    
    const VALIDITY_TYPES = [
        self::VALIDITY_TYPE_EMPTY   ,
        self::VALIDITY_TYPE_CASH    ,
        self::VALIDITY_TYPE_DELIVERY,
        self::VALIDITY_TYPE_DELAY   ,
        self::VALIDITY_TYPE_FM45    ,
        self::VALIDITY_TYPE_45FM    ,
        self::VALIDITY_TYPE_PERIODIC,
    ];
    
    const DISPLAY_TAX_AUTO = 'auto';
    const DISPLAY_TAX_NO   = 'no';
    const DISPLAY_TAX_YES  = 'yes';
    
    const DISPLAY_TAX = [
        self::DISPLAY_TAX_AUTO,
        self::DISPLAY_TAX_NO,
        self::DISPLAY_TAX_YES,
    ];
    
    /* FR : cash: Comptant (à la facturation)
       delivery: A la livraison
       delay: Jours calendaires (60 par défaut, ou négocié)
       fm45: Fin de mois + 45 jours
       45fm: 45 jours + fin de mois
       period: Périodique (45 jours après émission) */
    
    protected static $icons  = [];
    protected static $colors = [];
    
    protected $code; // Billing code (unique)
    protected $codeAuto = false; // Autoincrement ?
    protected $type; // Default = 'invoice'
    protected $mdBefore; // Introduction
    protected $mdAfter; // At the end of the document
    protected $mdBeforeAlign = 'J'; // Introduction aligment
    protected $mdAfterAlign  = 'C'; // Conclusion alignment
    protected $taxFranchise  = false; // VAT (Franchise en base de TVA) : https://www.service-public.fr/professionnels-entreprises/vosdroits/F21746
    protected $noTaxArticle; // Legal article to mention in case of tax-free billing
    protected $displayTax = self::DISPLAY_TAX_AUTO; // If not auto, display or not VAT
    protected $penaltyMention; // late penalties
    
    protected $qrCode = true; // Generate qrcode
    protected $credit = false; // Is a credit note
    
    protected $linkedDocumentCode; // Code of the linked document (credit, or model)
    protected $linkedDocumentType; // Type of the linked document (invoice, order, quote...)
    
    protected $dateSending;
    protected $dateValidity;
    protected $validityType = self::VALIDITY_TYPE_EMPTY;

    public function __construct(string $type = self::TYPE_INVOICE)
    {
        $this->setType($type);
        $this->setStatus(self::STATUS_CREATED);
    }
    
    /**
     * @param $code string|null
     * @return $this
     */
    public function setCode($code, $appendPrefix = false)
    {
        $this->code = $code === null ? null : (string) $code;
        if ($this->code !== null && $appendPrefix) {
            $this->code = static::buildCode($this->code, $this->getType());
        }
        return $this;
    }
    
    /**
     * Document code build/preview by default: "[DCF]xxxx". 
     * @param string $codeNo
     * @param string $type
     * @return string
     */
    public static function buildCode($codeNo, string $type): string
    {
        $prefixes = [
            self::TYPE_INVOICE => __("F"),
            self::TYPE_ORDER   => __("C"),
            self::TYPE_QUOTE   => __("D")
        ];
        return vsprintf("%s%'04s", [$prefixes[$type], $codeNo]);
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * @param $codeAuto bool
     * @return $this
     */
    public function setCodeAuto($codeAuto = true)
    {
        $this->codeAuto = (bool) $codeAuto;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCodeAuto(): bool
    {
        return (bool) $this->codeAuto;
    }
    
    /**
     * @return $this
     */
    public function setType($type)
    {
        if (!in_array($type, self::TYPES)) {
            throw new ArchException('Unknown type [' . $type . ']');
        }
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
    
    public function isInvoice()
    {
        return $this->getType() === self::TYPE_INVOICE;
    }
    
    public function isOrder()
    {
        return $this->getType() === self::TYPE_ORDER;
    }
    
    public function isQuote()
    {
        return $this->getType() === self::TYPE_QUOTE;
    }
    
    /**
     * Shown name (invoice => Facture)
     * @param bool $ucFirst
     * @param array $prefixes
     * @return string|null
     */
    public function getTypeName(bool $ucFirst = false, array $prefixes = [], bool $plural = false, bool $displayInvoiceIfCredit = false): ?string
    {
        if (!$this->type) {
            return null;
        }
        $isCredit = $displayInvoiceIfCredit ? false : $this->isCredit();
        return self::getTypeNameFromType($this->type, $ucFirst, $prefixes, $plural, $isCredit);
    }
    
    /**
     * @param string|null $mdBefore
     * @param string|null $align
     * @return $this
     */
    public function setMdBefore(?string $mdBefore, ?string $align = null)
    {
        $this->mdBefore = $mdBefore === null ? null : (string) $mdBefore;
        $align === null || $this->setMdBeforeAlign($align);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMdBefore(bool $compute = false): ?string
    {
        return BH::filterMarkdownContent($this->mdBefore, $compute);
    }
    
    /**
     * @param string|null $mdAfter
     * @param string|null $align
     * @return $this
     */
    public function setMdAfter(?string $mdAfter, ?string $align = null)
    {
        $this->mdAfter = $mdAfter === null ? null : (string) $mdAfter;
        $align === null || $this->setMdAfterAlign($align);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getMdAfter(bool $compute = false): ?string
    {
        return BH::filterMarkdownContent($this->mdAfter, $compute);
    }
    
    /**
     * @param $mdBeforeAlign string
     * @return $this
     */
    public function setMdBeforeAlign($mdBeforeAlign)
    {
        $this->checkAlign($mdBeforeAlign);
        $this->mdBeforeAlign = (string) $mdBeforeAlign;
        return $this;
    }

    /**
     * @return string
     */
    public function getMdBeforeAlign(): string
    {
        return $this->mdBeforeAlign;
    }
    
    /**
     * @param $mdAfterAlign string
     * @return $this
     */
    public function setMdAfterAlign(string $mdAfterAlign)
    {
        $this->checkAlign($mdAfterAlign);
        $this->mdAfterAlign = (string) $mdAfterAlign;
        return $this;
    }

    /**
     * @return string
     */
    public function getMdAfterAlign(): string
    {
        return $this->mdAfterAlign;
    }
    
    protected function checkAlign(string $align)
    {
        if (!in_array($align, ['', 'L', 'C', 'R', 'J'])) {
            throw new ArchException('Bad alignment value [' . $align . ']');
        }
    }
    
    /**
     * Invoice configuration
     * @see \Osf\Pdf\Document\Bean\BaseDocumentBean::setConfig()
     * @return \Osf\Pdf\Document\Bean\InvoiceBean
     */
    public function setConfig(BaseDocumentConfig $config)
    {
        if (!($config instanceof InvoiceConfig)) {
            throw new ArchException('Letter bean must be configured by a LetterConfig class instance');
        }
        parent::setConfig($config);
        return $this;
    }
    
    /**
     * @see \Osf\Pdf\Document\Bean\BaseDocumentBean::getConfig()
     * @return \Osf\Pdf\Document\Config\InvoiceConfig
     */
    public function getConfig()
    {
        if (!$this->config) {
            $this->setConfig(new InvoiceConfig());
        }
        return parent::getConfig();
    }
    
    /**
     * @param $qrCode bool
     * @return $this
     */
    public function setQrCode($qrCode = true)
    {
        $this->qrCode = (bool) $qrCode;
        return $this;
    }

    /**
     * @return bool
     */
    public function getQrCode(): bool
    {
        return $this->qrCode;
    }
    
    /**
     * @param bool $credit
     * @return $this
     */
    public function setCredit($credit = true)
    {
        $this->credit = (bool) $credit;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCredit(): bool
    {
        return $this->credit;
    }
    
    /**
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->getCredit();
    }
    
    /**
     * @param string|null $linkedDocumentCode
     * @return $this
     */
    public function setLinkedDocumentCode(?string $linkedDocumentCode)
    {
        
        $this->linkedDocumentCode = $linkedDocumentCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLinkedDocumentCode(): ?string
    {
        return $this->linkedDocumentCode;
    }
    
    /**
     * @param string|null $linkedDocumentType
     * @return $this
     */
    public function setLinkedDocumentType(?string $linkedDocumentType)
    {
        $this->linkedDocumentType = $linkedDocumentType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLinkedDocumentType(): ?string
    {
        return $this->linkedDocumentType;
    }
    
    /**
     * @return $this
     */
    public function setDateSending($date = null)
    {
        $this->dateSending = DT::buildDate($date);
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateSending(): DateTime
    {
        if (!$this->dateSending) {
            $this->setDateSending('now');
        }
        return $this->dateSending;
    }
    
    /**
     * @return $this
     */
    public function setDateValidity($date)
    {
        $this->dateValidity = !$date ? null : DT::buildDate($date);
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateValidity(): ?DateTime
    {
        return $this->dateValidity;
    }
    
    /**
     * @param string $validityType
     * @return $this
     */
    public function setValidityType(string $validityType)
    {
        if (!in_array($validityType, self::VALIDITY_TYPES)) {
            throw new ArchException('Unknown validity type [' . $validityType . ']');
        }
        $this->validityType = $validityType;
        return $this;
    }

    /**
     * @return string
     */
    public function getValidityType(): string
    {
        return $this->validityType;
    }
    
    /**
     * @return string
     */
    public function getComputedValidity(): string
    {
        if ($this->getDateValidity()) {
            return T::formatDate($this->getDateValidity());
        }
        return '';
    }
    
    /**
     * @param string|null $noTaxArticle
     * @return $this
     */
    public function setNoTaxArticle(?string $noTaxArticle)
    {
        $this->noTaxArticle = $noTaxArticle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNoTaxArticle(): ?string
    {
        return $this->noTaxArticle;
    }
    
    /**
     * @param string $displayTax
     * @return $this
     */
    public function setDisplayTax(string $displayTax)
    {
        if (!in_array($displayTax, self::DISPLAY_TAX)) {
            throw new ArchException('Display tax option not correct [' . $displayTax . ']');
        }
        $this->displayTax = $displayTax;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayTax(): string
    {
        return $this->displayTax;
    }
    
    /**
     * @param string $penaltyMention
     * @return $this
     */
    public function setPenaltyMention(string $penaltyMention)
    {
        $this->penaltyMention = $penaltyMention;
        return $this;
    }

    /**
     * @return string
     */
    public function getPenaltyMention(): string
    {
        return $this->penaltyMention;
    }
    
    //----------------------------------------------------------------------------------------
    // Calculs
    //----------------------------------------------------------------------------------------
    
    /**
     * @param $taxFranchise bool
     * @return $this
     */
    public function setTaxFranchise($taxFranchise = true)
    {
        $this->taxFranchise = (bool) $taxFranchise;
        return $this;
    }

    /**
     * @return bool
     */
    public function getTaxFranchise():bool
    {
        return $this->taxFranchise;
    }
    
    /**
     * Total with tax without discount
     * @param bool $invertIfCredit
     * @return float
     */
    public function getTotalTtcWithoutDiscount(bool $invertIfCredit = false): float
    {
        $total = 0;
        /* @var $product \Osf\Pdf\Document\Bean\ProductBean */
        foreach ($this->getProducts() as $product) {
            $total += $product->getPriceTTC() * $product->getQuantity();
        }
        return $invertIfCredit ? $this->invertIfCredit($total) : $total;
    }
    
    /**
     * Total without tax without discount
     * @param bool $invertIfCredit
     * @return float
     */
    public function getTotalHtWithoutDiscount(bool $invertIfCredit = false): float
    {
        $total = 0;
        /* @var $product \Osf\Pdf\Document\Bean\ProductBean */
        foreach ($this->getProducts() as $product) {
            $total += $product->getPriceHT() * $product->getQuantity();
        }
        return $invertIfCredit ? $this->invertIfCredit($total) : $total;
    }
    
    /**
     * Total with tax with discount
     * @param bool $invertIfCredit
     * @return float
     */
    public function getTotalTtcWithDiscount(bool $invertIfCredit = false): float
    {
        $total = 0;
        /* @var $product \Osf\Pdf\Document\Bean\ProductBean */
        foreach ($this->getProducts() as $product) {
            $total += $product->getPriceWithDiscountTTC() * $product->getQuantity();
        }
        return $invertIfCredit ? $this->invertIfCredit($total) : $total;
    }
    
    /**
     * Total without tax with discount
     * @param bool $invertIfCredit
     * @return float
     */
    public function getTotalHtWithDiscount(bool $invertIfCredit = false): float
    {
        $total = 0;
        /* @var $product \Osf\Pdf\Document\Bean\ProductBean */
        foreach ($this->getProducts() as $product) {
            $total += $product->getPriceWithDiscountHT() * $product->getQuantity();
        }
        return $invertIfCredit ? $this->invertIfCredit($total) : $total;
    }
    
    protected function invertIfCredit(float $total): float
    {
        return $this->isCredit() ? -$total : $total;
    }
    
    /**
     * True if it's an invoice and bank details of the provider are fully informed
     * FR: True si c'est une facture et que les coordonnées bancaires de l'expéditeur sont complètes
     * @return bool
     */
    public function hasRib(): bool
    {
        return $this->isInvoice() && $this->getProvider()->getBankDetails()->isFull();
    }
    
    //----------------------------------------------------------------------------------------
    // Méthodes statiques utilitaires
    //----------------------------------------------------------------------------------------
    
    /**
     * Legible name (invoice => Facture), static variable usable outside the bean
     * @param string $type
     * @param bool $ucFirst
     * @param array $prefixes
     * @return string
     */
    public static function getTypeNameFromType(string $type, bool $ucFirst = false, array $prefixes = [], bool $plural = false, bool $isCredit = false)
    {
        $names = $plural ? [
            self::TYPE_QUOTE    => __("devis"),
            self::TYPE_ORDER    => __("commandes"),
            self::TYPE_INVOICE  => $isCredit ? __("factures d'avoir") : __("factures"),
        ] : [
            self::TYPE_QUOTE    => __("devis"),
            self::TYPE_ORDER    => __("commande"),
            self::TYPE_INVOICE  => $isCredit ? __("note de crédit") : __("facture"),
        ];
        $name = $names[$type];
        $name = $ucFirst ? T::ucFirst($name) : $name;
        $prefix = isset($prefixes[$type]) ? $prefixes[$type] . ' ' : '';
        return $prefix . $name;
    }
    
    /**
     * Type relative color
     * @param string $type
     * @return string
     */
    public static function getColorFromType(string $type)
    {
        return isset(self::$colors[$type]) 
            ? self::$colors[$type] 
            : (isset(self::COLORS[$type]) 
                ? self::COLORS[$type] 
                : 'default');
    }
    
    /**
     * Type relative color
     * @param string $type
     * @return string
     */
    public static function getIconFromType(string $type): string
    {
        return isset(self::$icons[$type])
            ? self::$icons[$type] 
            : (isset(self::ICONS[$type]) 
                ? self::ICONS[$type] 
                : 'default');
    }
    
    public static function setTypeIcons(array $icons)
    {
        self::$icons = $icons;
    }
    
    public static function setTypeColors(array $colors)
    {
        self::$icons = $colors;
    }
    
    public static function getPrefixesSingular(): array
    {
        return  [
            self::TYPE_QUOTE    => __("le"),
            self::TYPE_ORDER    => __("la"),
            self::TYPE_INVOICE  => __("la"),
        ];
    }
    
    //----------------------------------------------------------------------------------------
    // Translate strings
    //----------------------------------------------------------------------------------------
    
    private function notused()
    {
        __("quote");
        __("order");
        __("invoice");
        __("created");
        __("sent");
        __("refused");
        __("canceled");
        __("signed");
        __("paid");
    }
}
