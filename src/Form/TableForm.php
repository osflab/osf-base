<?php
namespace Osf\Form;

use Osf\Form\Validator\FormValidatorInterface;
use Osf\Db\Table\TableInterface;
use Osf\Exception\ArchException;
use Osf\Stream\Text as T;

/**
 * Form auto-generated from db/php table
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage form
 */
class TableForm extends OsfForm
{
    /**
     * @var TableInterface
     */
    protected $table;
    protected $builded = false;
    protected $ignoredFields = ['id', 'uid', 'id_account', 'date_insert', 'date_update', 'comment', 'bean'];
    protected $onlyFields = [];
    protected $displayLabels = true;
    protected $optional = false;
    protected $generateDescriptions = true;
    
    /**
     * @param TableInterface $table
     */
    public function __construct(TableInterface $table = null)
    {
        $table !== null && $this->setTable($table);
        parent::__construct();
    }
    
    /**
     * Set table to use
     * @param TableInterface $table
     * @return $this
     */
    public function setTable(TableInterface $table)
    {
        if ($this->table) {
            throw new ArchException('Table is already setted');
        }
        $this->table = $table;
        return $this;
    }
    
    /**
     * @return \Osf\Db\Table\TableInterface
     */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
     * @return $this
     */
    public function setGenerateDescriptions($generateDescriptions)
    {
        $this->generateDescriptions = (bool) $generateDescriptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function getGenerateDescriptions()
    {
        return (bool) $this->generateDescriptions;
    }
    
    /**
     * Set all the form optional (usefull for subforms)
     * @return $this
     */
    public function setOptional(bool $allFieldsOptional = true)
    {
        $this->optional = $allFieldsOptional;
        return $this;
    }
    
    /**
     * Insert values in the form table if defined
     * @return int
     */
    public function insertValues(array $fields = [])
    {
        return $this->getTable()->insert($this->getValues($fields));
    }
    
    /**
     * Build form with database options
     * @return $this
     * @throws ArchException
     */
    protected function build()
    {
        if ($this->builded) {
            throw new ArchException('TableForm already builded');
        }
        $fields = $this->table->getFields();
        if ($this->onlyFields) {
            foreach ($this->onlyFields as $fieldName) {
                $params = $this->table->getFields()[$fieldName];
                $this->buildField($fieldName, $params, $this->table);
            }
        } else {
            foreach ($fields as $fieldName => $params) {
                if (in_array($fieldName, $this->ignoredFields)) {
                    continue;
                }
                $this->buildField($fieldName, $params, $this->table);
            }
        }
        $this->add((new Element\ElementSubmit('submit'))->setValue('Valider'));
        $this->builded = true;
        return $this;
    }
    
    protected function buildField($fieldName, array $params, TableInterface $table)
    {
        $field = new TableForm\Field($params, $fieldName, $table);
        if ($field->ignoreMe()) {
            return;
        }
        $fieldClass = "\\Osf\\Form\\Element\\" . $field->getElementClass();
        $elt = new $fieldClass($fieldName);
        if ($elt instanceof Element\ElementAbstract) {
            $elt->setLabel($this->displayLabels ? $field->getLabel() : null)
                ->setHelpKey($this->generateDescriptions ? $field->getHelpKey() : null)
                ->setPlaceholder($field->getPlaceholder())
                ->setTooltip($field->getTooltip())
                ->setValue($field->getDefaultValue())
                ->setRequired(!$this->optional && $field->isRequired())
                ->setPrefix($field->getPrefix())
                ->setDescription($this->generateDescriptions ? $field->getDescription() : null)
                ->add($field->getFilters())
                ->add($field->getValidators());
            if ($field->getRelevance() === 'low') {
                $elt->setRelevanceLow();
            }
        } else {
            throw new ArchException('Incorrect element type');
        }
        if ($elt instanceof Element\ElementInput) {
            $elt->setType($field->getInputType())
                ->setAddonLeft($field->getLeftLabel(), $field->getLeftIcon())
                ->setAddonRight($field->getRightLabel(), $field->getRightIcon())
                ->setDataMask($field->getDataMask(), $field->getDataMaskOpt());
        }
        
        // @task mettre en place des interfaces pour ces selections
        if (($elt instanceof Element\ElementInput || $elt instanceof Element\ElementTextarea) && 
            !$this->displayLabels && !$field->getPlaceholder() && $field->getLabel()) {
            $elt->setPlaceholder($field->getLabel());
        }
        if ($elt instanceof Element\ElementSelect) {
            $options = $field->getOptions();
            if (!$this->displayLabels && $field->getLabel(false) && is_array($options)) {
                $options = array_replace(['' => '-- ' . T::toUpper($field->getLabel()) . ' --'], $options);
            }
            else if (!$field->isRequired() && is_array($options) && !$field->getMultiple()) {
                $options = array_replace(['' => '?'], $options);
            }
            $elt->setMultiple((bool) $field->getMultiple());
            $elt->allowCreate((bool) $field->getCreate());
            $elt->setOptions($options)
                ->setAddonLeft($field->getLeftLabel(), $field->getLeftIcon())
                ->setAddonRight($field->getRightLabel(), $field->getRightIcon());
        }
        $helper = $elt->getHelper();
        $field->getSize()       && $helper->setSize($field->getSize());
        $field->getCssClasses() && $helper->addCssClasses($field->getCssClasses());
        $field->getAttributes() && $helper->setAttributes($field->getAttributes(), true);
        $this->add($elt);
    }
    
    /**
     * White list of fields to build. Ignored fields list will not be used.
     * @param array $fieldNames
     */
    public function onlyFields(array $fieldNames)
    {
        $this->onlyFields = $fieldNames;
        return $this;
    }
    
    /**
     * Add fields names from ignored fields
     * @param array $fieldNames
     */
    public function ignoreFields(array $fieldNames)
    {
        $this->ignoredFields = array_merge($this->ignoredFields, $fieldNames);
        return $this;
    }
    
    /**
     * If false, labels are displayed in placeholders if no placeholders (input)
     * @param bool $trueOrFalse
     * @return $this
     */
    public function displayLabels(bool $trueOrFalse = true)
    {
        $this->displayLabels = $trueOrFalse;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function buildIfNotAlreadyBuilded()
    {
        if (!$this->builded) {
            $this->build();
        }
        return $this;
    }
    
    public function render() {
        $this->buildIfNotAlreadyBuilded();
        return parent::render();
    }
    
    /**
     * Check if POST contains anything and call isValid()
     * @return boolean
     */
    public function isPostedAndValid(?FormValidatorInterface $additionalValidator = null): bool
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::isPostedAndValid($additionalValidator);
    }
    
    public function getElement($name) 
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::getElement($name);
    }
    
    public function getElements() 
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::getElements();
    }
    
    public function getValue(string $key, $subForm = null)
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::getValue($key, $subForm);
    }
    
    public function getValues(array $fields = [], $includeButtons = false): array
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::getValues($fields, $includeButtons);
    }
    
    /**
     * @param array $values
     * @return bool
     */
    public function isValid($values = null)
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::isValid($values);
    }
    
    public function removeElement($name) 
    {
        $this->buildIfNotAlreadyBuilded();
        return parent::removeElement($name);
    }
}