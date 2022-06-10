<?php
namespace Osf\Stream\Yaml;

use Osf\Stream\Yaml;
use Osf\Test\Runner as OsfTest;

/**
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
        
        $expected = ['root' => ['a' => 'b', 'c' => 'd', 'e' => ['f', 'g']]];
        self::assertEqual(Yaml::parseFile(__DIR__ . '/test.yml'), $expected);
        
        return self::getResult();
    }
}
