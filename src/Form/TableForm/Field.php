<?php
namespace Osf\Form\TableForm;

use Osf\Form\Element\ElementInput as I;
use Osf\Db\Table\TableInterface;
use Osf\Stream\Text as T;
use Osf\Application\Acl;
use Osf\Stream\Yaml;
use Osf\Exception\ArchException;
use Osf\Filter\Filter as F;
use Osf\Validator\Validator as V;
use Osf\Container\OsfContainer as C;

/**
 * Field params
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage form
 */
class Field
{
    const ELEMENT_CHECKBOXES = 'ElementCheckboxes';
    const ELEMENT_CHECKBOX   = 'ElementCheckbox';
    const ELEMENT_FILE       = 'ElementFile';
    const ELEMENT_HIDDEN     = 'ElementHidden';
    const ELEMENT_INPUT      = 'ElementInput';
    const ELEMENT_RADIOS     = 'ElementRadios';
    const ELEMENT_SELECT     = 'ElementSelect';
    const ELEMENT_SUBMIT     = 'ElementSubmit';
    const ELEMENT_TAGS       = 'ElementTags';
    const ELEMENT_TEXTAREA   = 'ElementTextarea';
    
    const ELEMENTS = [
        self::ELEMENT_CHECKBOXES,
        self::ELEMENT_CHECKBOX,
        self::ELEMENT_FILE,
        self::ELEMENT_HIDDEN,
        self::ELEMENT_INPUT,
        self::ELEMENT_RADIOS,
        self::ELEMENT_SELECT,
        self::ELEMENT_SUBMIT,
        self::ELEMENT_TAGS,
        self::ELEMENT_TEXTAREA  
    ];
    
    // Default = INPUT
    const DEFAULT_TYPE_ELEMENTS = [
        'varchar'    => self::ELEMENT_INPUT,
        'char'       => self::ELEMENT_INPUT,
        'tinytext'   => self::ELEMENT_TEXTAREA,
        'text'       => self::ELEMENT_TEXTAREA,
        'mediumtext' => self::ELEMENT_TEXTAREA,
        'longtext'   => self::ELEMENT_TEXTAREA,
        'tinyint'    => self::ELEMENT_INPUT,
        'smallint'   => self::ELEMENT_INPUT,
        'mediumint'  => self::ELEMENT_INPUT,
        'int'        => self::ELEMENT_INPUT,
        'bigint'     => self::ELEMENT_INPUT,
        'date'       => self::ELEMENT_INPUT,
        'datetime'   => self::ELEMENT_INPUT,
        'timestamp'  => self::ELEMENT_INPUT,
        'time'       => self::ELEMENT_INPUT,
        'year'       => self::ELEMENT_INPUT,
        'decimal'    => self::ELEMENT_INPUT,
        'float'      => self::ELEMENT_INPUT,
        'double'     => self::ELEMENT_INPUT,
        'real'       => self::ELEMENT_INPUT,
        'bit'        => self::ELEMENT_INPUT,
        'boolean'    => self::ELEMENT_CHECKBOX,
        'tinyblob'   => self::ELEMENT_FILE,
        'blob'       => self::ELEMENT_FILE,
        'mediumblob' => self::ELEMENT_FILE,
        'longblob'   => self::ELEMENT_FILE,
        'enum'       => self::ELEMENT_SELECT,
        'set'        => self::ELEMENT_SELECT,
    ];
    
    // For input elements
    const DEFAULT_INPUT_TYPES = [
        'varchar'    => I::TYPE_TEXT,
        'char'       => I::TYPE_TEXT,
        'tinyint'    => I::TYPE_NUMBER,
        'smallint'   => I::TYPE_NUMBER,
        'mediumint'  => I::TYPE_NUMBER,
        'int'        => I::TYPE_NUMBER,
        'bigint'     => I::TYPE_NUMBER,
        'date'       => I::TYPE_DATE,
        'datetime'   => I::TYPE_DATETIME,
        'timestamp'  => I::TYPE_DATETIME,
        'time'       => I::TYPE_TIME,
        'year'       => I::TYPE_TEXT,
        'decimal'    => I::TYPE_TEXT,
        'float'      => I::TYPE_TEXT,
        'double'     => I::TYPE_TEXT,
        'real'       => I::TYPE_TEXT,
        'bit'        => I::TYPE_TEXT,
    ];
    
    const SHORTCUTS_FILTERS = [
        'trim' => 'StringTrim',
        'int' => 'ToInt'
    ];
    
    const SHORTCUTS_VALIDATORS = [
        'len' => 'StringLength'
    ];
    
    protected $params  = [];
    protected $comment = [];
    protected $fieldName;
    
    public function __construct(array $params, string $fieldName, TableInterface $table)
    {
        $this->fieldName = $fieldName;
        
        // Tableau PHP
        if (isset($params['label'])) {
            $this->params = ['isNullable' => true];
            $this->comment = $params;
        }
        
        // Table de base de données
        else {
            $this->params = $params;
            if ($params['comment']) {
                $key = $table->getTableName() ? 'TFF:' . $table->getTableName() . ':' . $fieldName : null;
                if (!$key || !($this->comment = C::getCache()->get($key))) {
                    $this->comment = Yaml::parse($params['comment']);
                    $key && C::getCache()->set($key, $this->comment);
                }
            }
        }
    }
    
    public function ignoreMe():bool
    {
        return $this->getCommentValue('type') === 'ignored';
    }
    
    /**
     * Element class to instanciate
     * @return string
     * @throws ArchException
     */
    public function getElementClass():string
    {
        $class = self::ELEMENT_INPUT;
        $elt = $this->getCommentValue('element');
        if ($elt !== null) {
            $class = 'Element' . T::camelCase($elt);
            if (!in_array($class, self::ELEMENTS)) {
                throw new ArchException('Element class [' . $this->comment['element'] . '] unknown');
            }
        } else if (isset($this->params['dataType']) &&
                   isset(self::DEFAULT_TYPE_ELEMENTS[$this->params['dataType']])) {
            $class = self::DEFAULT_TYPE_ELEMENTS[$this->params['dataType']];
        }
        return $class;
    }
    
    /**
     * Input type for input elements
     * @return string|null
     * @throws ArchException
     */
    public function getInputType()
    {
        $type = $this->getCommentValue('type');
        if ($type !== null) {
            if (!in_array($type, I::TYPES)) {
                throw new ArchException('Input type [' . $this->comment['type'] . '] unknown');
            }
        } else if (isset($this->params['dataType']) &&
                   isset(self::DEFAULT_INPUT_TYPES[$this->params['dataType']])) {
            $type = self::DEFAULT_INPUT_TYPES[$this->params['dataType']];
        } else {
            $type = I::TYPE_TEXT;
        }
        return $type;
    }
    
    /**
     * Is value required ?
     * @return bool
     */
    public function isRequired():bool
    {
        return (bool) $this->getCommentValue('required', null, 
                      !$this->getParamsValue('isNullable'));
    }
    
    /**
     * Options for select element
     * @return array|null
     */
    public function getOptions()
    {
        $options = $this->getCommentValue('options');
        if ($options !== null) {
            if (!is_array($options)) {
                throw new ArchException('The options key must be an array');
            }
            return $options;
        }
        $options = $this->getParamsValue('permitted_values');
        if (is_array($options) && $options) {
            return array_combine($options, $options);
        }
        return null;
    }
    
    public function getFilters()
    {
        $filters = [];
        $this->appendFiltersBase($filters)
             ->appendFiltersInputType($filters)
             ->appendFiltersFromComment($filters);
        return $filters;
    }
    
    public function getValidators()
    {
        $validators = [];
        $this->appendValidatorsBase($validators)
             ->appendValidatorsInputType($validators)
             ->appendValidatorsFromComment($validators);
        return $validators;
    }
    
    public function getLabel(bool $fieldNameIfNotExists = true)
    {
        if ($fieldNameIfNotExists) {
            return $this->getCommentValue('label', null, $this->fieldName);
        } else {
            return $this->getCommentValue('label');
        }
    }
    
    public function getDescription()
    {
        return $this->getCommentValue('desc');
    }
    
    public function getPlaceholder()
    {
        return $this->getCommentValue('placeholder');
    }
    
    public function getTooltip()
    {
        return $this->getCommentValue('tooltip');
    }
    
    public function getRelevance()
    {
        return $this->getCommentValue('relevance');
    }
    
    public function getAclRole()
    {
        return $this->getCommentValue('acl', null, Acl::ROLE_PUBLIC);
    }
    
    public function getDefaultValue()
    {
        return $this->getCommentValue('default');
    }
    
    public function getLeftIcon()
    {
        return $this->getCommentValue('left', 'icon');
    }
    
    public function getLeftLabel()
    {
        return $this->getCommentValue('left', 'label');
    }
    
    public function getRightIcon()
    {
        return $this->getCommentValue('right', 'icon');
    }
    
    public function getRightLabel()
    {
        return $this->getCommentValue('right', 'label');
    }
    
    public function getDataMask()
    {
        return $this->getCommentValue('mask');
    }
    
    public function getDataMaskOpt()
    {
        $opt = $this->getCommentValue('maskopt');
        return $opt === null ? null : (array) $opt;
    }
    
    public function getSize()
    {
        return $this->getCommentValue('size');
    }
    
    public function getPrefix()
    {
        return $this->getCommentValue('prefix');
    }
    
    public function getCssClasses()
    {
        return $this->getCommentValue('css');
    }
    
    public function getAttributes()
    {
        return $this->getCommentValue('attrs');
    }
    
    public function getMultiple()
    {
        return $this->getCommentValue('multiple');
    }
    
    public function getCreate()
    {
        return $this->getCommentValue('create');
    }
    
    public function getHelpKey()
    {
        return $this->getCommentValue('help');
    }
    
    protected function getCommentValue(string $key1, string $key2 = null, $default = null)
    {
        if ($key2 === null && isset($this->comment[$key1])) {
            return $this->comment[$key1];
        } else if ($key2 && isset($this->comment[$key1][$key2])) {
            return $this->comment[$key1][$key2];
        }
        return $default;
    }
    
    protected function getParamsValue(string $key, $default = null)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return $default;
    }
    
    /**
     * Detected and attach base filters from field specificities
     * @param array $items
     * @return $this
     */
    protected function appendFiltersBase(array &$items)
    {
        if (!isset($this->params['dataType'])) {
            return $this;
        }
        
        // integers
        // DESACTIVATION : il faut pouvoir dire à l'utisateur qu'on ne peut pas mettre de virgule.
//        if (in_array($this->params['dataType'], [
//            'tinyint', 'smallint', 'mediumint', 'int', 'bigint'
//        ])) {
//            $items[] = F::getToInt();
//        }

        return $this;
    }
    
    /**
     * Detected and attach input type base filters
     * @param array $items
     * @return $this
     */
    protected function appendFiltersInputType(array &$items)
    {
        $type = $this->getInputType();
        
        switch ($type) {
            case I::TYPE_EMAIL : 
                $items[] = F::getStringTrim();
                $items[] = F::getStringToLower();
                break;
            case I::TYPE_COLOR : // TODO
                $items[] = F::getStringTrim();
                break;
            case I::TYPE_TEL : // TODO: internationalisation
                $items[] = F::getStringTrim();
                break;
            case I::TYPE_URL : 
                $items[] = F::getStringTrim();
                break;
            case I::TYPE_COLOR : 
            case I::TYPE_DATE :
                $items[] = F::getDateWire();
                break;
            case I::TYPE_DATETIME : 
            case I::TYPE_DATETIME_LOCAL : 
            case I::TYPE_MONTH :
            case I::TYPE_NUMBER :
            case I::TYPE_SEARCH : 
            case I::TYPE_TEL : 
            case I::TYPE_TIME : 
            case I::TYPE_WEEK : 
                $items[] = F::getStringTrim();
                break;
        }
        
        return $this;
    }
    
    /**
     * Detected and attach base validators from field specificities
     * @param array $items
     * @return $this
     */
    protected function appendValidatorsBase(array &$items)
    {
        // string max len
        if (isset($this->params['characterMaximumLength'])) {
            $items[] = V::newStringLength()->setMax($this->getParamsValue('characterMaximumLength'));
        }
        
        // Pas de datatype (table php)
        if (!isset($this->params['dataType'])) {
            return $this;
        }
        
        // integers
        if (in_array($this->params['dataType'], [
            'tinyint', 'smallint', 'mediumint', 'int', 'bigint'
        ])) {
            $items[] = V::newIsInt();
        }
        
        // Floats
//        if (in_array($this->params['dataType'], [
//            'decimal', 'float', 'double', 'real'
//        ])) {
//            $items[] = V::newIsFloat(); //->setLocale('fr');
//        }
        
        // Date
        if ($this->params['dataType'] === 'date') {
            $items[] = V::newDate('d/m/Y');
        }
        
        // Date
        if ($this->params['dataType'] === 'datetime') {
            $items[] = V::newDateTime();
        }
        
        // Explicit permitted values
        if (isset($this->params['permitted_values'])) {
            $items[] = V::newInArray()->setHaystack($this->getParamsValue('permitted_values'));
        }
        
        return $this;
    }
    
    /**
     * Detected and attach input type base validators
     * @param array $items
     * @return $this
     * @deprecated doublon avec l'input element
     */
    protected function appendValidatorsInputType(array &$items)
    {
        $type = $this->getInputType();
        
        switch ($type) {
            case I::TYPE_EMAIL : 
                //$items[] = V::newEmailAddress();
                break;
            case I::TYPE_COLOR : // TODO
                break;
            case I::TYPE_TEL : // TODO: internationalisation
                //$items[] = V::getRegex('/^\+?[0-9 ]+$/');
                break;
            case I::TYPE_URL : 
                $items[] = V::newUri();
                break;
        }
        
        return $this;
    }
    
    /**
     * Build filters from DB comment
     * @param array $items
     * @return $this
     */
    public function appendFiltersFromComment(array &$items)
    {
        $keys = $this->getCommentValue('filters');
        if (is_array($keys)) {
            foreach ($keys as $filter => $args) {
                if ($args === null) {
                    $args = [];
                } else if (!is_array($args)) {
                    $args = [$args];
                }
                $classKey = isset(self::SHORTCUTS_FILTERS[$filter])
                        ? self::SHORTCUTS_FILTERS[$filter]
                        : $filter;
                $items[] = F::newObject($classKey, $args, true);
            }
        }
        return $this;
    }
    
    /**
     * Build validators from DB comment
     * @param array $items
     * @return $this
     */
    public function appendValidatorsFromComment(array &$items)
    {
        $keys = $this->getCommentValue('validators');
        if (is_array($keys)) {
            foreach ($keys as $validator => $args) {
                if ($args === null) {
                    $args = [];
                } else if (!is_array($args)) {
                    $args = [$args];
                }
                $classKey = isset(self::SHORTCUTS_VALIDATORS[$validator])
                        ? self::SHORTCUTS_VALIDATORS[$validator]
                        : $validator;
                $items[] = V::newObject($classKey, $args, true);
            }
        }
        return $this;
    }
}