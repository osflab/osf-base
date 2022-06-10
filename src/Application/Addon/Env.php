<?php
namespace Osf\Application\Addon;

use Osf\Application\OsfApplication as Application;

/**
 * Environment management
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage application
 */
trait Env 
{
    /**
     * Get the current environment (production, test, demo or development)
     * @return string
     */
    public static function getApplicationEnv(): string
    {
        return defined('APPLICATION_ENV') ? APPLICATION_ENV : Application::ENV_PRD;
    }
    
    /**
     * Is this environment a production server ?
     * @return bool
     */
    public static function isProduction(): bool
    {
        return self::getApplicationEnv() === Application::ENV_PRD;
    }
    
    /**
     * Is this environment a development server ?
     * @return bool
     */
    public static function isDevelopment(): bool
    {
        return self::getApplicationEnv() === Application::ENV_DEV;
    }
    
    /**
     * Is this environment a staging (test) server ?
     * @return bool
     */
    public static function isStaging(): bool
    {
        return self::getApplicationEnv() === Application::ENV_STG;
    }
    
    /**
     * Is this environment a demo server ?
     * @return bool
     */
    public static function isDemo(): bool
    {
        return self::getApplicationEnv() === Application::ENV_DEM;
    }
}
