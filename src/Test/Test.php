<?php
namespace Osf\Test;

use Osf\Test\Runner as OsfTest;

/**
 * Test of the unit test component
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
        
        // Tests...
        try {
            self::assert(self::getResult() === true, 'Result must be true if no assertion');
            self::assertEqual(0, 0, 'Theses values must be equals');
        }
        
        // Displays exception
        catch (\Exception $e) {
            self::assertFalseException($e);
        }
        
        return self::getResult();
    }
}
