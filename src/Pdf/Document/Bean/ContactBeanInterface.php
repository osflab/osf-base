<?php
namespace Osf\Pdf\Document\Bean;

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
interface ContactBeanInterface
{
    public function getId();
    public function getFirstname();
    public function getLastname();
    public function getCivility();
    public function getFunction();
    public function getTel();
    public function getFax();
    public function getGsm();
    public function getUrl();
    public function getEmail();
    public function getCompanyName();
    
    public function getComputedFullname(bool $withCivility = true);
    public function getComputedTitle();
    
    /**
     * @return \Osf\Pdf\Document\Bean\AddressBean
     */
    public function getAddress();
    
    /**
     * @return \Osf\Pdf\Document\Bean\ImageBean
     */
    public function getCompanyLogo();
    
    /**
     * Complete the address with the company title and the contact name if needed
     * @return $this
     */
    public function computeAddressTitle();
}