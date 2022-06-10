<?php
namespace Osf\View;

use Osf\Container\OsfContainer as Container;

/**
 * Static helpers super class
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 */
abstract class AbstractStaticHelper
{
    /**
     * @var \Osf\View\AbstractHelper
     */
    protected static $viewHelper;
    
    /**
     * Eventually sed by sons classes to set the viewHelper instance to use
     */
    protected static function init()
    {
    }
    
    /**
     * Set the viewHelper instance
     * @param \Osf\View\AbstractHelper $viewHelper
     */
    protected static function setViewHelper(AbstractHelper $viewHelper)
    {
        self::$viewHelper = $viewHelper;
    }
    
    /**
     * @return \Osf\View\AbstractHelper
     */
    protected static function getViewHelper()
    {
        if (!self::$viewHelper) {
            self::$viewHelper = Container::getViewHelper();
        }
        return self::$viewHelper;
    }
    
    /**
     * @param name $name
     * @param array $params
     * @param \Osf\View\AbstractHelper $viewHelper
     * @return mixed
     */
    protected static function callHelper($name, $params, AbstractHelper $viewHelper = null)
    {
        if (!$viewHelper) {
            $viewHelper = static::getViewHelper();
        }
        return $viewHelper->__call($name, $params);
    }
    
    /**
     * Set value to attached vue
     * @param string $key
     * @param mixed $value
     * @return \Osf\View\AbstractHelper
     */
    public static function set(string $key, $value)
    {
        return self::getViewHelper()->getView()->addValue($key, $value);
    }
    
    /**
     * get view value
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        return self::getViewHelper()->getView()->getValue($key);
    }
    
    /**
     * Test if a key exists
     * @param string $key
     * @return bool
     */
    public static function has(string $key):bool
    {
        return self::getViewHelper()->getView()->getValue($key) !== null;
    }
    
    /**
     * Get all view values
     * @return array
     */
    public static function getAll():array
    {
        return self::getViewHelper()->getView()->getValues();
    }
}
