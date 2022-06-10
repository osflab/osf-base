<?php
namespace Osf\Form\Helper\Addon;

use Osf\Stream\Html;

/**
 * Icon or label at begining/end or input field
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage form
 */
trait LeftRight
{
    /**
     * Input addon html build tool
     * @param array|null $addon
     * @return string
     */
    protected function buildAddonHtml($addon, string $for = null)
    {
        if (is_array($addon)) {
            $attrs = is_array($addon[2]) ? $addon[2] : [];
            $attrs['class'] = (isset($attrs['class']) ? $attrs['class'] . ' ' : '') . 'input-group-addon';
            $for !== null && $attrs['for'] = $for;
            $elt = $for ? 'label' : 'div';
            $content  = is_null($addon[0]) ? '' : (string) $addon[0];
            $content .= is_null($addon[1]) ? '' : '<i class="fa ' . $addon[1] . '"></i>';
            $cssClasses = isset($addon[3]) && is_array($addon[3]) ? $addon[3] : [];
            return Html::buildHtmlElement($elt, $attrs, $content, true, $cssClasses);
        }
        return '';
    }
}
