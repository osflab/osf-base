<?php
namespace Osf\Generator;

use Osf\Exception\ArchException;

/**
 * Autocall for auto generated class instanciation (without constructor params)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage generator
 */
abstract class AbstractBuilder
{
    protected static $classes = [];
    protected static $autoCallInstances = [];
    
    /**
     * @param string $class
     * @param array $args
     * @param bool $persistant
     * @return multitype
     */
    protected static function buildClass(string $class, array $args, bool $persistant)
    {
        if (!$persistant) {
            return self::newObject($class, $args);
        }
        if (!array_key_exists($class, self::$autoCallInstances)) {
            self::$autoCallInstances[$class] = self::newObject($class, $args);
        }
        return self::$autoCallInstances[$class];
    }
    
    /**
     * Return an instance of corresponding object
     * @param string $name
     * @param array $args
     * @param type $persistant
     * @throws \Osf\Exception\ArchException
     */
    public static function get(string $name, array $args = [], $persistant = false)
    {
        if (!is_array(static::$classes)) {
            throw new ArchException('A static property $classes with an array of classes must be declared in class [' . __CLASS__ . ']');
        }
        if (!array_key_exists($name, static::$classes)) {
            throw new ArchException('Unknown object [' . $name . ']');
        }
        return self::buildClass(static::$classes[$name], $args, $persistant);
    }
    
    /**
     * Get full class name from key
     * @param string $name
     * @return string
     * @throws \Osf\Exception\ArchException
     */
    public static function getClass(string $name)
    {
        if (!isset(static::$classes[$name])) {
            throw new ArchException('Unable to find class from key [' . $name . ']');
        }
        return static::$classes[$name];
    }
    
    /**
     * Instanciate a class dynamically
     * @param string $class
     * @param array $args
     * @return stdClass
     */
    public static function newObject(string $class, array $args = [], $callGetClass = false)
    {
        $class = $callGetClass ? static::getClass($class) : $class;
        $rc = new \ReflectionClass($class);
        return $rc->newInstanceArgs($args);
    }
}