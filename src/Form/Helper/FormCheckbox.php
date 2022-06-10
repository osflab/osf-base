<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementCheckbox;
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
class FormCheckbox extends AbstractFormElementListHelper
{
    /**
     * @param ElementCheckbox $element
     * @return string
     */
    public function __invoke(ElementCheckbox $element)
    {
        $attributes = array(
            'type'  => 'checkbox',
            'value' => 1,
            'name'  => $element->getName(),
            'id'    => $element->getId()
        );
        if ($element->getValue()) {
            $attributes['checked'] = null;
        }
        $this->setAttributes($attributes);
        $this->setAddonAttributes($element);
        return Html::buildHtmlElement('input', $this->getAttributes());
    }
}
