<?php
namespace Osf\Form\Helper;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Form\Element\ElementSelect;
use Osf\View\Component;
use Osf\Stream\Html;

/**
 * Text input element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class FormSelect extends AbstractFormElementListHelper
{
    use Addon\LeftRight;
    
    /**
     * @param ElementSelect $element
     * @return \Osf\Form\Helper\FormSelect
     */
    public function __invoke(ElementSelect $element)
    {
        $this->setElement($element);
        $this->initValues(get_defined_vars());
        return $this;
    }
    
    /**
     * @return string
     */
    public function render()
    {
        /* @var $element \Osf\Form\Element\ElementSelect */
        $element = $this->getElement();
        $options = $this->getBeforeOptions();
        $separator = '';
        $elementValue = $element->getValue();
        $elementValues = is_array($elementValue) ? array_unique($elementValue) : [$elementValue];
        
        // Lazy Autocomplétion
        if ($element->getAutocompleteAdapter()) {
            $element->getAutocompleteAdapter()->registerAutocomplete($element, $element->getAutocompleteLimit());
        }
        
        // Options
        $eltOptions = $element->getOptions();
        if ($element->isMultiple()) {
            $orderedOptions = [];
            foreach ($elementValues as $key) {
                if ($element->getAutocomplete()) {
                    $orderedOptions[$key] = $key;
                } else {
                    if (!isset($eltOptions[$key])) {
                        Checkers::notice('Key [' . $key . '] not found in select options');
                        continue;
                    }
                    $orderedOptions[$key] = $eltOptions[$key];
                    unset($eltOptions[$key]);
                }
            }
            $orderedOptions += $eltOptions;
            $eltOptions = $orderedOptions;
        }
        foreach ($eltOptions as $key => $option) {
            $attributes = ['value' => htmlentities($key)];
            if (in_array($key, $elementValues)) {
                $attributes['selected'] = null;
            }
            $options .= $separator . Html::buildHtmlElement('option', $attributes, Html::escape($option));
            $separator || $separator = $this->getBetweenOptions();
        }
        $options .= $this->getAfterOptions();
        
        // Attributes
        $attributes = $this->getAttributes();
        $attributes['name'] = $element->getName();
        $attributes['id']   = $element->getId();
        $element->isMultiple() && $attributes['multiple'] = null;
        $element->isMultiple() && $attributes['name'] .= '[]';
        $element->getPlaceholder() && $attributes['placeholder'] = $this->esc($element->getPlaceholder());
        
        // Build main element
        $retVal = Html::buildHtmlElement('select', $attributes, $options);
        
        // Addons left / right
        $this->resetDecorations();
        $this->setAddonAttributes($element);
        $lValue = $this->buildAddonHtml($element->getAddonLeft());
        $rValue = $this->buildAddonHtml($element->getAddonRight());
        if ($lValue || $rValue || $this->getAttributes()) {
            if ($lValue || $rValue) {
                $this->addCssClass('input-group');
            }
            $retVal = '<div' . $this->getEltDecorationStr() . '>' . $lValue . $retVal . $rValue . '</div>';
        }
        
        // Selectize l'élément
        Component::getSelectize()->registerSelect($element);
        
        return $retVal;
    }
}