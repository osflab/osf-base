<?php
namespace Osf\View\Table;

use Osf\View\Table;
use Osf\Db\Table\AbstractTableGateway;
use Osf\Form\TableForm\Field;

/**
 * Osf table from database
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class TableDb extends Table
{
    /**
     * @var \Osf\Db\Table\AbstractTableGateway
     */
    protected $osfTable;
    
    /**
     * @var \Zend\Db\Sql\Select
     */
    protected $dbSelect;
    
    /**
     * Buid an osf table from db table in order to display it with Table view helper
     * 
     * Get this objet from DbTables, modify it and call ->execute() 
     * 
     * Ex: 
     * $table = DB::getAccountTable()->getTable($fields);
     * $table->getDbSelect()->order('lastname ASC');
     * $table->execute(); // You must do this at the end
     * echo H::table($table);
     * 
     * @param AbstractTableGateway $table
     * @param array $fields field keys to display
     * @param bool $buildLabels get label names from database comments
     * @param array $fieldParams predefined fieldparams
     */
    public function __construct(
            AbstractTableGateway $table, 
            array      $fields = [], 
            bool  $buildLabels = true, 
            array $fieldParams = [])
    {
        $this->osfTable = $table;
        $this->build($fields, $buildLabels, $fieldParams);
    }
    
    /**
     * @param array $fields
     * @param bool $buildLabels
     * @param array $fieldParams
     * @return $this
     */
    protected function build(array $fields, bool $buildLabels, array $fieldParams)
    {
        if ($buildLabels) {
            $tableFields = $this->osfTable->getFields();
            foreach ($tableFields as $fieldName => $params) {
                if ($fields && !in_array($fieldName, $fields)) {
                    continue;
                }
                $field = new Field($params, $fieldName, $this->osfTable);
                if ($field->getLabel()) {
                    $fieldParams[$fieldName][self::FP_LABEL] = $field->getLabel();
                }
            }
            $this->setDisplayLabels(true);
        }
        $this->setFieldParams($fieldParams);
        $this->dbSelect = $this->getDbTable()->buildSelect();
        if ($fields) {
            $this->dbSelect->columns($fields);
        }
        return $this;
    }
    
    /**
     * @return \Osf\Db\Table\AbstractTableGateway
     */
    public function getDbTable()
    {
        return $this->osfTable;
    }
    
    /**
     * @return \Zend\Db\Sql\Select
     */
    public function getDbSelect()
    {
        return $this->dbSelect;
    }
    
    /**
     * Execute query and get resultset
     * @return \Zend\Db\ResultSet\ResultSetInterface
     */
    public function execute()
    {
        $resultSet = $this->dbSelect->execute();
        $this->setData($resultSet);
        return $resultSet;
    }
}