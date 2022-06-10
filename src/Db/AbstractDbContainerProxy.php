<?php
namespace Osf\Db;

use Osf\Container\AbstractContainer;

/**
 * Access to data models object containers
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 13 nov. 2013
 * @package osf
 * @subpackage db
 */
abstract class AbstractDbContainerProxy extends AbstractContainer
{
    /**
     * @param string $className
     * @return \Osf\Db\Table\AbstractTableGateway
     */
    protected static function buildTableObject($className)
    {
        Table\AbstractTableGateway::initSchemas();
        return self::buildObject($className);
    }
    
    /**
     * @param string $className
     * @param array $rowData
     * @param bool $rowExistsInDatabase
     * @return \Osf\Db\Row\AbstractRowGateway
     */
    protected static function buildRowObject($className, $rowData, $rowExistsInDatabase)
    {
        Table\AbstractTableGateway::initSchemas();
        $row = new $className();
        if ($rowData) {
            $row->populate($rowData, $rowExistsInDatabase);
        }
        return $row;
    }
}
