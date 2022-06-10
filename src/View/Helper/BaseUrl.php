<?php
namespace Osf\View\Helper;

use Osf\Container\OsfContainer as Container;

/**
 * Base url using the router
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 */
class BaseUrl extends AbstractViewHelper
{
    /**
     * Base url from router
     * @param string $uri
     * @param bool $withHost
     * @return string
     */
    public function __invoke(string $uri = '', bool $withHost = false):string
    {
        return rtrim(Container::getRouter()->getBaseUrl($withHost), '/') . $uri;
    }
}