<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementCheckboxes;
use Osf\Stream\Html;
use Osf\Container\OsfContainer as Container;

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
class FormCheckboxes extends AbstractFormElementListHelper
{
    protected $betweenOptions = '';
    
    /**
     * @param ElementCheckboxes $element
     * @return string
     */
    public function __invoke(ElementCheckboxes $element)
    {
        $output = $this->getBeforeOptions();
        $separator = '';
        $labelHelper = Container::getViewHelper()->label;
        $elementValues = is_array($element->getValue()) 
                ? $element->getValue() 
                : ($element->getValue() 
                        ? [htmlentities($element->getValue()) => htmlentities($element->getValue())] 
                        : []);
        foreach ($element->getOptions() as $key => $option) {
            $id  = $element->getName() . '[' . $key . ']';
            $attributes = [
                'type'  => 'checkbox', 
                'name'  => $id,
                'id'    => $id,
                'value' => $key
            ];
            if (array_key_exists($key, $elementValues)) {
                $attributes['checked'] = 'checked';
            }
            $this->setAttributes($attributes);
            $this->setAddonAttributes($element);
            $output .= $separator . Html::buildHtmlElement('div', ['class' => 'checkbox icheck-primary'], 
                          Html::buildHtmlElement('input', $this->getAttributes())
                        . $this->getBeforeLabel() 
                        . $labelHelper($option, $id, ['class' => 'checkboxlabel']) 
                        . $this->getAfterLabel());
                    
            if (!$separator) {
                $separator = $this->getBetweenOptions();
            }
        }
        $output .= $this->getAfterOptions();
        return $output;
    }
}
