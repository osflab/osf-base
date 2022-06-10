<?php
namespace Osf\Db;

use Zend\Db\Sql\Select as ZendSelect;

/**
 * Select from Zend
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage select
 */
class Select extends ZendSelect
{
    /**
     * @var Table\AbstractTableGateway
     */
    protected $osfTable;
    
    public function __construct(Table\AbstractTableGateway $table)
    {
        $this->osfTable = $table;
        return parent::__construct($table->getTableName());
    }
    
    /**
     * @return ResultSetInterface
     */
    public function execute()
    {
        return $this->osfTable->selectWith($this);
    }
}
