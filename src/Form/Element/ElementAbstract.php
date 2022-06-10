<?php
namespace Osf\Form\Element;

use Zend\Filter\FilterChain;
use Zend\Validator\ValidatorChain;
use Zend\Filter\FilterInterface;
use Zend\Validator\ValidatorInterface;
use Osf\Form\Helper\AbstractFormHelper;
use Osf\Exception\ArchException;
use Osf\Form\AbstractForm;
use Osf\View\Helper\Bootstrap\Addon\Tooltip;
use Osf\Stream\Text as T;

/**
 * Form element using Zend Filter and Validators but simplified
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class ElementAbstract implements ValidatorInterface
{
    const RELEVANCE_LOW = 'low';
    const RELEVANCE_HIGH = 'high';
    
    use Tooltip;
    
    protected $name;
    protected $label;
    protected $placeholder;
    protected $description;
    protected $errors     = [];
    protected $warnings   = [];
    protected $params     = [];
    protected $filters    = null; // Chaîne de filtrage
    protected $validators = null; // Chaîne de validation
    protected $fTab       = [];   // Filtres récupérables via un tableau
    protected $vTab       = [];   // Validateurs récupérables via un tableau
    protected $value      = '';
    protected $helper     = null;
    protected $required   = false;
    protected $ignore     = false; // Valeur de l'élément ignoré par form->getValues()
    protected $focus      = false; // Mettre le focus sur cet élément en priorité
    protected $relevance  = self::RELEVANCE_HIGH;
    protected $form;    // Formulaire auquel l'élément est attaché
    protected $prefix;  // Préfixe à ajouter dans le nom de l'élément à l'affichage (surcharge celui du formulaire)
    protected $helpKey; // Basename du fichier d'aide lié Markdown, appelé avec le helper "help"
    
    public function __construct($nameOrParams = null, array $params = null)
    {
        // Remaniement des paramètres
        $this->initTooltip([]);
        if (is_array($nameOrParams)) {
            $name = isset($nameOrParams[0]) ? $nameOrParams[0] : null;
            $params = isset($nameOrParams[1]) ? $nameOrParams[1] : $params;
        } else {
            $name = $nameOrParams;
        }
        
        // Vérification des types de données
        if (!is_string($name)) {
            throw new ArchException('Element name must be a string');
        }
        if ($params !== null && !is_array($params)) {
            throw new ArchException('Params must be an associative array');
        }
        
        // Set the name and params
        if ($name !== null) {
            $this->setName($name);
        }
        if ($params !== null) {
            $this->appendParams($params);
        }
    }

    /**
     * Get name of the element
     * @param bool $withPrefixes with form/element prefix and parent form key/prefix
     * @return string
     */
    public function getName($withPrefixes = true)
    {
        $names = [];
        if ($withPrefixes) {
            if ($this->getForm() && $this->getForm()->isSubForm()) {
                if ($this->getForm()->getParentForm()->getPrefix()) {
                    $names[] = $this->getForm()->getParentForm()->getPrefix();
                }
                $names[] = $this->getForm()->getFormKey();
            }
            if ($this->getPrefix()) {
                $names[] = $this->getPrefix();
            }
        }
        $names[] = $this->name;
        return array_shift($names) . ($names ? '[' . implode('][', $names) . ']' : '');
    }
    
    public function getId()
    {
        return trim(str_replace('__', '_', strtr($this->getName(), '[]', '__')), '_');
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function getPlaceholder()
    {
        return $this->placeholder;
    }
    
    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description === null ? null : (string) $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->errors);
    }
    
    /**
     * @param string $errorMessage
     * @return $this
     */
    public function addError($errorMessage)
    {
        $this->errors[] = (string) $errorMessage;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
    
    /**
     * @return bool
     */
    public function hasWarning(): bool
    {
        return !empty($this->warnings);
    }
    
    /**
     * @param string $message
     * @return $this
     */
    public function addWarning($message)
    {
        $this->warnings[] = (string) $message;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addParam($key, $value)
    {
        $this->params[trim($key)] = $value;
        return $this;
    }
    
    /**
     * @param array $params
     * @return $this
     */
    public function appendParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }
    
    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label, $helpKey = null)
    {
        if ($label !== null) {
            $this->label = (string) $label;
        }
        if ($helpKey !== null) {
            $this->setHelpKey($helpKey);
        }
        return $this;
    }
    
    /**
     * @param string $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        if ($placeholder !== null) {
            $this->placeholder = (string) $placeholder;
        }
        return $this;
    }
    
    /**
     * @return \Zend\Filter\FilterChain
     */
    public function getFilters()
    {
        if ($this->filters === null) {
            $this->filters = new FilterChain();
        }
        return $this->filters;
    }
    
    /**
     * @return \Zend\Validator\ValidatorChain
     */
    public function getValidators()
    {
        if ($this->validators === null) {
            $this->validators = new ValidatorChain();
        }
        return $this->validators;
    }
    
    /**
     * @param FilterChain $filters
     * @return $this
     */
    public function setFilters(FilterChain $filters)
    {
        $this->filters = $filters;
        return $this;
    }
    
    /**
     * @param ValidatorChain $validators
     * @return $this
     */
    public function setValidators(ValidatorChain $validators)
    {
        $this->validators = $validators;
        return $this;
    }
    
    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->getFilters()->attach($filter);
        $this->fTab[get_class($filter)][] = $filter;
        return $this;
    }
    
    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function addValidator(ValidatorInterface $validator)
    {
        $this->getValidators()->attach($validator);
        $this->vTab[get_class($validator)][] = $validator;
        return $this;
    }
    
    /**
     * @param string $validatorClassName
     * @param int $index
     * @return \Zend\Validator\AbstractValidator
     */
    public function getValidator(string $validatorClassName, int $index = 0)
    {
        return isset($this->vTab[$validatorClassName][$index])
            ? $this->vTab[$validatorClassName][$index]
            : null;
    }
    
    /**
     * @param string $filterClassName
     * @param int $index
     * @return \Zend\Filter\AbstractFilter
     */
    public function getFilter(string $filterClassName, int $index = 0)
    {
        return isset($this->fTab[$filterClassName][$index])
            ? $this->fTab[$filterClassName][$index]
            : null;
    }
    
    /**
     * @param type $filterOrValidator
     * @return $this
     * @throws ArchException
     */
    public function add($filterOrValidator)
    {
        if (is_array($filterOrValidator)) {
            foreach ($filterOrValidator as $item) {
                $this->add($item);
            }
            return $this;
        }
        if (!is_object($filterOrValidator)) {
            throw new ArchException('Validator or filter instance required');
        }
        switch (true) {
            case $filterOrValidator instanceof \Zend\Validator\AbstractValidator : 
                $this->addValidator($filterOrValidator);
                break;
            case $filterOrValidator instanceof \Zend\Filter\AbstractFilter : 
                $this->addFilter($filterOrValidator);
                break;
            default : 
                throw new ArchException('Bad filter or validator type');
        }
        return $this;
    }
    
    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired($required = true)
    {
        $this->required = (bool) $required;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Zend Validate Interface
     * @return boolean
     */
    public function isValid($value = null)
    {
        if ($value !== null) {
            $this->setValue($value);
        }
        $this->errors = [];
        $output = true;
        if (is_array($this->getValue())) {
            return $output;
        }
        if (strlen($this->getValue())) {
            $output = $this->getValidators()->isValid($this->getValue());
            if (!$output) {
                $this->errors = $this->getValidators()->getMessages();
            }
        } elseif ($this->required) {
            $this->errors['required'] = __("This value is required");
            $output = false;
        }
        return $output;
    }

    /**
     * Zend Validate Interface
     * @return array
     */
    public function getMessages()
    {
        return $this->getErrors();
    }
    
    /**
     * @task [HELPER] OPTIMISER
     * @return \Osf\Form\Helper\AbstractFormHelper
     */
    public function getHelper()
    {
        if ($this->helper === null) {
            $helperClass = '\Osf\Form\Helper\Form' . preg_replace("/^.*Element(.*)$/", '$1', get_class($this));
            $this->setHelper(new $helperClass());
        }
        return $this->helper;
    }
    
    /**
     * @param \Osf\Form\Helper\AbstractFormHelper $helper
     * @return $this
     */
    public function setHelper(AbstractFormHelper $helper)
    {
        $this->helper = $helper;
        $this->helper->setElement($this);
        return $this;
    }
    
    /**
     * Used by form to retreive it from element
     * @return $this
     */
    public function setForm(AbstractForm $form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Form attached to the current element
     * @return \Osf\Form\AbstractForm
     */
    public function getForm()
    {
        return $this->form;
    }
    
    /**
     * Prefix to add in order to get values in an array
     * This value will overload the form prefix
     * @param $prefix Prefix value : '' => cancel the form prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = T::strOrNull($prefix);
        return $this;
    }

    /**
     * Prefix to add to the element (or form) name
     * @return string
     */
    public function getPrefix()
    {
        if ($this->prefix === null && $this->getForm()) {
            return $this->getForm()->getPrefix();
        }
        return $this->prefix;
    }
    
    /**
     * @return $this
     */
    public function setRelevanceHigh()
    {
        return $this->setRelevance(self::RELEVANCE_HIGH);
    }

    /**
     * @return $this
     */
    public function setRelevanceLow()
    {
        return $this->setRelevance(self::RELEVANCE_LOW);
    }

    /**
     * @return $this
     */
    public function setRelevance($relevance)
    {
        $this->relevance = $relevance;
        return $this;
    }

    public function getRelevance()
    {
        return $this->relevance;
    }
    
    /**
     * @param $ignore bool
     * @return $this
     */
    public function setIgnore($ignore = true)
    {
        $this->ignore = (bool) $ignore;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIgnore():bool
    {
        return $this->ignore;
    }
    
    /**
     * @return $this
     */
    public function setFocus($focus = true)
    {
        $this->focus = (bool) $focus;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFocus()
    {
        return (bool) $this->focus;
    }
    
    /**
     * @param $helpKey string|null
     * @return $this
     */
    public function setHelpKey($helpKey)
    {
        $this->helpKey = $helpKey === null ? null : (string) $helpKey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHelpKey()
    {
        return $this->helpKey;
    }
    
    /**
     * Call the element renderer from its helper
     * @return string
     */
    public function renderElement(): string
    {
//        return (string) $this->getHelper()->render(); // $this->getHelper()->__invoke($this);
        return (string) $this->getHelper()->__invoke($this);
    }
    
    public function __toString()
    {
        return $this->renderElement();
    }
}