<?php
namespace Osf\Form\Helper;

use Osf\Stream\Html;
use Osf\View\AbstractHelper;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Display html form
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 */
class Label extends AbstractHelper
{
    use EltDecoration;
    
    /**
     * @param string $label
     * @param string $for
     * @param array $attributes
     * @return string
     */
    public function __invoke($label = null, ?string $for = null, ?array $attributes = [])
    {
        if ($for !== null) {
            $attributes['for'] = $for;
        }
        $this->resetDecorations();
        $this->setAttributes($attributes, true);
        return Html::buildHtmlElement('label', $this->getAttributes(), (string) $label);
    }
}