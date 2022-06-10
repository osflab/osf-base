<?php
namespace Osf\Db\Row;

/**
 * Each row must implements this interface
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 9 nov. 2013
 * @package osf
 * @subpackage db
 */
interface RowGatewayInterface
{
    /**
     * @param string $field
     * @param mixed $value
     * @return RowGatewayInterface
     */
    public function set($field, $value);
    
    /**
     * @param string $field
     */
    public function get($field);
    
    /**
     * @return string
     */
    public function getSchema();
    
    /**
     * @return array
     */
    public function toArray();
    
//    /**
//     * @param array $data
//     * @param bool $rowExistsInDatabase
//     */
//    public function populate(array $data, $rowExistsInDatabase = false);
}