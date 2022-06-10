<?php
namespace Osf\View\Helper\Bootstrap;

use Osf\Container\OsfContainer as Container;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Breadcrumb
 * @author Gérald & Guillaume Ponçon
 * @version 1.0
 * @since CPM-1.0 - Fri Sep 03 21:12:51 GMT+01:00 2010
 * @package application
 * @subpackage helpers
 */
class Breadcrumb extends AVH
{
    use EltDecoration;
    
    protected $links = [];
    protected $active = null;

    /**
     * @param string $label
     * @param string $url
     * @return $this
     */
    public function addLink($label, $url = null)
    {
        $this->links[] = ['label' => $label, 'url' => $url];
        return $this;
    }
    
    /**
     * @param string $label
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return $this
     */
    public function addMvcLink($label, $controller = null, $action = null, array $params = [])
    {
        $url = Container::getViewHelper()->url($controller, $action, $params);
        return $this->addLink($label, $url);
    }
    
    /**
     * @param string $activeLabel
     * @return $this
     */
    public function setActive($activeLabel)
    {
        $this->active = (string) $activeLabel;
        return $this;
    }
    
    /**
     * @return \Osf\View\Helper\Bootstrap\Breadcrumb
     */
    public function __invoke()
    {
        $this->resetDecorations();
        $this->links = [];
        $this->active = null;
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $h = Container::getViewHelper();
        $this->addCssClasses(['breadcrumb', 'hidden-xs', 'hidden-sm']);
        $this->html('<ol' . $this->getEltDecorationStr() . '>');
        $this->html('<li><i class="fa fa-home"></i>&nbsp;&nbsp;<a href="' . $h->baseUrl() . '/">' . __("Home") . '</a></li>');
        foreach ($this->links as $link) {
            if ($link['url'] !== null) {
                $this->html('<li><a href="' . $link['url'] . '">' . $this->esc($link['label']) . '</a></li>');
            } else {
                $this->html('<li>' . $this->esc($link['label']) . '</li>');
            }
        }
        $this->html('<li class="active">' . $this->esc($this->active) . '</li>', $this->active);
        $this->html('</ol>');
        return $this->getHtml();
    }
}