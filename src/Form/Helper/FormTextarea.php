<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementTextarea;
use Osf\View\Component;

/**
 * Textarea customized element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class FormTextarea extends AbstractFormHelper
{
    const DEFAULT_ROWS = 2;
    
    /**
     * @param ElementTextarea $element
     * @return string
     */
    public function __invoke(ElementTextarea $element)
    {
        Component::getAutosize();
        if (!$this->getAttribute('rows')) {
            $this->setAttribute('rows', self::DEFAULT_ROWS);
        }
        return $this->buildStandardElement($element, [], ['form-control'], 'textarea', true);
    }
}