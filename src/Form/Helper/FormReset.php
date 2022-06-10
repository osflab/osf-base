<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementReset;

/**
 * Reset input element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class FormReset extends AbstractFormHelper
{
    /**
     * @param ElementReset $element
     * @return string
     */
    public function __invoke(ElementReset $element)
    {
        $classes = ['btn', 'btn-' . $element->getStatus()];
        return $this->buildStandardElement($element, ['type' => 'reset'], $classes);
    }
}