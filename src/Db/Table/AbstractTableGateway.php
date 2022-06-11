<?php
namespace Osf\Db\Table;

use Laminas\Db\TableGateway\AbstractTableGateway as ZendAbstractTableGateway;
use Laminas\Db\ResultSet\HydratingResultSet;
use Osf\Exception\ArchException;
use Osf\Db\Row\RowHydrator;
use Osf\Db\Addon\Schema;
use Osf\View\Table\TableDb;
use Osf\Container\LaminasContainer;
use Osf\Form\TableForm;
use Osf\Db\Select;

/**
 * OSF Parent class for generated tables in data models
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 9 nov. 2013
 * @package osf
 * @subpackage db
 */
class AbstractTableGateway extends ZendAbstractTableGateway implements TableGatewayInterface
{
    use Schema;
    
    protected $rowClass;
    protected $rowPrototype;

    /**
     * @return \Osf\Db\Row\RowHydrator
     */
    protected function getHydrator()
    {
        static $hydrator = null;
        
        if ($hydrator === null) {
            $hydrator = new RowHydrator();
        }
        return $hydrator;
    }
    
    /**
     * @see \Laminas\Db\TableGateway\AbstractTableGateway::initialize()
     */
    public function initialize()
    {
        if ($this->isInitialized) {
            return;
        }
        $this->resultSetPrototype = new HydratingResultSet($this->getHydrator(), $this->getRowPrototype());
        $this->adapter = ZendContainer::getDbAdapter($this->getSchema());
        return parent::initialize();
    }
    
    /**
     * @return \Osf\Db\Row\AbstractRowGateway
     * @throws ArchException
     */
    public function getRowPrototype()
    {
        if (!$this->rowPrototype) {
            if (!$this->rowClass) {
                throw new ArchException('$rowClass is not specified in table class');
            }
            $rowClass = $this->rowClass;
            $this->rowPrototype = new $rowClass();
        }
        return $this->rowPrototype;
    }
    
    /**
     * @see \Laminas\Db\TableGateway\AbstractTableGateway::getSql()
     * @return \Laminas\Db\Sql\Sql
     */
    public function getSql()
    {
        $this->initialize();
        return parent::getSql(); 
    }
    
    /**
     * @param int $id
     * @throws ArchException
     * @return \Osf\Db\Row\AbstractRowGateway
     */
    public function find($id)
    {
        if (is_numeric($id)) {
            $rows = $this->select(['id' => (int) $id]);
        }
        if (is_array($id)) {
            $rows = $this->select($id);
        }
        if (isset($rows)) {
            return $rows->current();
        }
        throw new ArchException('Bad id type');
    }
    
    /**
     * @param array $ids
     * @param array $fields
     * @task [DB] Get the primary key of current table, not only the 'id' field
     * @return \Laminas\Db\Adapter\Driver\Mysqli\Result
     */
    public function findIds(array $ids, array $fields = [])
    {
        // Filtrage et validation des ids
        $filteredIds = [];
        foreach ($ids as $id) {
            if (!is_numeric($id)) {
                throw new ArchException('Not a numeric id [' . $id . ']');
            }
            $filteredIds[] = (int) $id;
        }
        $strIds = implode(',', $filteredIds);
        
        // Filtrage et validation des tables
        foreach ($fields as $field) {
            if (!preg_match('/^[a-zA-Z_-]+$/', $field)) {
                throw new ArchException('Bad table syntax [' . $field . ']');
            }
        }
        $fields = $fields ? implode(', ', $fields) : '*';
        
        // Requête
        $sql = 'SELECT ' . $fields . ' '
                . 'FROM ' . $this->table . ' '
                . 'WHERE id IN (' . $strIds . ')';
        return $this->prepare($sql)->execute();
    }
    
    /**
     * @return \Osf\Form\TableForm
     */
    public function getForm()
    {
        return new TableForm($this);
    }
    
    /**
     * @return \Osf\View\Table\TableDb
     */
    public function getTable(
            array $fields = [], 
            bool  $buildLabels = true, 
            array $fieldParams = [])
    {
        return new TableDb($this, $fields, $buildLabels, $fieldParams);
    }
    
    protected function getPropValue($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
    
    public function getTableName()
    {
        return $this->getPropValue('table');
    }
    
    public function getFields()
    {
        return $this->getPropValue('fields');
    }
    
    public function getConstraints()
    {
        return $this->getPropValue('constraints');
    }
    
    public function getTriggers()
    {
        return $this->getPropValue('triggers');
    }
    
    /**
     * @return \Laminas\Db\Adapter\Driver\ConnectionInterface
     */
    public function beginTransaction()
    {
        $this->initialize();
        return $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }
    
    /**
     * @return \Laminas\Db\Adapter\Driver\ConnectionInterface
     */
    public function commit()
    {
        return $this->getAdapter()->getDriver()->getConnection()->commit();
    }
    
    /**
     * @return \Laminas\Db\Adapter\Driver\ConnectionInterface
     */
    public function rollback()
    {
        return $this->getAdapter()->getDriver()->getConnection()->rollback();
    }
    
    /**
     * @return \Laminas\Db\Adapter\Driver\Mysqli\Mysqli
     */
    public function getDriver()
    {
        return $this->getAdapter()->getDriver();
    }
    
    /**
     * @param string $sql
     * @return \Laminas\Db\Adapter\Driver\ResultInterface
     */
    public function execute(string $sql)
    {
        $this->initialize();
        return $this->getDriver()->getConnection()->execute($sql);
    }
    
    /**
     * @param string $sql
     * @return Zend\Db\Adapter\Driver\Mysqli\Statement
     */
    public function prepare(string $sql)
    {
        $this->initialize();
        return $this->getDriver()->createStatement()->prepare($sql);
    }
    
    /**
     * @return \Laminas\Db\Sql\Select
     */
    public function buildSelect($where = null)
    {
        $select = new Select($this);
        if ($where !== null) {
            $select->where($where);
        }
        return $select;
    }
    
    /**
     * @param array $set
     * @return int
     */
    public function insert($set)
    {
        $this->initialize();
        return parent::insert($set);
    }
    
    /**
     * @param string $set
     * @param array $where
     * @return int
     */
    public function update($set, $where = null, array $joins = null)
    {
        $this->initialize();
        return parent::update($set, $where, $joins);
    }
}