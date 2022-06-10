<?php
namespace Osf\Config;

use Osf\Db\Table\TableInterface;

/**
 * A part of config structure
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage config
 */
class Part implements TableInterface
{
    protected $fields;
    
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }
    
    public function getFields(): ?array
    {
        return $this->fields;
    }
    
    public function getTableName(): ?string
    {
        return null;
    }
}
