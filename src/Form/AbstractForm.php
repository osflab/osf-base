<?php
namespace Osf\Form;

use Zend\Validator\ValidatorInterface;
use Osf\Form\Validator\FormValidatorInterface;
use Osf\Form\Element\ElementAbstract;
use Osf\Form\Hydrator\HydratorAbstract;
use Osf\Form\Hydrator\HydratorStandard;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Session\Container as Session;

/**
 * Top osf form class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage form
 */
abstract class AbstractForm implements ValidatorInterface
{
    protected $title      = null;
    protected $icon       = null;
    protected $iconColor  = null;
    protected $htmlBefore = '';
    protected $htmlAfter  = '';
    protected $action     = null;
    protected $elements   = [];
    protected $options    = [];
    protected $subForms   = [];
    protected $parentForm;
    protected $formKey; // Form key if the form is a subform
    protected $prefix;
    protected $collapseLowRelevance = true;
    protected $starsForRequired = true;
    protected $collapsable = false;
    protected $expandable = false;
    protected $help; // Aide liée au forumulaire
    protected $hasFilledElt = false; // Possède au moins un élément rempli (filtrage activé)
    protected $highlightFilledElts = false; // Mise en surbrillance des éléments remplis
    
    /**
     * @param ElementAbstract $element
     * @return $this
     */
    public function add(ElementAbstract $element)
    {
        $this->elements[$element->getName(false)] = $element;
        $element->setForm($this);
        return $this;
    }
    
    /**
     * @param array|null $values
     * @param HydratorAbstract $hydrator
     * @return $this
     */
    public function hydrate(?array $values, HydratorAbstract $hydrator = null, bool $prefixedValues = true, bool $noError = false, bool $fullValues = false)
    {
        if ($values === null) {
            return $this;
        }
        if ($hydrator === null) {
            $hydrator = new HydratorStandard();
        }
        $hydrator->hydrate($values, $this, $prefixedValues, $noError, $fullValues);
        return $this;
    }

    /**
     * @param array|null $values
     * @return bool
     */
    public function isValid($values = null) // Cf. ZendValidator::isValid()
    {
        $this->hydrate($values);
        $this->hasFilledElt = false;
        $valid = true;
        foreach ($this->getSubForms() as $subForm) {
            $valid = $subForm->isValid() && $valid;
        }
        foreach ($this->getElements() as $element) {
            $valid = $element->isValid() && $valid;
            $this->highlightEltIfFilled($element);
        }
        return $valid;
    }
    
    /**
     * @param ElementAbstract $elt
     * @return $this
     */
    protected function highlightEltIfFilled(ElementAbstract $elt)
    {
        if ($this->highlightFilledElts && !($elt instanceof Element\ElementSubmit)) {
            if ($elt->getValue()) {
                $elt->getHelper()->hasCssClass('filled') || $elt->getHelper()->addCssClass('filled');
                $this->hasFilledElt = true;
            } else {
                $elt->getHelper()->removeCssClass('filled');
            }
        }
        return $this;
    }
    
    /**
     * At least one element is filled ?
     * @return bool
     */
    public function hasFilledElt(): bool
    {
        return $this->hasFilledElt;
    }
    
    /**
     * Highlight filled elements
     * @param bool $highlightFilledElts
     * @return $this
     */
    public function setHighlightFilledElts($highlightFilledElts = true)
    {
        $this->highlightFilledElts = (bool) $highlightFilledElts;
        return $this;
    }

    /**
     * @return bool
     */
    public function getHighlightFilledElts(): bool
    {
        return $this->highlightFilledElts;
    }
    
    /**
     * Check if POST contains anything and call isValid()
     * @task [form] filtrer pour un formulaire particulier...
     * @return false|array
     */
    public function getPostedValues()
    {
        return filter_input_array(INPUT_POST);
    }
    
    /**
     * Is the form posted ?
     * @task [FORM] FR: Associer à la détection isPosted() le jeton CSRF
     * @return bool
     */
    public function isPosted()
    {
        // Si on détecte le tag RVS dans $_POST, on fixe la valeur dans la session
        $this->rvsCheckPostTag();
        
        $postedValues = $this->getPostedValues();
        if (!$postedValues) {
            return false;
        }
        if ($this->getPrefix()) {
            return array_key_exists($this->getPrefix(), $postedValues);
        }
        return true;
    }
    
    /**
     * Check if POST contains anything and call isValid()
     * @param FormValidatorInterface $additionalValidator
     * @return boolean
     */
    public function isPostedAndValid(?FormValidatorInterface $additionalValidator = null): bool
    {
        if ($this->isPosted()) {
            $values = $this->getPostedValues();
            $this->hydrate($values, null, true, true);
            $valid = $this->isValid();
            if ($additionalValidator !== null) {
                $valid = $additionalValidator->isValid($this) && $valid;
            }
            return $valid;
        }
        return false;
    }
    
    public function getElements()
    {
        return $this->elements;
    }
    
    public function getElementKeys()
    {
        return array_keys($this->elements);
    }
    
    /**
     * @param string $name
     * @return ElementAbstract
     */
    public function getElement($name)
    {
        return array_key_exists($name, $this->elements) ? $this->elements[$name] : null;
    }

    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * @param string $name
     * @return \Osf\Form\AbstractForm
     */
    public function removeElement($name)
    {
        unset($this->elements[$name]);
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $value
     * @return \Osf\Form\AbstractForm
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
    
    /**
     * @param $iconColor string|null
     * @return $this
     */
    public function setIconColor($iconColor)
    {
        $iconColor === null || Checkers::checkColor($iconColor);
        $this->iconColor = $iconColor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconColor()
    {
        return $this->iconColor;
    }
    
    /**
     * @param type $title
     */
    public function setTitle($title, $icon = null, $iconColor= null)
    {
        $icon !== null && Checkers::checkIcon($icon, null);
        $this->icon = $icon;
        $this->title = (string) $title;
        $this->setIconColor($iconColor);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key, $subForm = null)
    {
        return $subForm 
               ? $this->getSubForm($subForm)->getElement($key)->getValue()
               : $this->getElement($key)->getValue();
    }
    
    /**
     * @return array
     */
    public function getValues(array $fields = []):array
    {
        $values = [];
        foreach ($this->getSubForms() as $formName => $form) {
            $values[$formName] = $form->getValues();
        }
        foreach ($this->getElements() as $element) {
            if (($fields && !in_array($element->getName(), $fields)) || // Pas dans la liste blanche
                ($element->getIgnore())) { // Elements ignorés (boutons submit ignorés par défaut)
                continue;
            }
            if ($element->getPrefix()) {
                $values[$element->getPrefix()][$element->getName(false)] = $element->getValue();
            } else {
                $values[$element->getName(false)] = $element->getValue();
            }
        }
        return $values;
    }

    /**
     * @param string $url
     * @return $this;
     */
    public function setAction(string $url)
    {
        $this->action = $url;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * @param string $key
     * @param string $title
     * @param \Osf\Form\AbstractForm $form
     * @return $this
     */
    public function addSubForm(string $key, AbstractForm $form)
    {
        $this->subForms[$key] = $form;
        $form->setParentForm($this);
        $form->setFormKey($key);
        return $this;
    }
    
    /**
     * @param string $key
     * @return AbstractForm
     */
    public function getSubForm(string $key)
    {
        return $this->hasSubForm($key) ? $this->subForms[$key] : null;
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function hasSubForm(string $key):bool
    {
        return array_key_exists($key, $this->subForms);
    }
    
    /**
     * @return array
     */
    public function getSubForms():array
    {
        return $this->subForms;
    }
    
    /**
     * @return $this
     */
    public function setParentForm(AbstractForm $parentForm)
    {
        $this->parentForm = $parentForm;
        return $this;
    }

    /**
     * If the form is a subform, get the parent form
     * @return \Osf\Form\AbstractForm
     */
    public function getParentForm()
    {
        return $this->parentForm;
    }
    
    /**
     * Is current form a subform ?
     * @return bool
     */
    public function isSubForm()
    {
        return (bool) $this->getParentForm();
    }

    /**
     * @return $this
     */
    public function setFormKey(string $parentFormKey)
    {
        $this->formKey = $parentFormKey;
        return $this;
    }

    public function getFormKey()
    {
        return $this->formKey;
    }
    
    /**
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string) $prefix;
        return $this;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }
    
    /**
     * @return $this
     */
    public function setHtmlBefore($htmlBefore)
    {
        $this->htmlBefore = (string) $htmlBefore;
        return $this;
    }

    public function getHtmlBefore()
    {
        return $this->htmlBefore;
    }
    
    /**
     * @param string $htmlAfter
     * @return $this
     */
    public function setHtmlAfter(string $htmlAfter)
    {
        $this->htmlAfter = (string) $htmlAfter;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlAfter(): string
    {
        return $this->htmlAfter;
    }
    
    /**
     * Set collapse low relevance inputs
     * @param type $collapseLowRelevance
     * @param bool $force force la valeur dans la session pour la rendre persistante
     * @return $this
     */
    public function setCollapseLowRelevance($collapseLowRelevance = true, bool $force = false)
    {
        if ($collapseLowRelevance !== null) {
            $this->collapseLowRelevance = (bool) $collapseLowRelevance;
        }
        if ($force) {
            Session::set($this->rvsSessionKey(), $this->collapseLowRelevance);
        }
        return $this;
    }

    /**
     * S'il y a une valeur dans la session, renvoit cette valeur, sinon la valeur du formulaire
     * @return type
     */
    public function getCollapseLowRelevance()
    {
        // Si on détecte le tag RVS dans $_POST, on fixe la valeur dans la session
        $this->rvsCheckPostTag();
        
        // On récupère la valeur rvs de la session, si elle existe, on la retourne
        $rvs = Session::get($this->rvsSessionKey());
        if ($rvs !== null) {
            return (bool) $rvs;
        }
        
        // Sinon, on retourne la valeur par défaut
        return $this->collapseLowRelevance;
    }
    
    public function cleanCollapseLowRelevancePersistence()
    {
        Session::clean($this->rvsSessionKey());
    }
    
    /**
     * @return $this
     */
    public function setStarsForRequired($starsForRequired = true)
    {
        $this->starsForRequired = (bool) $starsForRequired;
        return $this;
    }

    public function getStarsForRequired():bool
    {
        return $this->starsForRequired;
    }
    
    /**
     * @param $collapsable bool
     * @return $this
     */
    public function setCollapsable($collapsable = true)
    {
        $this->collapsable = (bool) $collapsable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCollapsable():bool
    {
        return $this->collapsable;
    }
    
    /**
     * @param $expandable bool
     * @return $this
     */
    public function setExpandable($expandable = true)
    {
        $this->expandable = (bool) $expandable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getExpandable():bool
    {
        return $this->expandable;
    }
    
    /**
     * @param $help string|null
     * @return $this
     */
    public function setHelp($help)
    {
        $this->help = $help === null ? null : (string) $help;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHelp()
    {
        return $this->help;
    }
    
    /**
     * If RVS tag is detected in $_POST, we fix the value in PHP session
     * @task [RVS] FR: Fixer le tag dans le cache utilisateur par formulaire ?
     * @return $this
     */
    protected function rvsCheckPostTag()
    {
        static $checked = false;
        
        if (!$checked) {
            $rvs = filter_input(INPUT_POST, 'rvs');
            if ($rvs !== null && $rvs != -1) {
                $this->setCollapseLowRelevance((bool) $rvs, true);
            }
            $checked = true;
        }
        return $this;
    }
    
    protected function rvsSessionKey()
    {
        return 'RVS:' . get_class($this);
    }
    
    public function render()
    {
        return (string) \Osf\Container\OsfContainer::getViewHelper()->form($this)->render();
    }
    
    public function __toString()
    {
        return $this->render();
    }
    
    public function getMessages()
    {
        throw new ArchException('To be implemented (ZendValidator)');
    }
}