<?php
namespace Osf\Session;

use Osf\Test\Runner as OsfTest;

/**
 * Admin menu test
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        $session = new AppSession();
        self::assert($session instanceof SessionInterface);
        
        $mock = new Mock();
        self::assert($mock instanceof SessionInterface);
        self::assert($mock->set('test', ['a' => 'b']));
        self::assertEqual($mock->get('test'), ['a' => 'b']);
        self::assertEqual($mock->getAll(), ['test' => ['a' => 'b']]);
        $mock->clean('test');
        self::assertEqual($mock->getAll(), []);
        
        return self::getResult();
    }
}

