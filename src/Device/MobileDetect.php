<?php
namespace Osf\Device;

use Osf\Container\OsfContainer as Container;

/**
 * Device detector and manager
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage device
 */
class MobileDetect extends \Mobile_Detect
{
    private $session;
    
    /**
     * @return Session
     */
    protected function getSession()
    {
        if ($this->session === null) {
            $this->session = Container::getSession('OSF_DEVICE');
        }
        return $this->session;
    }
    
    /**
     * @staticvar bool $currentDevice
     * @param string $userAgent
     * @param string $httpHeaders
     * @return bool
     */
    public function isMobile($userAgent = null, $httpHeaders = null)
    {
        if ($this->isTablet($userAgent, $httpHeaders)) {
            return false;
        }
        if (is_null($userAgent) && is_null($httpHeaders)) {
            $isMobile = $this->getSession()->get('mobile');
            if ($isMobile === null) {
                $isMobile = (bool) parent::isMobile();
                $this->getSession()->set('mobile', $isMobile);
                if ($isMobile) {
                    $this->getSession()->set('tablet', false);
                }
            }
            return $isMobile;
        }
        return (bool) parent::isMobile($userAgent, $httpHeaders);
    }
    
    /**
     * @staticvar bool $currentDevice
     * @param string $userAgent
     * @param string $httpHeaders
     * @return bool
     */
    public function isTablet($userAgent = null, $httpHeaders = null)
    {
        if (is_null($userAgent) && is_null($httpHeaders)) {
            $isTablet = $this->getSession()->get('tablet');
            if ($isTablet === null) {
                $isTablet = (bool) parent::isTablet();
                $this->getSession()->set('tablet', $isTablet);
                if ($isTablet) {
                    $this->getSession()->set('mobile', false);
                }
            }
            return $isTablet;
        }
        return parent::isTablet($userAgent, $httpHeaders);
    }
    
    /**
     * @return bool
     */
    public function isMobileOrTablet()
    {
        return $this->isMobile() || $this->isTablet();
    }
}
