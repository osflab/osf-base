<?php
namespace Osf\Db\Table;

/**
 * Table with fields
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 10 nov. 2013
 * @package osf
 * @subpackage db
 */
interface TableInterface
{
    /**
     * @return array
     */
    public function getFields();
    
    /**
     * @return string
     */
    public function getTableName();
}