<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Stream\Text as T;
use Osf\Exception\ArchException;
use Osf\Pdf\Document\Bean\BankDetailsBean;
use Osf\Bean\BeanHelper as BH;

/**
 * Corporation
 * 
 * [IN DEVELOPMENT]
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 3 nov. 2013
 * @package osf
 * @subpackage pdf
 */
class CompanyBean extends AbstractPdfDocumentBean
{
    protected $id;
    protected $uid;
    protected $title;
    protected $siret;
    protected $description;
    protected $tel;
    protected $fax;
    protected $email;
    protected $url;
    
    protected $intro;                // Statut + Capital ou N° enregistrement + préfecture
    protected $registration;         // RCS / Tribunal de commerce
    protected $chargeWithTax = true; // Facturer avec la TVA (true par défaut, à laisser true en cas de doute)
    protected $tvaIntra;             // TVA intracommunautaire, obligatoire si chargeWithTax est à false
    protected $ape;                  // Code APE (NAF)
    protected $bankDetails;          // BankDetailsBean
    
    /**
     * @var AddressBean
     */
    protected $addressBilling;

    /**
     * @var AddressBean
     */
    protected $addressDelivery;

    /**
     * @var ImageBean
     */
    protected $logo;
    
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
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param int $uid
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = (int) $uid;
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
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = T::strOrNull($title);
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param AddressBean|array $address
     * @return $this
     */
    public function setAddressBilling($address)
    {
        $this->address = self::buildAddress($address);
        return $this;
    }
    
    /**
     * @return AddressBean
     */
    public function getAddressBilling(): ?AddressBean
    {
        return $this->address;
    }

    /**
     * @param AddressBean|array $address
     * @return $this
     */
    public function setAddressDelivery($address)
    {
        $this->addressDelivery = self::buildAddress($address);
        return $this;
    }
    
    /**
     * @return AddressBean
     */
    public function getAddressDelivery(): ?AddressBean
    {
        return $this->addressDelivery;
    }

    /**
     * @param ImageBean|array $logo
     * @return $this
     */
    public function setLogo($logo)
    {
        if (is_string($logo)) {
            $logo = new ImageBean($logo);
        }
        if ($logo !== null && !($logo instanceof ImageBean)) {
            throw new ArchException('Company logo must be an image');
        }
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return ImageBean
     */
    public function getLogo(): ?ImageBean
    {
        return $this->logo;
    }

    /**
     * @param string $tel
     * @return $this
     */
    public function setTel($tel)
    {
        $this->tel = T::strOrNull($tel);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTel(): ?string
    {
        return $this->tel;
    }

    /**
     * @param string $fax
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = T::strOrNull($fax);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }
    
    
    
    /**
     * Transforme une adresse en AddressBean si ce n'est déjà fait
     * @param mixed $address
     * @return AddressBean|null
     * @throws ArchException
     */
    protected static function buildAddress($address): ?AddressBean
    {
        if (is_array($address)) {
            $address = (new AddressBean())->populate($address);
        }
        if (!($address instanceof AddressBean)) {
            throw new ArchException('Address must be and array or AddressBean');
        }
        return $address;
    }
    
    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = T::strOrNull($email);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = T::strOrNull($url);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
    
    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->companyDesc = T::strOrNull($description);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(bool $escape = false): ?string
    {
        return BH::filterContent($this->description, $escape);
    }
    
    /**
     * @param string|null $siret
     * @return $this
     */
    public function setSiret($siret)
    {
        $this->siret = T::strOrNull($siret);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    /**
     * @return string|null
     */
    public function getSiren(): ?string
    {
        return T::siretToSiren($this->siret);
    }
    
    /**
     * @param string|null $intro
     * @return $this
     */
    public function setIntro($intro)
    {
        $this->intro = T::strOrNull($intro);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIntro(bool $escape = false): ?string
    {
        return BH::filterContent($this->intro, $escape);
    }
    
    /**
     * Get address title or company name or firstname / lastname
     * @return string
     */
    public function getComputedTitle(bool $escape = false): string
    {
        if (!$this->getAddressBilling()) {
            $this->setAddressBilling(new AddressBean());
        }
        return $this->getAddressBilling()->getTitle($escape)
            ?? $this->getTitle($escape);
    }
    
    /**
     * Complète l'adresse avec le titre de la société et le nom du contact si besoin
     * @return $this
     */
    public function computeAddressTitle()
    {
        // Si l'address bean n'existe pas, on le crée.
        if ($this->getAddressBilling() === null) {
            $this->setAddressBilling([]);
        }
        
        // Titre : nom de la société ou nom du contact si celui de la société n'existe pas
        if (!$this->getAddressBilling() || !$this->getAddressBilling()->getTitle()) {
            $this->getAddressBilling()->setTitle($this->getComputedTitle());
        }

        // Ajout du nom de contact dans l'adresse si le titre est le nom de la société
//        if ($this->getTitle() && ($fn = $this->getComputedFullname())) {
//            $this->getAddress()->setAddressIntro($fn);
//        }

        return $this;
    }
    
    /**
     * RCS / Tribunal de commerce
     * @param string|null $registration
     * @return $this
     */
    public function setRegistration($registration)
    {
        $this->registration = T::strOrNull($registration);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistration(): ?string
    {
        return $this->registration;
    }
    
    /**
     * @param string|null $tvaIntra
     * @return $this
     */
    public function setTvaIntra($tvaIntra)
    {
        $this->tvaIntra = T::strOrNull($tvaIntra);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTvaIntra(): ?string
    {
        return $this->tvaIntra;
    }
    
    /**
     * Code NAF (APE)
     * @param string|null $ape
     * @return $this
     */
    public function setApe(?string $ape)
    {
        $this->ape = T::strOrNull($ape);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApe(): ?string
    {
        return $this->ape;
    }
    
    /**
     * Coordonnées bancaires
     * @param BankDetailsBean $bankDetails
     * @return $this
     */
    public function setBankDetails(BankDetailsBean $bankDetails)
    {
        $this->bankDetails = $bankDetails;
        return $this;
    }
    
    /**
     * Coordonnées bancaires
     * @return BankDetailsBean
     */
    public function getBankDetails(): BankDetailsBean
    {
        if ($this->bankDetails === null) {
            $this->setBankDetails(new BankDetailsBean());
        }
        
        return $this->bankDetails;
    }
    
    /**
     * @param bool $chargeWithTax
     * @return $this
     */
    public function setChargeWithTax($chargeWithTax = true)
    {
        $this->chargeWithTax = (bool) $chargeWithTax;
        return $this;
    }

    /**
     * @return bool
     */
    public function getChargeWithTax(): bool
    {
        return $this->chargeWithTax !== false;
    }
}
