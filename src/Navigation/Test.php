<?php
namespace Osf\Navigation;

use Osf\Test\Runner as OsfTest;

/**
 * Tests for navigation structure
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 25 sept. 2013
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        $menu = \Osf\Container\OsfContainer::getNavigationMenu('test');
        self::assert($menu instanceof \Osf\Navigation\Item, 'Menu not an item');
        
        $menu->buildChild('home')->setLabel('Accueil');
        $account = $menu->buildChild('ident')->setLabel('Identification');
        self::assert(count($menu) == 2, 'Count failed');
        
        $m = \Osf\Container\OsfContainer::getNavigationMenu('test');
        self::assert($m === $menu);
        self::assert(is_null($m->getParent()));
        
        $menu->rewind();
        self::assert($menu->current()->getLabel() == 'Accueil');
        self::assert($menu->next());
        self::assert($menu->current()->getLabel() == 'Identification');
        
        $account->buildChild('login')->setLabel('Login');
        $account->buildChild('logout')->setLabel('Logout');
        self::assert(count($account) == 2);
        self::assert($account->getParent() === $m);
        
        return self::getResult();
    }
}