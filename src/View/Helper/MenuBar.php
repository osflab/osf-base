<?php
namespace Osf\View\Helper;

use Osf\Container\OsfContainer as Container;
use Osf\Exception\ArchException;

/**
 * General menu display
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright NEA 2009
 * @version 2.0
 * @package common
 * @subpackage helpers
 * @deprecated
 */
class MenuBar extends AbstractViewHelper
{
    /**
     * Build an HTML menu 
     * @return string
     */
    public function __invoke($items, $color = 'trans')
    {
        if (!in_array($color, array('blue', 'gray', 'trans'))) {
            throw new ArchException('Bad menubar color');
        }
        $h = Container::getViewHelper();
        $retVal = "<div class=\"wsbuttons\">\n";
        foreach ($items as $key => $item) {
            if (isset($item['separator'])) {
                if (!array_key_exists($key + 1, $items)
                || array_key_exists('separator', $items[$key + 1])) {
                    continue;
                }
                $retVal .= '<p class="menuseparator">' . $h->htmlEscape($item['separator']) . '</p>';
                continue;
            }
            if (!isset($item['url']) || !isset($item['image']) || !isset($item['name'])) {
                throw new ArchException('Bad menu item, key missing.');
            }
            $linkStart = "<a href=\"" . $item['url'] . "\">";
            $linkStop = "</a>";
            $gotoTitle =  'Aller à la page &quot;' . strtolower($item['name']) . '&quot;';
            $retVal .= "<div class=\"wsbutton" . $color . "\">\n";
            $retVal .= $linkStart . "\n";
            $retVal .= "<img ";
            $retVal .= "src=\"" . $h->baseUrl() . '/images/menu/';
            $retVal .= 'default/' . $item['image'];
            $retVal .= "\" border=\"0\" class=\"wsimg\" alt=\"" . $gotoTitle . "\" title=\"" . $gotoTitle . "\" />";
            $retVal .= $linkStop . "\n";
            $retVal .= "<div class=\"wslabel\">" . $linkStart . $item['name'] . $linkStop . "</div>\n";
            if (isset($item['description']) && $item['description']) {
                $retVal .= "<div class=\"wsdesc\">" . $item['description'] . "</div>\n";
            }
            $retVal .= "</div>\n";
        }
        $retVal .= "</div>\n";
        return $retVal;
    }
}
