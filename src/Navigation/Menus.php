<?php
namespace Osf\Navigation;

use Osf\Navigation\Item;

/**
 * Simple navigation container
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 25 sept. 2013
 * @package osf
 * @subpackage navigation
 */
class Menus
{
    protected $menus = [];
    
    /**
     * @param string $menuId
     * @return \Osf\Navigation\Item
     */
    public function getNavigation($menuId)
    {
        if (!array_key_exists($menuId, $this->menus)) {
            $this->menus[$menuId] = new Item($menuId, 'root');
        }
        return $this->menus[$menuId];
    }
}