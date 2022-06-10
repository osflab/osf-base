<?php
namespace Osf\Pdf\Document\Bean;

/**
 * Interface compatible with the corresponding helper
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 31 oct. 2013
 * @package osf
 * @subpackage pdf
 */
interface AddressBeanInterface
{
    public function getTitle();
    public function getAddress();
    public function getPostalCode();
    public function getCity();
    public function getCountry();
}