<?php
namespace Osf\Form\Element;

use Osf\View\Component;

/**
 * Tags selectize element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class ElementTags extends ElementInput
{
    public function __toString() 
    {
        Component::getSelectize()->registerTags($this);
        return parent::__toString();
    }
}