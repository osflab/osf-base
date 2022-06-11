<?php

namespace Osf\Db;

use Laminas\Filter\ToInt;
use Laminas\I18n\Validator\IsFloat;
use Laminas\I18n\Validator\IsInt;
use Osf\Container\LaminasContainer;
use Osf\Exception\ArchException;
use Laminas\Db\Sql\TableIdentifier;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\Db\TableGateway\AbstractTableGateway;

/**
 * Table gateway from Laminas
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 1 oct. 2013
 * @package osf
 * @subpackage db
 */
class TableGateway extends AbstractTableGateway
{
    const PRIMARY_KEY_FIELD = 'id';
    
    protected ?string $schema = null;
    protected array $values = [];
    protected array $fields = [];

    /**
     * @throws ArchException
     */
    public function __construct($table = null, $adapterName = null)
    {
        if ($table !== null && !is_int($table) && !is_array($table)) {
            if (!(is_string($table) || $table instanceof TableIdentifier)) {
                throw new ArchException(
                        'Table name must be a string or an instance of Laminas\Db\Sql\TableIdentifier');
            }
            $this->table = $table;
        }
        if ($adapterName !== null) {
            $this->adapter = LaminasContainer::getDbAdapter($adapterName);
        } else {
            if (! isset($this->adapter)) {
                $this->adapter = LaminasContainer::getDbAdapter($this->schema);
            }
        }
        if (is_int($table) || is_array($table)) {
            $this->load($table);
        }
    }

    /**
     * Load a row from an array or a primary key
     * @param int|array $data
     * @return TableGateway
     * @throws ArchException
     */
    public function load($data)
    {
        //new RowGatewayFeature();
        
        // This is the primary key
        if (is_int($data)) {
            $id = (int) $data;
            $row = $this->select(self::PRIMARY_KEY_FIELD . '=' . $id)->current();
            if ($row === false) {
                throw new ArchException('Unable to load undefined row #' . $id);
            }
            $this->populate($row);
            return $this;
        }
    
        // Populate
        elseif (is_array($data)) {
            $this->populate($data);
            return $this;
        }
    
        // Bad data type
        throw new ArchException('Bad data type, unable to load model');
    }

    public function set($field, $value)
    {
        // @task [ORM] validations et filtrages automatiques
        $this->values[$field] = $value;
    }

    public function get($field)
    {
        // @task [ORM] vérifications automatiques
        return $this->values[$field];
    }
    
    /**
     * Populate from array or arrayobject
     * @param array $values
     * @return TableGateway
     * @throws ArchException
     */
    public function populate(array $values)
    {
        foreach ($values as $key => $value) {
            if (!array_key_exists($key, $this->values)) {
                throw new ArchException('Bad key ' . $key . ', not a field');
            }
            $this->set($key, $value);
        }

        return $this;
    }
    
    public function toArray()
    {
        return $this->values;
    }

    /**
     * Generate validators from metadata
     * @param string $fieldname
     * @return array
     * @throws ArchException
     */
    public function buildValidators($fieldname = null)
    {
        $validators = array();
        if ($fieldname === null) {
            $fields = array_keys($this->_metadata);
            foreach ($fields as $field) {
                $validators[$field] = $this->getValidators($field);
            }
            return $validators;
        }
        
        if (!isset($this->_metadata[$fieldname])) {
            throw new ArchException("No field name " . $fieldname);
        }
        $fieldMetaData = $this->_metadata[$fieldname];
        if (!$fieldMetaData['NULLABLE']) {
            $validators[] = new NotEmpty();
        }
        switch ($fieldMetaData['DATA_TYPE']) {
            case 'VARCHAR2':
            case 'NVARCHAR':
            case 'NVARCHAR2':
            // case 'LONG' :
            case 'CHAR':
            case 'NCHAR':
                // case 'RAW' :
                $validators[] = new StringLength(0, 
                        $fieldMetaData['CHAR_LENGTH'], 'UTF-8');
                break;
            case 'NUMBER':
            case 'NUMERIC':
            case 'FLOAT':
            case 'DEC':
            case 'DECIMAL':
                $scale = (int) $fieldMetaData['SCALE'];
                $validators[] = $scale == 0 && $fieldMetaData['DATA_TYPE'] != 'FLOAT' ? new IsInt() : new IsFloat();
                break;
            default:
        }
        return $validators;
    }

    /**
     * Generate filters from metadata
     * @param string $fieldname
     * @return array
     * @throws ArchException
     */
    public function buildFilters($fieldname = null)
    {
        $filters = array();
        if ($fieldname === null) {
            $fields = array_keys($this->_metadata);
            foreach ($fields as $field) {
                $filters[$field] = $this->getFilters($field);
            }
            return $filters;
        }
        
        if (! isset($this->_metadata[$fieldname])) {
            throw new ArchException("No field name " . $fieldname);
        }
        $fieldMetaData = $this->_metadata[$fieldname];
        switch ($fieldMetaData['DATA_TYPE']) {
            case 'VARCHAR2':
            case 'NVARCHAR':
            case 'NVARCHAR2':
            case 'LONG':
            case 'CHAR':
            case 'NCHAR':
            case 'RAW':
                // No filters for chars
                break;
            case 'NUMBER':
            case 'NUMERIC':
                $filters[] = new ToInt();
                break;
            case 'FLOAT':
            case 'DEC':
            case 'DECIMAL':
                // No filters for floats
                break;
            default:
        }
        return $filters;
    }

    /**
     * Additional attributes for form filter and validation
     * @param string $fieldname
     * @return array [[<field>]][<fieldtype>][<attrkey>] = <attrvalue>
     * @throws ArchException
     */
    public function buildAttribs($fieldname = null)
    {
        $attribs = array();
        if ($fieldname === null) {
            $fields = array_keys($this->_metadata);
            foreach ($fields as $field) {
                $attribs[$field] = $this->getAttribs($field);
            }
            return $attribs;
        }
        
        if (! isset($this->_metadata[$fieldname])) {
            throw new ArchException("No field name " . $fieldname);
        }
        $fieldMetaData = $this->_metadata[$fieldname];
        switch ($fieldMetaData['DATA_TYPE']) {
            case 'VARCHAR2':
            case 'NVARCHAR':
            case 'NVARCHAR2':
            case 'LONG':
            case 'CHAR':
            case 'NCHAR':
            case 'RAW':
                $attribs['text']['maxlength'] = (int) $fieldMetaData['CHAR_LENGTH'];
                break;
            case 'NUMBER':
            case 'NUMERIC':
                // No attribs for numeric
                break;
            case 'FLOAT':
            case 'DEC':
            case 'DECIMAL':
                // No filters for floats
                break;
            default:
        }
        return $attribs;
    }
}
