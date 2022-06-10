<?php
namespace Osf\Pdf\Document\Bean\Addon;

use Osf\Pdf\Document\Bean\ContactBean;

/**
 * Provider trait
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
trait Provider
{
    /**
     * @var ContactBean
     */
    protected $provider;
    
    /**
     * @param ContactBean $contact
     * @return $this
     */
    public function setProvider(ContactBean $contact = null)
    {
        $this->provider = $contact;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasProvider(): bool
    {
        return (bool) $this->provider;
    }
    
    /**
     * @param bool $computeTitle
     * @return \Osf\Pdf\Document\Bean\ContactBean
     */
    public function getProvider(bool $computeTitle = true): ContactBean
    {
        if (!($this->provider instanceof ContactBean)) {
            $this->provider = new ContactBean();
        }
        if ($computeTitle) {
            $this->provider->computeAddressTitle();
        }
        return $this->provider;
    }
}
