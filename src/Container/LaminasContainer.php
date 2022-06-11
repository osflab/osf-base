<?php
namespace Osf\Container;

use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter;
use Laminas\I18n\Translator\Translator;
use Osf\Container\OsfContainer as Container;
use Osf\Exception\ArchException;

/**
 * Laminas components builder and container
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 23 sept. 2013
 * @package osf
 * @subpackage container
 */
class LaminasContainer extends AbstractContainer
{
    protected static array $instances = [];
    protected static string $mockNamespace = self::MOCK_DISABLED;

    /**
     * @return Translator
     * @throws ArchException
     */
    public static function getTranslate($initTranslate = true)
    {
        $handler = $initTranslate ? 'initTranslate' : null;
        return self::buildObject('\Laminas\I18n\Translator\Translator', [], null, $handler);
    }

    /**
     * @return AuthenticationService
     * @throws ArchException
     */
    public static function getAuth()
    {
        return self::buildObject('\Laminas\Authentication\AuthenticationService');
    }
    
    /**
     * Configuration de la db dans la configuration de l'application
     * @return array
     * @throws ArchException
     */
    protected static function getDbConfig()
    {
        static $config = null;
        
        if (!$config) {
            $config = Container::getConfig()->getConfig('db');
            if (!is_array($config)) {
                throw new ArchException('Key db must be declared in application configuration');
            }
        }
        return $config;
    }

    /**
     * Type de BD (admin ou common) à partir du schéma
     * @staticvar array $keys
     * @param string $schema
     * @return string|null
     * @throws ArchException
     */
    protected static function getDbKey($schema)
    {
        static $keys = [];
        
        if ($schema === null) {
            return null;
        }
        if (!$keys) {
            foreach (self::getDbConfig() as $key => $conf) {
                $keys[$conf['database']] = $key;
            }
        }
        return $keys[$schema] ?? null;
    }

    /**
     * @param string|null $schema
     * @return Adapter
     * @throws ArchException
     */
    public static function getDbAdapter($schema = null)
    {
        $dbKey = self::getDbKey($schema);
        if ($schema && !$dbKey) {
            throw new ArchException('Key for db schema "' . $schema . '" not found in application configuration');
        }
        return self::getDbAdapterFromKey($dbKey);
    }

    /**
     * @param string $dbKey
     * @return Adapter
     * @throws ArchException
     */
    public static function getDbAdapterFromKey($dbKey = null)
    {
        $className = '\Laminas\Db\Adapter\Adapter';
        if ($dbKey === null) {
            $dbKey = (string) array_keys(Container::getConfig()->getConfig('db'))[0];
        }
        if (!isset(self::getInstances()[$className][$dbKey])) {
            if (!$dbKey) {
                throw new ArchException('Db key "' . $dbKey . '" not found in application configuration');
            }
            $options = ['options' => ['buffer_results' => true]];
            $config = [array_merge(self::getDbConfig()[$dbKey], $options)];
        } else {
            $config = [];
        }
        return self::buildObject($className, $config, $dbKey);
    }
}
