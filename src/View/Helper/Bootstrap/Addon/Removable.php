<?php
namespace Osf\View\Helper\Bootstrap\Addon;

/**
 * Removable element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Removable
{
    protected $removable = false;
    
    /**
     * Removable element
     * @param bool $trueOrFalse
     * @return $this
     */
    public function removable($trueOrFalse = true)
    {
        $this->removable = (bool) $trueOrFalse;
        return $this;
    }
    
    public function initRemovable(array $vars)
    {
        $this->removable(array_key_exists('removable', $vars) ? $vars['removable'] : false);
    }
}