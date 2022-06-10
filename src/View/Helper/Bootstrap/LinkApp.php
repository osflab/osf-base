<?php 

namespace Osf\View\Helper\Bootstrap;

use Osf\Stream\Html;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap link app button (compatible with Link)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class LinkApp extends AVH
{
    use Addon\Url;
    use Addon\Icon;
    use Addon\Badges;
    use Addon\Tooltip;
    use EltDecoration;
    
    protected $label;
    protected $action;
    protected $controller;
    protected $params = [];
    protected $attributes = [];
    protected $htmlElement;
    
    /**
     * Build a link with url helper
     * @param string $label
     * @param string $controller
     * @param string $action
     * @param array $params
     * @param array $attributes
     * @param string $htmlElement
     * @param array $cssClasses
     * @return \Osf\View\Helper\Bootstrap\LinkApp
     */
    public function __invoke(
            string $label, 
                   $controller  = null,
                   $action      = null,
            array  $params      = [],
            array  $attributes  = [],
            string $htmlElement = 'a',
            array  $cssClasses  = [])
    {
        $this->initValues(get_defined_vars());
        $this->label = $this->esc($label);
        $this->controller = $controller === null ? \Osf\Container\OsfContainer::getRequest()->getController() : trim($controller);
        $this->action = $action === null ? null : trim($action);
        $this->params = $params;
        $this->htmlElement = $htmlElement;
        $this->iconIsNullable(false);
        $this->iconSetDefault(AVH::ICON_CIRCLE);
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $label = $this->getBadgesHtml() . $this->getIconHtml() . $this->label;
        $this->addCssClasses(['btn', 'btn-app']);
        $this->url || $this->url($this->getView()->url($this->controller, $this->action, $this->params));
        $this->setAttributes($this->getUrlAttributes(true));
        $this->setAttributes($this->getTooltipAttributes());
        return Html::buildHtmlElement($this->htmlElement, $this->getAttributes(), $label);
    }
}
