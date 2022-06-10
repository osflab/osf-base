<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;

/**
 * Dropdown Menu Attachment
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Menu
{
    /**
     * @var DropDownMenu 
     */
    protected $menu = null;
    
    /**
     * Attach a dropdown menu
     * @param \Osf\View\Helper\Bootstrap\Addon\Addon\DropDownMenu $menu
     * @return $this
     */
    public function setMenu(DropDownMenu $menu)
    {
        if ($this->menu instanceof DropDownMenu) {
            Checkers::notice('A dropdown menu already exists. The old one will be delete.');
        }
        $this->menu = $menu;
        return $this;
    }
    
    /**
     * Attributes to add to attached element
     * @return array
     */
    public function getMenuAttributes():array
    {
        if ($this->menu !== null) {
            $attrs = [];
            if ($this->menu->getOrientationClass() == 'dropdown') {
                $attrs['id'] = $this->menu->getLabelledBy();
            }
            $attrs['data-toggle']   = 'dropdown';
            $attrs['aria-haspopup'] = 'true';
            $attrs['aria-expanded'] = 'false';
            return $attrs;
        }
        return [];
    }
    
    /**
     * Css classes to add to attached element
     * @return array
     */
    public function getMenuCssClasses():array
    {
        return $this->menu ? ['dropdown-toggle'] : [];
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initMenu(array $vars)
    {
        if (isset($vars['menu']) && ($vars['menu'] instanceof DropDownMenu)) {
            $this->setMenu($vars['menu']);
        } else {
            $this->menu = null;
        }
        return $this;
    }
}
