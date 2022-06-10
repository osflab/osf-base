<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Stream\Text as T;
use Osf\Exception\ArchException;
use Osf\Pdf\Document\Bean\BankDetailsBean;
use Osf\Bean\BeanHelper as BH;

/**
 * Physical person or corporation
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 3 nov. 2013
 * @package osf
 * @subpackage pdf
 */
class ContactBean extends AbstractPdfDocumentBean implements ContactBeanInterface
{
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $civility;
    protected $function;
    protected $tel;
    protected $fax;
    protected $gsm;
    protected $email;
    protected $url;
    
    protected $idCompany;
    protected $companyUid;
    protected $companyName;
    protected $companySiret;
    protected $companyDesc;
    protected $companyIntro; // Statut + Capital ou N° enregistrement + préfecture
    protected $companyTel;
    protected $companyFax;
    protected $companyEmail;
    protected $companyRegistration; // RCS / Tribunal de commerce
    protected $companyTvaIntra; // TVA Intracommunautaire
    protected $companyApe; // Code APE (NAF)
    
    protected $bankDetails; // BankDetailsBean
    
    /**
     * @var AddressBean
     */
    protected $address;

    /**
     * @var AddressBean
     */
    protected $addressDelivery;

    /**
     * @var ImageBean
     */
    protected $companyLogo;
    
    // TVA
    protected $chargeWithTax = true; // Facturer avec la TVA (true par défaut, à laisser true en cas de doute)
    protected $tvaIntra;             // TVA intracommunautaire, obligatoire si withTax est à false
    
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
     * @param $idCompany int|null
     * @return $this
     */
    public function setIdCompany($idCompany)
    {
        $this->idCompany = $idCompany === null ? null : (int) $idCompany;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdCompany()
    {
        return $this->idCompany;
    }
    
    /**
     * @param int $companyUid
     * @return $this
     */
    public function setCompanyUid(int $companyUid)
    {
        $this->companyUid = $companyUid;
        return $this;
    }

    /**
     * @return int
     */
    public function getCompanyUid(): int
    {
        return (int) $this->companyUid;
    }
    
    /**
     * @param bool $escape
     * @return string|null
     */
    public function getCompanyName(bool $escape = false): ?string
    {
        return $this->companyName === '' || $this->companyName === null ? null : BH::filterContent($this->companyName, $escape);
    }

    /**
     * @return AddressBean
     */
    public function getAddress(): ?AddressBean
    {
        return $this->address;
    }

    /**
     * @return AddressBean
     */
    public function getAddressDelivery(): ?AddressBean
    {
        return $this->addressDelivery;
    }

    /**
     * @return ImageBean
     */
    public function getCompanyLogo(): ?ImageBean
    {
        return $this->companyLogo;
    }

    /**
     * @param string $companyName
     * @return $this
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = T::strOrNull($companyName);
        return $this;
    }

    /**
     * @param AddressBean|array $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = self::buildAddress($address);
        return $this;
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
     * @param ImageBean|array $companyLogo
     * @return $this
     */
    public function setCompanyLogo($companyLogo)
    {
        if (is_string($companyLogo)) {
            $companyLogo = new ImageBean($companyLogo);
        }
        if ($companyLogo !== null && !($companyLogo instanceof ImageBean)) {
            throw new ArchException('Company logo must be an image');
        }
        $this->companyLogo = $companyLogo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(bool $escape = false): ?string
    {
        return BH::filterContent($this->firstname, $escape);
    }

    /**
     * @return string|null
     */
    public function getLastname(bool $escape = false): ?string
    {
        return BH::filterContent($this->lastname, $escape);
    }

    /**
     * @return string|null
     */
    public function getCivility(bool $fullWord = false)
    {
        $fullWords = [
            'm' => __("Monsieur"),
            'm.' => __("Monsieur"),
            'mr' => __("Monsieur"),
            'mme' => __("Madame"),
            'mlle' => __("Mademoiselle"),
            'dr' => __("Docteur")
        ];
        if ($fullWord) {
            $lc = strtolower($this->civility);
            if (isset($fullWords[$lc])) {
                return $fullWords[$lc];
            }
        }
        return $this->civility;
    }

    /**
     * @return string|null
     */
    public function getFunction(bool $escape = false): ?string
    {
        return BH::filterContent($this->function, $escape);
    }

    /**
     * @return string|null
     */
    public function getTel(): ?string
    {
        return $this->tel;
    }

    /**
     * @return string|null
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * @return string|null
     */
    public function getGsm(): ?string
    {
        return $this->gsm;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $function
     * @return $this
     */
    public function setFunction($function)
    {
        $this->function = T::strOrNull($function);
        return $this;
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
     * @param string $fax
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = T::strOrNull($fax);
        return $this;
    }

    /**
     * @param string $gsm
     * @return $this
     */
    public function setGsm($gsm)
    {
        $this->gsm = T::strOrNull($gsm);
        return $this;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname, bool $format = true)
    {
        $this->firstname = T::strOrNull($firstname);
        if ($format && $this->firstname) {
            $this->firstname = T::ucPhrase($this->firstname);
        }
        return $this;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname, bool $format = true)
    {
        $this->lastname = T::strOrNull($lastname);
        if ($format && $this->lastname) {
            $this->lastname = T::toUpper($this->lastname, true);
        }
        return $this;
    }

    /**
     * @param string $civility
     * @return $this
     */
    public function setCivility($civility)
    {
        $this->civility = T::strOrNull($civility);
        return $this;
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
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = T::strOrNull($url);
        return $this;
    }
    
    /**
     * Title = companyName
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setCompanyName($title);
    }

    public function getTitle()
    {
        return $this->getCompanyName();
    }
    
    /**
     * @param string|null $companyDesc
     * @return $this
     */
    public function setCompanyDesc($companyDesc)
    {
        $this->companyDesc = T::strOrNull($companyDesc);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyDesc(bool $escape = false): ?string
    {
        return BH::filterContent($this->companyDesc, $escape);
    }
    
    /**
     * @param string|null $companySiret
     * @return $this
     */
    public function setCompanySiret($companySiret)
    {
        $this->companySiret = T::strOrNull($companySiret);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanySiret(): ?string
    {
        return $this->companySiret;
    }

    /**
     * @return string|null
     */
    public function getCompanySiren(): ?string
    {
        return T::siretToSiren($this->companySiret);
    }
    
    /**
     * @param string|null $companyIntro
     * @return $this
     */
    public function setCompanyIntro($companyIntro)
    {
        $this->companyIntro = T::strOrNull($companyIntro);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyIntro(bool $escape = false): ?string
    {
        return BH::filterContent($this->companyIntro, $escape);
    }
    
    /**
     * @param string|null $companyTel
     * @return $this
     */
    public function setCompanyTel($companyTel)
    {
        $this->companyTel = T::strOrNull($companyTel);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyTel(): ?string
    {
        return $this->companyTel;
    }
    
    /**
     * @param string|null $companyFax
     * @return $this
     */
    public function setCompanyFax($companyFax)
    {
        $this->companyFax = $companyFax === null ? null : (string) $companyFax;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyFax(): ?string
    {
        return $this->companyFax;
    }
    
    /**
     * @param string|null $companyEmail
     * @return $this
     */
    public function setCompanyEmail($companyEmail)
    {
        $this->companyEmail = $companyEmail === null ? null : (string) $companyEmail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyEmail(): ?string
    {
        return $this->companyEmail;
    }
    
    /**
     * Build and return fullname
     * @param bool $withCivility
     * @return string
     */
    public function getComputedFullname(bool $withCivility = true, bool $escape = false): string
    {
        return trim(($withCivility && $this->getLastname() ? $this->getCivility() : '') . ' ' . 
                    $this->getFirstname($escape) . ' ' . 
                    $this->getLastname($escape));
    }
    
    /**
     * Get address title or company name or firstname / lastname
     * @return string
     */
    public function getComputedTitle(bool $escape = false): string
    {
        if (!$this->getAddress()) {
            $this->setAddress(new AddressBean());
        }
        return $this->getAddress()->getTitle($escape)
            ?? $this->getCompanyName($escape)
            ?? $this->getComputedFullname(true, $escape);        
//        if ($this->getAddress()->getTitle()) {
//            return $this->getAddress()->getTitle();
//        }
//        return $this->getCompanyName() . 
//              ($this->getCompanyName() && $this->getComputedFullname() ? "\n" : '') . 
//               $this->getComputedFullname();
    }
    
    /**
     * Nom ou "monsieur" ou "madame" ou "madame, monsieur" pour les entêtes de lettre
     * @return string
     */
    public function getComputedCivilityWithLastname(bool $escape = false): string
    {
        if (!$this->getCivility()) {
            return __("Madame, Monsieur");
        }
        if (!$this->getLastname($escape)) {
            return $this->getCivility(true);
        }
        return $this->getCivility(true) . ' ' . $this->getLastname($escape);
    }
    
    /**
     * Complète l'adresse avec le titre de la société et le nom du contact si besoin
     * @return $this
     */
    public function computeAddressTitle()
    {
        // Si l'address bean n'existe pas, on le crée.
        if ($this->getAddress() === null) {
            $this->setAddress([]);
        }
        
        // Titre : nom de la société ou nom du contact si celui de la société n'existe pas
        if (!$this->getAddress() || !$this->getAddress()->getTitle()) {
            $this->getAddress()->setTitle($this->getComputedTitle());
        }

        // Ajout du nom de contact dans l'adresse si le titre est le nom de la société
        if ($this->getCompanyName() && ($fn = $this->getComputedFullname())) {
            $this->getAddress()->setAddressIntro($fn);
        }

        return $this;
    }
    
    /**
     * Email du contact ou de la société selon priorité et disponibilité
     * @param bool $contactEmailIsPriority
     * @return string|null
     */
    public function getComputedEmail(bool $contactEmailIsPriority = false): ?string
    {
        return ($contactEmailIsPriority && $this->email) || !$this->companyEmail ? $this->email : $this->companyEmail;
    }
    
    /**
     * Tel du contact ou de la société selon priorité et disponibilité
     * @param bool $contactTelIsPriority
     * @return string|null
     */
    public function getComputedTel(bool $contactTelIsPriority = false): ?string
    {
        return ($contactTelIsPriority && $this->tel) || !$this->companyTel ? $this->tel : $this->companyTel;
    }
    
    /**
     * Fax du contact ou de la société selon priorité et disponibilité
     * @param bool $contactFaxIsPriority
     * @return string|null
     */
    public function getComputedFax(bool $contactFaxIsPriority = false): ?string
    {
        return ($contactFaxIsPriority && $this->fax) || !$this->companyFax ? $this->fax : $this->companyFax;
    }
    
    /**
     * RCS / Tribunal de commerce
     * @param string|null $companyRegistration
     * @return $this
     */
    public function setCompanyRegistration($companyRegistration)
    {
        $this->companyRegistration = $companyRegistration === null ? null : (string) $companyRegistration;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyRegistration(): ?string
    {
        return $this->companyRegistration;
    }
    
    /**
     * @param string|null $companyTvaIntra
     * @return $this
     */
    public function setCompanyTvaIntra(?string $companyTvaIntra)
    {
        $this->companyTvaIntra = $companyTvaIntra === null ? null : (string) $companyTvaIntra;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyTvaIntra(): ?string
    {
        return $this->companyTvaIntra;
    }
    
    /**
     * Code NAF (APE)
     * @param string|null $companyApe
     * @return $this
     */
    public function setCompanyApe(?string $companyApe)
    {
        $this->companyApe = $companyApe === null ? null : (string) $companyApe;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyApe(): ?string
    {
        return $this->companyApe;
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
    
    /**
     * @param string|null $tvaIntra
     * @return $this
     */
    public function setTvaIntra(?string $tvaIntra)
    {
        $this->tvaIntra = $tvaIntra;
        $this->companyTvaIntra = $tvaIntra;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTvaIntra(): ?string
    {
        return $this->companyTvaIntra ?? $this->tvaIntra;
    }
}
