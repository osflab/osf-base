<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Bean\BeanHelper as BH;
use Osf\Bean\AbstractBean;

/**
 * Bank details
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 3 nov. 2013
 * @package osf
 * @subpackage pdf
 */
class BankDetailsBean extends AbstractBean
{
    protected $accountOwnerName; // Titulaire du compte
    protected $domiciliation;    // Domiciliation
    protected $iban;             // Code IBAN / RIB
    protected $bic;              // BIC (SWIFT)
    
    /**
     * @param string|null $accountOwnerName
     * @return $this
     */
    public function setAccountOwnerName(?string $accountOwnerName)
    {
        $this->accountOwnerName = $accountOwnerName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccountOwnerName(bool $escape = false): ?string
    {
        return BH::filterContent($this->accountOwnerName, $escape);
    }
    
    /**
     * @param string|null $domiciliation
     * @return $this
     */
    public function setDomiciliation(?string $domiciliation)
    {
        $this->domiciliation = $domiciliation;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDomiciliation(bool $escape = false): ?string
    {
        return BH::filterContent($this->domiciliation, $escape);
    }
    
    /**
     * @param string|null $iban
     * @return $this
     */
    public function setIban(?string $iban)
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * bool $format Formatte pour l'affichage
     * @return string|null
     */
    public function getIban(bool $format = false): ?string
    {
        if (!$format) {
            return $this->iban;
        }
        $txt = '';
        $iban = $this->iban;
        while ($iban) {
            $txt .= ' ' . substr($iban, 0, 4);
            $iban = substr($iban, 4);
        }
        return trim($txt);
    }
    
    /**
     * @param string|null $bic
     * @return $this
     */
    public function setBic(?string $bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBic(): ?string
    {
        return $this->bic;
    }
    
    /**
     * Les coordonnées bancaires sont-elles complètes ?
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->getAccountOwnerName()
            && $this->getDomiciliation()
            && $this->getIban()
            && $this->getBic();
    }
    
    public function getRibFr(): ?string
    {
        if (!$this->getIban()) {
            return null;
        }
        
        if (!preg_match('/^FR.{25}$/', $this->getIban(), $values)) {
            throw new ArchException('IBAN syntax not correct');
        }
        return substr($this->getIban(), 4);
    }
    
    /**
     * RIB décomposé : banque, guichet, compte, clé
     * @return array|null
     * @throws ArchException
     */
    public function getRibFrArray(): ?array
    {
        if (!$this->getIban()) {
            return null;
        }
        
        $values = [];
        if (!preg_match('/^FR.{2}(.{5})(.{5})(.{11})(.{2})$/', $this->getIban(), $values)) {
            throw new ArchException('IBAN syntax not correct');
        }
        array_shift($values);
        return $values;
    }
}
