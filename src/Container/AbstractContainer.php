<?php
namespace Osf\Container;

use Osf\Exception\ArchException;
use Osf\Container\OsfContainer as Container;

/**
 * Object builder and manager for container classes
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 23 sept. 2013
 * @package osf
 * @subpackage container
 */
abstract class AbstractContainer
{
    // Note : properties are declared in subclasses in order to 
    // separate each context
    
    const MOCK_DISABLED = 'real';
    const MOCK_ENABLED  = 'mock';
    
    /**
     * Set the mock context in order to generate mock objects
     * Namespace "real" = no mock (production)
     * Namespace "mock" = mock (test)
     * @param string $namespace
     */
    public static function setMockNamespace(string $namespace): void
    {
        static::$mockNamespace = $namespace;
    }
    
    /**
     * Register a mock object
     * @param stdClass $object
     * @param string $className
     * @param array $args
     * @param string $namespace
     * @return void
     */
    public static function registerMock($object, string $className, array $args = [], string $namespace = 'default', string $mockNamespace = self::MOCK_ENABLED): void
    {
        static::$instances[$mockNamespace][$className][$namespace] = $object;
    }
    
    /**
     * Clean mock context
     * @return void
     */
    public static function cleanMocks(string $mockNamespace = self::MOCK_ENABLED): void
    {
        if (isset(static::$instances[$mockNamespace])) {
            unset(static::$instances[$mockNamespace]);
        }
    }
    
    /**
     * Build an object or get existing object from class name and serveral parameters
     * @param string $className class name with namespace
     * @param array $args construct arguments values
     * @param string $instanceName Name of the instance (x names = x instances)
     * @param string $beforeBuildBootstrapMethod method to call in bootstrap file before build the object
     * @param string $afterBuildBootstrapMethod method to call in bootstrap file after build the object
     * @return mixed
     * @throws ArchException
     */
    public static function buildObject(
            $className, 
            array $args = [], 
            $instanceName = 'default', 
            $beforeBuildBootstrapMethod = null, // Callback to call before building 
            $afterBuildBootstrapMethod = null   // Callback to call after building
    )
    {
        // Si l'objet n'est pas encore instancié...
        if (!isset(static::$instances[static::$mockNamespace][$className][$instanceName])) {
            
            // Appel du handler à exécuter avant instanciation pour 
            // effectuer les chargements nécessaires (lazy loading)
            if ($beforeBuildBootstrapMethod !== null) {
                $bootstrap = Container::getBootstrap();
                if (!is_callable([$bootstrap, $beforeBuildBootstrapMethod])) {
                    throw new ArchException("Bootstrap method $beforeBuildBootstrapMethod() must be declared in your bootstrap file");
                }
                $bootstrap->$beforeBuildBootstrapMethod();
            }

            // Instanciation
            $class = new \ReflectionClass($className);
            if (!$class->isInstantiable()) {
                throw new ArchException('Class ' . $className . ' must be instanciable');
            }
            static::$instances[static::$mockNamespace][$className][$instanceName]
                = count($args)
                ? $class->newInstanceArgs($args)
                : $class->newInstance();
            
            // Appel du handler à exécuter avant instanciation pour
            // effectuer les chargements nécessaires (lazy loading)
            if ($afterBuildBootstrapMethod !== null) {
                $bootstrap = Container::getBootstrap();
                if (!method_exists($bootstrap, $afterBuildBootstrapMethod)) {
                    throw new ArchException("Bootstrap method $afterBuildBootstrapMethod() must be declared in your bootstrap file");
                }
                $bootstrap->$afterBuildBootstrapMethod();
            }
        }
        
        // Envoi de l'instance
        return static::$instances[static::$mockNamespace][$className][$instanceName];
    }
    
    /**
     * Get all instances in the container
     * @return array
     */
    public static function getInstances()
    {
        return array_key_exists(static::$mockNamespace, static::$instances) ? static::$instances[static::$mockNamespace] : [];
    }
    
    /**
     * Bind to stream->ucfirst if installed
     * @param string $txt
     * @return string
     */
    protected static function ucFirst($txt): string
    {
        if (class_exists('\Osf\Stream\Text')) {
            return \Osf\Stream\Text::ucFirst($txt);
        }
        return ucfirst((string) $txt);
    }
}
