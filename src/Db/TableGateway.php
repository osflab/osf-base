<?php
namespace Osf\Db;

use Osf\Container\ZendContainer;
use Osf\Exception\ArchException;
use Zend\Db\Sql\TableIdentifier;
use Zend\I18n\Validator\Float;
use Zend\I18n\Validator\Int;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Db\TableGateway\AbstractTableGateway;

/**
 * Table gateway from Zend
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
    
    protected $schema = null;
    protected $values = array();
    protected $fields = array();
    
    public function __construct($table = null, $adapterName = null)
    {
        if ($table !== null && !is_int($table) && !is_array($table)) {
            if (!(is_string($table) || $table instanceof TableIdentifier)) {
                throw new ArchException(
                        'Table name must be a string or an instance of Zend\Db\Sql\TableIdentifier');
            }
            $this->table = $table;
        }
        if ($adapterName !== null) {
            $this->adapter = ZendContainer::getDbAdapter($adapterName);
        } else {
            if (! isset($this->adapter)) {
                $this->adapter = ZendContainer::getDbAdapter($this->schema);
            }
        }
        if (is_int($table) || is_array($table)) {
            $this->load($table);
        }
    }

    /**
     * Load a row from an array or a primary key
     * @param int|array $data
     * @throws ArchException
     * @return \Osf\Db\TableGateway
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
     * @param unknown_type $values
     * @throws ArchException
     * @return \Osf\Db\TableGateway
     */
    public function populate($values)
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
     * @throws OpenStates_Exception
     * @return array
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
                if ($scale == 0 && $fieldMetaData['DATA_TYPE'] != 'FLOAT') {
                    $validators[] = new Int();
                    // $validators[] = new Zend_Validate_StringLength(0, (int)
                // $fieldMetaData['LENGTH'], 'UTF-8');
                } else {
                    $validators[] = new Float();
                    // $validators[] = new Zend_Validate_StringLength(0, ((int)
                // $fieldMetaData['LENGTH']) + 1, 'UTF-8');
                }
                // $precision = (int) $fieldMetaData['PRECISION'];
                // if ($precision) {
                // $max = 255 * ((int) $precision - (int) $scale);
                // if ($fieldMetaData['UNSIGNED']) {
                // $validators[] = new Zend_Validate_Between(0, $max, false);
                // } else {
                // $validators[] = new Zend_Validate_Between(-($max / 255), $max
                // / 255, false);
                // }
                // }
                break;
            default:
        }
        return $validators;
    }

    /**
     * Generate filters from metadata
     * @param string $fieldname            
     * @throws OpenStates_Exception
     * @return array
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
                $filters[] = new \Zend\Filter\Int();
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
     * @throws OpenStates_Exception
     * @return array [[<field>]][<fieldtype>][<attrkey>] = <attrvalue>
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
