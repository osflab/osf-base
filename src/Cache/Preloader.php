<?php
namespace Osf\Cache;

use Osf\Container\OsfContainer as C;

/**
 * Page pre-loader deamon
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage cache
 * @todo
 */
class Preloader
{
    const WAIT_PERIOD = 10000; // µs
    
    const QUEUE_HIGH = 'PLDH';
    const QUEUE_LOW  = 'PLDL';
    
    public static function launchDeamon()
    {
        while (true) {
            C::getCache()->getRedis()->lPop();
            usleep(10000);
            echo ".";
        }
    }
}
