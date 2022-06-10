<?php
namespace Osf\Db\Addon;

use Osf\Exception\ArchException;
use Osf\Container\OsfContainer as C;

/**
 * DB schema management
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage db
 */
trait Schema
{
    protected $schema;
    protected $schemaKey; // admin ou common
    
    /**
     * @return string
     */
    public function getSchema()
    {
        if ($this->schema) {
            return $this->schema;
        }
        if ($this->schemaKey) {
            if (!isset(DB_SCHEMAS[$this->schemaKey])) {
                throw new ArchException('Schema key [' . $this->schemaKey . '] do not have correspondance in current app config.');
            }
            return DB_SCHEMAS[$this->schemaKey];
        }
        throw new ArchException('Schema is not specified in table class');
    }
    
    /**
     * Create DB_SCHEMAS const, used to get DB schemas names from keys
     * @return $this
     * @throws ArchException
     */
    public static function initSchemas()
    {
        if (!defined('DB_SCHEMAS')) {
            $config = C::getConfig()->getConfig('db');
            if (!$config || !is_array($config)) {
                throw new ArchException('No db information found in app config.');
            }
            $schemas = [];
            foreach ($config as $key => $info) {
                $schemas[$key] = $info['database'];
            }
            define('DB_SCHEMAS', $schemas);
        }
    }
}
