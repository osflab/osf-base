<?php
namespace Osf\Application;

/**
 * Application plugin parent class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 26 sept. 2013
 * @package osf
 * @subpackage plugin
 */
class PluginAbstract
{
    public function beforeRoute() {}
    public function afterRoute() {}
    public function beforeDispatchLoop() {}
    public function afterDispatchLoop() {}
    public function beforeAction() {}
    public function afterAction() {}
}
