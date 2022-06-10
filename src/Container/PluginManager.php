<?php
namespace Osf\Container;

use Osf\Exception\ArchException;
use Osf\Application\PluginAbstract as ApplicationPlugin;

/**
 * Plugin manager
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 26 sept. 2013
 * @package osf
 * @subpackage container
 */
class PluginManager
{
    const PRIORITY_HIGH   = 2;
    const PRIORITY_NORMAL = 1;
    const PRIORITY_LOW    = 0;
    
    const STEP_BEFORE_ROUTE         = 'beforeRoute';
    const STEP_AFTER_ROUTE          = 'afterRoute';
    const STEP_BEFORE_DISPATCH_LOOP = 'beforeDispatchLoop';
    const STEP_AFTER_DISPATCH_LOOP  = 'afterDispatchLoop';
    const STEP_BEFORE_ACTION        = 'beforeAction';
    const STEP_AFTER_ACTION         = 'afterAction';
    
    protected static $plugins = array();
    protected static $pluginsOrder = array();
    
    /**
     * Register a MVC plugin
     * @param PluginAbstract $plugin
     * @param integer $priority
     * @throws ArchException
     */
    public static function registerApplicationPlugin(ApplicationPlugin $plugin, $priority = self::PRIORITY_NORMAL)
    {
        if ($priority != self::PRIORITY_HIGH 
                && $priority != self::PRIORITY_NORMAL 
                && $priority != self::PRIORITY_LOW) {
            throw new ArchException('Bad plugin priority');
        }
        $pluginClass = get_class($plugin);
        if (isset(self::$plugins[$pluginClass])) {
            throw new ArchException('Plugin ' . $pluginClass . ' already registered');
        }
        self::$plugins[$pluginClass] = $plugin;
        self::$pluginsOrder[$priority][] = $pluginClass;
    }
    
    /**
     * Get a registered plugin by class name
     * @param string $pluginClass
     * @return \Osf\Application\PluginAbstract
     */
    public static function getApplicationPlugin($pluginClass)
    {
        return isset(self::$plugins[$pluginClass]) ? self::$plugins[$pluginClass] : null;
    }
    
    /**
     * Used in Application core, please do not touch
     * @param string $step
     */
    public static function handleApplicationPlugins($step)
    {
        foreach (self::$pluginsOrder as $plugins) {
            foreach ($plugins as $pluginClass) {
                self::$plugins[$pluginClass]->$step();
            }
        }
    }
}
