<?php
namespace Osf\View;

use Osf\Test\Runner as OsfTest;
use Osf\Container\OsfContainer as Container;

/**
 * Default router unit tests
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        $view = Container::getView();
        self::assert($view instanceof \Osf\View\OsfView, 'Bad view type');
        self::assert(count($view->getValues()) == 0, 'View params not empty');
        $view->register(array('a' => 'b', 'c' => array('d', 'e')));
        self::assert(count($view->getValues()) == 2, 'Bad values count');
        $view->register(array('c' => 'Bonjour', 'd' => array('d', 'e')));
        self::assert(count($view->getValues()) == 3, 'Bad values count');
        
        $view = Container::getLayout(false);
        self::assert($view instanceof \Osf\View\OsfView, 'Bad layout type');
        self::assert(count($view->getValues()) == 0, 'Layout params not empty');
        $view->register(array('a' => 'b', 'c' => array('d', 'e')));
        self::assert(count($view->getValues()) == 2, 'Bad values count');
        $view->register(array('c' => 'Bonjour', 'd' => array('d', 'e')));
        self::assert(count($view->getValues()) == 3, 'Bad values count');
        
        // TODO: test render
        
        return self::getResult();
    }
}