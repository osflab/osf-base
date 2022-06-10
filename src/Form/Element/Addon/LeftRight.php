<?php
namespace Osf\Form\Element\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;

/**
 * Left and right icon/label addons
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage form
 */
trait LeftRight
{
    protected $leftAddon;
    protected $rightAddon;
    
    /**
     * @param string $label
     * @param string $icon
     * @return $this
     */
    public function setAddonLeft($label = null, $icon = null, array $attributes = [], array $cssClasses = [])
    {
        if ($label !== null || $icon !== null) {
            $icon !== null && Checkers::checkIcon($icon, null);
            $this->leftAddon = [$label, $icon, $attributes, $cssClasses];
        } else {
            $this->leftAddon = null;
        }
        return $this;
    }
    
    /**
     * @param string $label
     * @param string $icon
     * @return $this
     */
    public function setAddonRight($label = null, $icon = null, array $attributes = [], array $cssClasses = [])
    {
        if ($label !== null || $icon !== null) {
            $icon !== null && Checkers::checkIcon($icon, null);
            $this->rightAddon = [$label, $icon, $attributes, $cssClasses];
        } else {
            $this->rightAddon = null;
        }
        return $this;
    }
    
    /**
     * @return string
     */
    function getAddonLeft()
    {
        return $this->leftAddon;
    }
    
    /**
     * @return string
     */
    function getAddonRight()
    {
        return $this->rightAddon;
    }
}