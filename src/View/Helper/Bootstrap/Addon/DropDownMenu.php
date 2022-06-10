<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Stream\Html;

/**
 * Dropdown menu to use with buttons or other compatible components
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class DropDownMenu extends AVH
{
    use EltDecoration;
    
    const ORIENTATION_DOWN = 'dropdown';
    const ORIENTATION_UP   = 'dropup';
    const ALIGN_LEFT  = 'dropdown-menu-left';
    const ALIGN_RIGHT = 'dropdown-menu-right';
    
    // Compteur d'instances de menus
    protected static $counter = 1;
    
    protected $id = null;
    protected $menu = [];
    
    public function __construct()
    {
        $this->id = self::$counter++;
        $this->initValues([]);
    }
    
    // Not used to build this menu. Must be used by the container component.
    protected $orientation;
    protected $align = '';
    
    public function addLink($label, $url, $disabled = false, array $attributes = [], array $cssClasses = [])
    {
        $this->menu[] = ['link', $label, $url, $disabled, $attributes, $cssClasses];
        return $this;
    }
    
    public function addSeparator()
    {
        $this->menu[] = ['separator'];
        return $this;
    }
    
//    public function addSubMenu()
//    {
//        $subMenu = new self;
//        $this->menu[] = ['menu', $subMenu];
//        return $subMenu;
//    }
    
    public function addHeader($label)
    {
        $this->menu[] = ['header', $label];
        return $this;
    }
    
    /**
     * @return string
     * @deprecated since version 0.1
     */
    public function getOrientationClass()
    {
        return $this->orientation;
    }
    
    /**
     * @return $this
     */
    public function orientationDown()
    {
        $this->orientation = self::ORIENTATION_DOWN;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function orientationUp()
    {
        $this->orientation = self::ORIENTATION_UP;
        return $this;
    }
    
    public function alignLeft()
    {
        $this->align = self::ALIGN_LEFT;
        return $this;
    }
    
    public function alignRight()
    {
        $this->align = self::ALIGN_RIGHT;
        return $this;
    }
    
    /**
     * Label usefull to link menus to the corresponding action buttons
     * @return string
     */
    public function getLabelledBy()
    {
        return 'ddm' . $this->id;
    }
    
    public function render()
    {
        $this->addCssClass('dropdown-menu');
        $this->setAttribute('aria-labelledby', $this->getLabelledBy());
        $this->addCssClass($this->align);
        
        $this->html('<ul' . $this->getEltDecorationStr() . '>');
        foreach ($this->menu as $elt) {
            switch ($elt[0]) {
                case 'link' : 
                    $class = $elt[3] ? ' class="disabled"' : '';
                    $attrs = $elt[4];
                    $css = $elt[5];
                    $elt[2] && $attrs['href'] = $elt[2];
                    $this->html('  <li' . $class . '>' . Html::buildHtmlElement('a', $attrs, $elt[1], true, $css) . '</li>');
                    break;
                case 'separator' : 
                    $this->html('  <li role="separator" class="divider"></li>');
                    break;
                case 'header' : 
                    $this->html('  <li class="dropdown-header">' . $elt[1] . '</li>');
                    break;
//                case 'menu' : 
//                    $this->html((string) $elt[1]);
//                    break;
                default : 
                    throw \Exception('Uknown menu element[' . $elt[0] . ']');
            }
        }
        $this->html('</ul>');
        return $this->getHtml();
    }
}