<?php
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Table;
use Osf\Container\OsfContainer as Container;

/**
 * Is an ajax request, usefull to filter display
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class IsAjax extends AVH
{
    /**
     * true if an ajax request and if $ai specified, $ai = request['ai'] 
     * @param string $ai
     * @return bool
     */
    public function __invoke(string $ai = null):bool
    {
        $request = Container::getRequest();
        $aiValue = $request->getParam(Table::AI_KEY);
        return $aiValue && ($ai === null ? true : $ai == $aiValue);
    }
}