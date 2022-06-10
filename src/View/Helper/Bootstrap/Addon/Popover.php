<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Component;

/**
 * Popover addon
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Popover
{
    protected $popover;
    protected $popoverOptions = [];
    
    /**
     * Help bubble with title
     * @param string $title
     * @param string $txt
     * @param string $placement top, bottom, left, right
     * @param bool $html to put HTML instead of text
     * @param string $container 'body' or name of the tag to attache to the popover
     * @param int $delay délai de l'animation (ms) 
     * @return $this
     */
    public function setPopover(string $title, string $txt, $placement = null, $html = null, $container = null, $delay = null)
    {
        $this->popover = ['title' => $title, 'data-content' => $txt];
        $this->popoverOptions['title'] = $title;
        $placement !== null && Checkers::checkPlacement($placement);
        $placement !== null && $this->popoverOptions['data-placement'] = $placement;
        $html      !== null && $this->popoverOptions['data-html'] = (int) (bool) $html;
        $container !== null && $this->popoverOptions['data-container'] = (string) $container;
        $delay     !== null && $this->popoverOptions['data-delay'] = (int) $delay;
        return $this;
    }
    
    /**
     * Get attributes and active popovers for jquery
     * @return array
     */
    public function getPopoverAttributes():array
    {
        if ($this->popover !== null) {
            Component::getJquery()->enablePopovers();
            $attrs = $this->popoverOptions;
            $attrs['data-toggle'] = 'popover';
            $attrs['title'] = $this->popover['title'];
            $attrs['data-content'] = $this->popover['data-content'];
            return $attrs;
        }
        return [];
    }
    
    protected function initPopover(array $vars)
    {
        $this->popover = null;
    }
}
