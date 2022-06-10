<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementHidden;

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
class FormHidden extends AbstractFormHelper
{
    /**
     * @param ElementTextarea $element
     * @return string
     */
    public function __invoke(ElementHidden $element)
    {
        return $this->buildStandardElement($element, ['type' => 'hidden']);
    }
}