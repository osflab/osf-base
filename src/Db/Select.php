<?php
namespace Osf\Db;

use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Select as LaminasSelect;
use Osf\Db\Table\AbstractTableGateway;

/**
 * Select from Laminas
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage select
 */
class Select extends LaminasSelect
{
    protected AbstractTableGateway $osfTable;

    /**
     * @param AbstractTableGateway $table
     */
    public function __construct(Table\AbstractTableGateway $table)
    {
        $this->osfTable = $table;
        return parent::__construct($table->getTableName());
    }
    
    /**
     * @return ResultSetInterface
     */
    public function execute(): ResultSetInterface
    {
        return $this->osfTable->selectWith($this);
    }
}
