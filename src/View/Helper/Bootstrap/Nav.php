<?php 

namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Helper\Tags\Title;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Navigation tool 
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Nav extends AVH
{
    use Title;
    use EltDecoration;
    use Addon\Icon;
    
    const TYPE_TABS  = 'nav-tabs';
    const TYPE_PILLS = 'nav-pills';
    
    protected static $navId = 0;
    
    protected $type = self::TYPE_TABS;
    protected $stacked = false;
    protected $justified = false;
    protected $items = [];
    protected $activeAutoDetect = true;
    protected $container = true;
    
    public function __construct()
    {
        self::$navId++;
    }
    
    /**
     * Bootstrap nav items
     * @param string $type
     * @param bool $stacked
     * @param bool $justified
     * @param bool $activeAutoDected
     * @return \Osf\View\Helper\Bootstrap\Nav
     */
    public function __invoke(bool $stacked = false, bool $justified = false, bool $activeAutoDected = true)
    {
        $this->initValues(get_defined_vars());
        $this->type = self::TYPE_TABS;
        $this->stacked = $stacked;
        $this->justified = $justified;
        $this->activeAutoDetect = $activeAutoDected;
        $this->container = true;
        $this->items = [];
        return $this;
    }
    
    /**
     * @return $this
     */
    public function typeTabs()
    {
        $this->type = self::TYPE_TABS;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function typePills()
    {
        $this->type = self::TYPE_PILLS;
        return $this;
    }
    
    /**
     * Add a link element to the nav bar
     * @param string $label
     * @param string $url
     * @param bool $active
     * @param bool $disabled
     * @param string $icon
     * @return $this
     */
    public function addLink(
            string $label, 
            string $url, 
            bool $active       = false, 
            bool $disabled     = false, 
            string $icon       = null, 
            string $iconColor  = null, 
            string $badge      = null, 
            string $badgeColor = null)
    {
        Checkers::checkUrl($url);
        $this->items[] = [
            'label'    => $label, 
            'url'      => $url, 
            'active'   => $active, 
            'disabled' => $disabled,
            'icon'     => $icon,
            'icolor'   => $iconColor,
            'badge'    => $badge,
            'bcolor'   => $badgeColor
        ];
        return $this;
    }
    
    /**
     * Add a tab with content (not a link)
     * @param string $label
     * @param string $content
     * @param bool $active
     * @param bool $disabled
     * @return $this
     */
    public function addTab(string $label, string $content, bool $active = false, bool $disabled = false)
    {
        $this->items[] = [
            'label'    => $label, 
            'content'  => $content, 
            'active'   => $active, 
            'disabled' => $disabled
        ];
        return $this;
    }
    
    /**
     * Add a menu element to the nav bar
     * @param string $label
     * @param \Osf\View\Helper\Bootstrap\Addon\DropDownMenu $menu
     * @param bool $active
     * @param bool $disabled
     * @return $this
     */
    public function addMenu(string $label, Addon\DropDownMenu $menu, bool $active = false, bool $disabled = false)
    {
        $this->items[] = [
            'label'    => $label, 
            'menu'     => $menu, 
            'active'   => $active, 
            'disabled' => $disabled
        ];
        return $this;
    }
    
    /**
     * Display or not the container (div)
     * @param bool $container
     * @return $this
     */
    public function setContainer($container = true)
    {
        $this->container = (bool) $container;
        return $this;
    }
    
    /**
     * @param string $title
     * @param string $icon
     * @return $this
     */
    public function title(string $title, string $icon = null)
    {
        $this->setTitle($title);
        $icon && $this->icon($icon);
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setStacked($stacked = true)
    {
        $this->stacked = (bool) $stacked;
        $this->typePills();
        return $this;
    }

    public function getStacked()
    {
        return $this->stacked;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClass('nav');
        $this->addCssClass($this->type == self::TYPE_PILLS ? self::TYPE_PILLS : self::TYPE_TABS);
        $this->addCssClass('nav-stacked', $this->stacked);
        $this->addCssClass('nav-justified', $this->justified);
        $this->addCssClass('pull-right', $this->title || $this->icon);
        $this->html('<div class="nav-tabs-custom">', $this->container && !$this->stacked);
        $this->html('<ul' . $this->getEltDecorationStr() . '>');
        $content = '';
        $tabs = [];
        foreach ($this->items as $key => $item) {
            
            // Vérification de l'item menu
            if (isset($item['menu']) && !($item['menu'] instanceof Addon\DropDownMenu)) {
                throw new \Exception('Unknown item type, DropDownMenu required');
            }
            
            // Item actif si url courante
            if (isset($item['url']) && $this->activeAutoDetect && !$item['active']) {
                $item['active'] = self::isCurrentUrl($item['url']);
            }
            
            // Element li de l'item
            $liClass = isset($item['menu']) 
                     ? ' class="dropdown"' 
                     : ($item['active'] 
                         ? ' class="active"' 
                         : ($item['disabled'] 
                             ? ' class="disabled"' 
                             : ''));
            
            // Contenu du bouton de l'item
            if (isset($item['url'])) {
                $currentLink = '<a href="' . $item['url'] . '">';
                if ($item['icon']) {
                    Checkers::checkIcon($item['icon']);
                    $item['icolor'] && Checkers::checkColor($item['icolor']);
                    $color = $item['icolor'] ? ' text-' . $item['icolor'] : '';
                    $currentLink .= '<i class="fa ' . $item['icon'] . $color . '"></i> ';
                }
                $currentLink .= $item['label'];
                if ($item['badge']) {
                    $item['bcolor'] && Checkers::checkColor($item['bcolor']);
                    $color = $item['bcolor'] ? ' bg-' . $item['bcolor'] : ' bg-black';
                    $currentLink .= '<span class="pull-right badge' . $color . '">' . $this->esc($item['badge']) . '</span>';
                }
                $currentLink .= '</a>';
            } else if (isset($item['menu'])) {
                $currentLink = '<a class="dropdown-toggle" data-toggle="dropdown" href="#" ';
                $currentLink .= 'role="button" aria-haspopup="true" aria-expanded="false">';
                $currentLink .= $item['label'] . ' <span class="caret"></span> </a>' . $item['menu'];
            } else if (isset($item['content'])) {
                $currentLink = '<a href="#tab_' . self::$navId . '_' . $key . '" data-toggle="tab">' . $item['label'] . '</a>';
                $active = $item['active'] ? ' active' : '';
                $content .= '<div class="tab-pane' . $active . '" id="tab_' . self::$navId . '_' . $key . '">';
                $content .= $item['content'];
                $content .= '</div>';
            }
            $tabs[] = '<li role="presentation"' . $liClass . '>' . $currentLink . '</li>';
        }
        $this->title && $tabs = array_reverse($tabs);
        $this->html(implode('', $tabs));
        if ($this->title || $this->icon) {
            $this->html('<li class="pull-left header">');
            $this->html($this->getIconHtml() . ($this->title ? ' ' . $this->title : ''));
            $this->html('</li>');
        }
        $this->html('</ul>');
        
        // Insertion du contenu des tabs
        $this->html('<div class="tab-content">' . $content . '</div>', $content);
        $this->html('</div>', $this->container && !$this->stacked);
        
        return $this->getHtml();
    }
}