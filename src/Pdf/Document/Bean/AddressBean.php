<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Bean\BeanHelper as BH;

/**
 * Address bean
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 31 oct. 2013
 * @package osf
 * @subpackage pdf
 */
class AddressBean extends AbstractPdfDocumentBean implements AddressBeanInterface
{
    protected $id;
    protected $title;
    protected $addressIntro; // Ligne supplémentaire éventuelle avec le nom du contact
    protected $address;
    protected $postalCode;
    protected $city;
    protected $country;

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
     * @param bool $escape
     * @return string|null
     */
    public function getTitle(bool $escape = false): ?string
    {
        return $this->title === '' || $this->title === null ? null : BH::filterContent($this->title, $escape);
    }

    
    public function getAddress(bool $withIntro = true, string $separator = "\n", bool $escape = false): string
    {
        $intro = $withIntro && $this->addressIntro ? $this->addressIntro : '';
        $intro .= $this->addressIntro && $this->address ? $separator : '';
        return BH::filterContent(trim($intro . $this->address), $escape, true);
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param bool $removeCedex
     * @param bool $escape
     * @return string|null
     */
    public function getCity(bool $removeCedex = false, bool $escape = false): ?string
    {
        // try to remove CEDEX part
        if ($removeCedex) {
            return trim(preg_replace('/^(.+)cedex.*$/i', '$1', $this->city));
        }
        return BH::filterContent($this->city, $escape);
    }

    /**
     * @param bool $escape
     * @return string|null
     */
    public function getCountry(bool $escape = false): ?string
    {
        return BH::filterContent($this->country, $escape);
    }

    /**
     * @param string $title
     * @return \Osf\Pdf\Document\Bean\Address
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $address
     * @return \Osf\Pdf\Document\Bean\Address
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param int $postalCode
     * @return \Osf\Pdf\Document\Bean\Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @param string $city
     * @return \Osf\Pdf\Document\Bean\Address
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $country
     * @return \Osf\Pdf\Document\Bean\Address
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
    
    /**
     * Build formatted string address
     * @param bool $withTitle
     * @param bool $withCountry
     * @param string $separator
     * @return string
     */
    public function getComputedAddress(
            bool $withTitle   = false, 
            bool $withIntro   = true, 
            bool $withCountry = true, 
            string $separator = "\n",
            bool $escape = false): string
    {
        $address = $this->getAddress($withIntro);
        
        // @task [UNIX] pourquoi je me retrouve avec des \r\n ici ?
        if ($address && $separator !== "\n") {
            $address = str_replace(["\r\n", "\n"], [$separator, $separator], $address);
        }
        $fullAddress = $withTitle && $this->getTitle() ? $this->getTitle() . $separator : '';
        $fullAddress .= trim(($address ? $address . $separator : '')
                        . ($this->getPostalCode() ? $this->getPostalCode() . ' ' : '')
                        .  $this->getCity()
                        . ($withCountry && $this->getCountry() ? $separator . strtoupper($this->getCountry()) : ''));
        return BH::filterContent(trim($fullAddress), $escape, true);
    }
    
    /**
     * @param $addressIntro string|null
     * @return $this
     */
    public function setAddressIntro($addressIntro)
    {
        $this->addressIntro = $addressIntro === null ? null : (string) $addressIntro;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddressIntro(bool $escape = false): ?string
    {
        return BH::filterContent($this->addressIntro, $escape);
    }
    
    /**
     * Is the address content complete ?
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->getAddress(false) 
            && $this->getPostalCode() 
            && $this->getCity();
    }
    
    /**
     * If the content is not complete, return the field name to fill
     * @return string|null
     */
    public function getNotFullField(): ?string
    {
        return !$this->getAddress(false) ? 'address' : (!$this->getPostalCode() ? 'postal_code' : (!$this->getCity() ? 'city' : null));
    }
}