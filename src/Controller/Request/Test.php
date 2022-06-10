<?php
namespace Osf\Controller\Request;

use Osf\Container\OsfContainer;
use Osf\Test\Runner as OsfTest;

/**
 * Request unit test
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 sept. 2013
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();

        $request = OsfContainer::getRequest();
        self::assert($request instanceof \Osf\Controller\Request, 'Bad request object');
        self::assert(is_null($request->getController()), 'Controller defined ?');

        return self::getResult();
    }
}