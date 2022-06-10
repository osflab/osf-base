<?php
namespace Osf\Db\Row;

use Zend\Db\RowGateway\AbstractRowGateway as ZendAbstractRowGateway;
use Zend\Db\Sql\Sql;
use Osf\Exception\ArchException;
use Osf\Container\ZendContainer;
use Osf\Db\Table\AbstractTableGateway;
use Osf\Stream\Text as T;
use Osf\Db\Addon\Schema;

/**
 * OSF Parent class for generated rows classes
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 9 nov. 2013
 * @package osf
 * @subpackage db
 */
abstract class AbstractRowGateway 
    extends ZendAbstractRowGateway
    implements RowGatewayInterface
{
    use Schema;
    
    const SPECIAL_FIELD_BEAN = 'bean';
    
    protected $tableGateway;
    protected $extraData = [];
    
    /**
     * FR: Initialisation avec la création du SQL avec le schéma
     * @see \Zend\Db\RowGateway\AbstractRowGateway::initialize()
     */
    public function initialize()
    {
        if ($this->isInitialized) {
            return;
        }
        if (count(array_intersect_key($this->getTableGateway()->getFields(), $this->extraData))) {
            throw new ArchException('Some extra data fields and row fields have the same name');
        }
        $this->sql = new Sql(ZendContainer::getDbAdapter($this->getSchema()), $this->table);
        return parent::initialize();
    }
    
    /**
     * FR: Injecte une valeur dans un champ
     * 
     * Pour valider et filtrer, surcharger cette méthode plutôt que les accesseurs
     * 
     * @see \Osf\Db\Row\RowGatewayInterface::set()
     * @param string|array $field
     * @param mixed|array $value
     * @return \Osf\Db\Row\AbstractRowGateway
     */
    public function set($field, $value, $withFilters = true)
    {
        $this->loadBean();
        
        // Filtres
        if ($withFilters) {
            
            // Gestion des insertions multiples
            if (is_array($field) && is_array($value) && count($field) == count($value)) {
                while (count($field) != 0) {
                    $this->set(array_shift($field), array_shift($value));
                }
                return $this;
            }
            
            // Sérialisation automatique si c'est un field "bean"
            if ($field == self::SPECIAL_FIELD_BEAN) {
                $value = serialize($value);
            }
        }
        
        // Insertion de la valeur
        $fields = $this->getTableGateway()->getFields();
        if (isset($fields[$field])) {
            $this->data[$field] = $value;
//        } else if (array_key_exists($field, $this->extraData)) {
//            $this->extraData[$field] = $value;
        } else {
            throw new ArchException('Field [' . $field . '] unknown');
        }
        return $this;
    }
    
    /**
     * FR: Récupère la valeur d'un champ
     * 
     * Pour filtrer, surcharger cette méthode plutôt que les accesseurs
     * 
     * @see \Osf\Db\Row\RowGatewayInterface::get()
     * @param string $field
     */
    public function get($field)
    {
        // La valeur n'existe pas dans la table ?
        if (!array_key_exists($field, $this->data)) {
            
            // Récupération depuis les données extra si la valeur 
            // est dans le bean
            if ($field !== self::SPECIAL_FIELD_BEAN) {
                $this->loadBean();
            }
            if (array_key_exists($field, $this->extraData)) {
                return $this->extraData[$field];
            }
            return null;
        }
        
        // Désérialisation si c'est un bean
        if ($field == self::SPECIAL_FIELD_BEAN && $this->data[$field]) {
            return unserialize($this->data[$field]);
        }
        
        return $this->data[$field];
    }
    
    /**
     * Multiple set()
     * @param array $values key -> value pairs to put with "set"
     * @param bool $withFilters
     * @return $this
     */
    public function setValues(array $values, bool $withFilters = true)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $withFilters);
        }
        return $this;
    }
    
    /**
     * @return AbstractTableGateway
     */
    public function getTableGateway()
    {
        if (!$this->tableGateway) {
            $class = preg_replace('/^(.*\\\\)[^\\\\]+$/', '\\\\$1DbContainer', get_class($this));
            $method = 'get' . T::camelCase($this->table) . 'Table';
            $this->tableGateway = $class::$method();
        }
        return $this->tableGateway;
    }
    
    /**
     * FR: Retourne l'enregistrement lié à une clé étrangère
     * @param string $value
     * @param string $fkTable
     * @param string $fkField
     * @return \Osf\Db\Row\AbstractRowGateway
     */
    protected function getInternalFkRow($value, AbstractTableGateway $fkTable, $fkField = 'id')
    {
        return $fkTable->select(array($fkField => $value))->current();
    }
    
    /**
     * FR: Retourne les enregistrements d'une table dont une clé étrangère référence la table courante
     * @param string $fkTable
     * @param string $fkField
     * @param string $pkValue
     * @param string $pkField
     * @throws ArchException
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    protected function getExternalFkRows(AbstractTableGateway $fkTable, $fkField, $pkValue = null, $pkField = null)
    {
        if ($pkField === null) {
            if (!isset($this->primaryKeyColumn[0])) {
                throw new ArchException('No primary key specified for this row');
            }
            $pkField = $this->primaryKeyColumn[0];
        }
        if ($pkValue === null) {
            if (!isset($this->primaryKeyData[$pkField])) {
                throw new ArchException('No data for primary key for this row');
            }
            $pkValue = $this->primaryKeyData[$pkField];
        }
        return $fkTable->select(array($fkField => $pkValue));
    }
    
    /**
     * FR: Récupère l'enregistrement sous forme de tableau avec éventuellement les champs mentionnés
     * @see \Zend\Db\RowGateway\AbstractRowGateway::toArray()
     * @param array $fields
     * @return array
     */
    public function toArray(array $fields = null)
    {
        $this->loadBean();
        $row = array_merge(parent::toArray(), $this->extraData);
        if ($fields && is_array($row)) {
            $row = array_intersect_key($row, array_flip($fields));
        }
        return $row;
    }
    
    /**
     * Serialize current object and put it in bean field
     * @return $this
     */
    protected function updateBean()
    {
        $this->loadBean();
//        if (method_exists($this, 'setBean')) {
//            $this->set(self::SPECIAL_FIELD_BEAN, $this);
//        }
        return $this;
    }
    
    /**
     * Load extra data from bean object of the current row
     * @return $this
     */
    public function loadBean($cache = true)
    {
        static $loaded = false;
        
//        if (!$cache || !$loaded) {
//            $bean = $this->get(self::SPECIAL_FIELD_BEAN);
//            if ($bean instanceof AbstractRowGateway) {
//                $this->extraData = $bean->extraData;
//                $loaded = true;
//            }
//        }
        return $this;
    }
    
    /**
     * Get extra data from bean object
     * @return array
     */
    public function getExtraData()
    {
        return $this->getExtraData();
    }
    
    /**
     * Insert the row and return the id
     * @return mixed
     */
    public function insert()
    {
        return $this->updateBean()
                    ->getTableGateway()
                    ->insert($this->toArray());
    }
    
    /**
     * @return int
     */
    public function save()
    {
        $this->updateBean();
        return parent::save();
    }
    
//    public function populate(array $rowData, $rowExistsInDatabase = false)
//    {
//        $this->extraData = array_merge($this->extraData, array_intersect_key($rowData, $this->extraData));
//        return parent::populate(array_diff_key($rowData, $this->extraData), $rowExistsInDatabase);
//    }
    
    /**
     * Filtre les données à insérer dans l'objet sérialisé (bean)
     * @return array
     */
    public function __sleep()
    {
        return ['extraData'];
    }
}
