<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;

/**
 * Trait element for helpers with status feature
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Status
{
    private   $statusIsNullable = false;
    private   $defaultStatus = null;
    protected $status = null;
    
    /**
     * Valid bootstrap status
     * @param string $status
     * @return $this
     */
    public function status($status)
    {
        if (!$this->statusIsNullable || $status !== null) {
            Checkers::checkStatus($status, null);
        }
        $this->status = $status;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function statusDefault() { return $this->status(AVH::STATUS_DEFAULT); }
    /**
     * @return $this
     */
    public function statusPrimary() { return $this->status(AVH::STATUS_PRIMARY); }
    /**
     * @return $this
     */
    public function statusSuccess() { return $this->status(AVH::STATUS_SUCCESS); }
    /**
     * @return $this
     */
    public function statusInfo()    { return $this->status(AVH::STATUS_INFO); }
    /**
     * @return $this
     */
    public function statusWarning() { return $this->status(AVH::STATUS_WARNING); }
    /**
     * @return $this
     */
    public function statusDanger()  { return $this->status(AVH::STATUS_DANGER); }
    
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status ?: $this->defaultStatus;
    }
    
    /**
     * @return $this
     */
    protected function statusIsNullable($trueOrFalse = true)
    {
        $this->statusIsNullable = (bool) $trueOrFalse;
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function statusSetDefault($status)
    {
        if ($status !== null && !(in_array($status, AVH::STATUS_LIST))) {
            throw new \Osf\Exception\ArchException('Bad default status [' . $status . ']');
        }
        $this->defaultStatus = $status;
        return $this;
    }
    
    protected function initStatus(array $vars)
    {
        $this->statusIsNullable(array_key_exists('statusIsNullable', $vars) ? $vars['statusIsNullable'] : false);
        $this->statusSetDefault(array_key_exists('defaultStatus', $vars) ? $vars['defaultStatus'] : null);
        if (isset($vars['status'])) {
            $this->status($vars['status']);
        } else {
            $this->status = null;
        }
    }
}
