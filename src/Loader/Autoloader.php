<?php
namespace Osf\Loader;

use Exception;

/**
 * Include once this file to launch autoload
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage loader
 */
class Autoloader
{
    /**
     * Old PSR4 autoloader
     * @param type $className
     * @return void
     * @throws Exception
     * @deprecated since version 3.0.0
     */
    public static function autoload($className): void
    {
        include strtr($className, '\\', DIRECTORY_SEPARATOR) . '.php';
        if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
            throw new Exception("Class/Interface/Trait file for '$className' not found.");
        }
    }
    
    /**
     * Launch the autoload mechanism
     * @return void
     */
    public static function register(): void
    {
        // OSF autoloader
        // spl_autoload_register(['\Osf\Loader\Autoloader', 'autoload']);
        $composerAutoloaderFile = realpath(__DIR__ . '/../../vendor/autoload.php');
        if ($composerAutoloaderFile) {
            include_once $composerAutoloaderFile;
        }
    }
}
