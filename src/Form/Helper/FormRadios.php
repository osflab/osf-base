<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementRadios;
use Osf\Stream\Html;
use Osf\Container\OsfContainer as Container;

/**
 * Radios elements
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 * @task [HEPLERS] reorganiser ce helper (mélange general / bootstrap)
 */
class FormRadios extends AbstractFormElementListHelper
{
    protected $betweenOptions = '';
    
    /**
     * @param ElementRadios $element
     * @return string
     */
    public function __invoke(ElementRadios $element)
    {
        $output = $this->getBeforeOptions();
        $separator = '';
        $labelHelper = Container::getViewHelper()->label;
        $elementValue = $element->getValue();
        foreach ($element->getOptions() as $key => $option) {
            $id  = $element->getName() . '[' . $key . ']';
            $attributes = array(
                'type'  => 'radio', 
                'name'  => $element->getName(),
                'id'    => $id,
                'value' => $key
            );
            if ($key == $elementValue) {
                $attributes['checked'] = 'checked';
            }
            $this->setAttributes($attributes, true);
            $this->setAddonAttributes($element);
            $output .= $separator . Html::buildHtmlElement('div', ['class' => 'radio icheck-primary'], 
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