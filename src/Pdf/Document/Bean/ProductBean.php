<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Exception\ArchException;
use Osf\Bean\BeanHelper as BH;
use Osf\Bean\AbstractBean;
use Osf\Stream\Text as T;
use Osf\Helper\Price;

/**
 * A product
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class ProductBean extends AbstractBean
{
    const TAX_DEFAULT  = 20;  // TVA 20%
    const TAX_MAX      = 100; // Taxe à 100%
    const DISCOUNT_MAX = 100; // % 0 = pas de discount, 100 = gratuit
    
    const PRICE_HT  = 'ht';
    const PRICE_TTC = 'ttc';
    
    const UNITS = [
        'fr' => [
            'd' => 'j',
        ]
    ];
    
    // Champs provenant de la base
    protected $id;  // Global ID
    protected $uid; // User product ID
    protected $title;
    protected $price;
    protected $priceType; // HT ou TTC 
    protected $code;
    protected $description;
    protected $tax = self::TAX_DEFAULT; // Pourcentage de taxe à ajouter au prix HT
    protected $discount = 0; // Pourcentage de remise à multiplier au prix HT
    protected $unit;
    protected $status = true; // Activé ou non
    
    // Champs additionnels
    protected $quantity = 1;
    protected $comment; // Commentaire additionel en MD
    protected $priceIsDefault = true; // Si false, c'est un prix modifié par 
                                      // rapport à celui de la base de données.
                                      // Si le prix n'est pas modifié dans le 
                                      // formulaire de création de factures, 
                                      // une modification ou duplicata de la 
                                      // facture ira prendre le nouveau prix 
                                      // du produit si celui-ci à changé
    protected $discountIsDefault = true; // Pareil pour la réduction
    
    
    /**
     * @param $id int|null
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id === null ? null : (int) $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @param $uid int|null
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = $uid === null ? null : (int) $uid;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUid(): ?int
    {
        return $this->uid;
    }
    
    /**
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = T::strOrNull($title);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    /**
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = (float) $price;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceHT()
    {
        if ($this->getPriceType() === self::PRICE_HT || $this->getTax() === 0) {
            return $this->price;
        }
        return Price::TtcToHt($this->price, $this->getTax(), true);
    }
    
    /**
     * @param $priceType string|null
     * @return $this
     */
    public function setPriceType($priceType)
    {
        $priceType = (string) $priceType;
        if (!in_array($priceType, ['ttc', 'ht'])) {
            throw new ArchException('Bad price type [' . $priceType . ']');
        }
        $this->priceType = $priceType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPriceType(): ?string
    {
        return $this->priceType;
    }
    
    /**
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = (string) $code;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }
    
    /**
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    /**
     * @param bool $compute
     * @return string|null
     */
    public function getDescription(bool $compute = false): ?string
    {
        return BH::filterMarkdownContent($this->description, $compute);
    }
    
    /**
     * @param $comment string|null
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = T::strOrNull($comment);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(bool $compute = false): ?string
    {
        return BH::filterMarkdownContent($this->comment, $compute);
    }
    
    /**
     * @param $quantity int|null
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity === null ? null : (int) $quantity;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }
    
    /**
     * @return $this
     */
    public function setDiscount($discount)
    {
        $discount = (float) $discount;
        if ($discount < 0 || $discount > self::DISCOUNT_MAX) {
            throw new ArchException('Bad discount value [' . $discount . ']');
        }
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }
    
    /**
     * @return $this
     */
    public function setTax($tax)
    {
        $tax = (float) $tax;
        if ($tax < 0 || $tax > self::TAX_MAX) {
            throw new ArchException('Max tax exceeded');
        }
        $this->tax = $tax;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTax(): ?float
    {
        return $this->tax;
    }
    
    /**
     * @param $unit string|null
     * @return $this
     */
    public function setUnit($unit)
    {
        $this->unit = $unit === null ? null : (string) $unit;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnit($lang = 'fr')
    {
        return isset(self::UNITS[$lang][$this->unit]) ? self::UNITS[$lang][$this->unit] : $this->unit;
    }
    
    /**
     * @param $status bool
     * @return $this
     */
    public function setStatus($status = true)
    {
        $this->status = (bool) $status;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * Part de la taxe dans le prix TTC à payer
     * @return float
     */
//    public function getShareOfTaxInThePrice()
//    {
//        // Pourcentage de taxe à la valeur HT à payer
//        return $this->getPriceWithDiscountHT() * ($this->getTax() / 100);
//    }

    /**
     * Price without tax with discount
     * @return float
     */
    public function getPriceWithDiscountHT()
    {
        // Retrait du pourcentage de remise au prix HT
        return Price::priceWithDiscount($this->getPriceHT(), $this->getDiscount(), true);
    }
    
    /**
     * @return float
     */
    public function getPriceTTC()
    {
        if ($this->getPriceType() === self::PRICE_TTC || $this->getTax() === 0) {
            return $this->price;
        }
        return Price::htToTtc($this->price, $this->getTax(), true);
    }

    /**
     * Price with tax, unitary
     * @return float
     */
    public function getPriceWithDiscountTTC()
    {
        return Price::priceWithDiscount($this->getPriceTTC(), $this->getDiscount(), true);
    }
    
    /**
     * Price with tax which take account of the quantity
     * @return float
     */
    public function getTotalPriceTTC()
    {
        return $this->getPriceWithDiscountTTC() * $this->getQuantity();
    }
    
    /**
     * Total price without tax which take account of the quantity
     * @return float
     */
    public function getTotalPriceHT()
    {
        return $this->getPriceWithDiscountHT() * $this->getQuantity();
    }
    
    /**
     * Checks if the type of price (tax or not) is defined. Otherwise we can't perform calculations
     * @throws ArchException
     */
    protected function checkPriceType()
    {
        if (!in_array($this->priceType, [self::PRICE_HT, self::PRICE_TTC])) {
            throw new ArchException('Price type is not defined. Unable to continue.');
        }
    }
    
    /**
     * @param $priceIsDefault bool
     * @return $this
     */
    public function setPriceIsDefault($priceIsDefault = true)
    {
        $this->priceIsDefault = (bool) $priceIsDefault;
        return $this;
    }

    /**
     * If it's the default price of a product (and not a specific price for a client)
     * @return bool
     */
    public function getPriceIsDefault(): bool
    {
        return $this->priceIsDefault;
    }
    
    /**
     * If it's the default discount of this product
     * @param $discountIsDefault bool
     * @return $this
     */
    public function setDiscountIsDefault($discountIsDefault = true)
    {
        $this->discountIsDefault = (bool) $discountIsDefault;
        return $this;
    }

    /**
     * This is the default discount of this product
     * @return bool
     */
    public function getDiscountIsDefault(): bool
    {
        return $this->discountIsDefault;
    }
}
