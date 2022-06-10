<?php
namespace Osf\View\Helper;

use Osf\Stream\Html;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Controller\Router;

/**
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 * @task [LINK] Lien a déplacer dans bootstrap s'il y a des décorations bootstrap
 */
class Link extends AbstractViewHelper
{
    use EltDecoration;
    use Bootstrap\Addon\Tooltip;
    use Bootstrap\Addon\Popover;
    use Bootstrap\Addon\Url;
    use Addon\MvcUrl;
    
    protected $label;
    protected $htmlElement;
    protected $blank = false;
    protected $absolute;
    protected $isAjaxLink = false;
    
    /**
     * Build a link with url helper
     * @param string $label
     * @param string $controller
     * @param string $action
     * @param array $params
     * @param array $attributes
     * @param string $htmlElement
     * @param array $cssClasses
     * @return \Osf\View\Helper\Link
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
        $this->label = $label;
        $this->htmlElement = $htmlElement;
        $this->blank = false;
        $this->absolute = false;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setHtmlElement(string $htmlElement = null)
    {
        $this->htmlElement = $htmlElement;
        return $this;
    }

    public function getHtmlElement()
    {
        return $this->htmlElement;
    }
    
    /**
     * @return $this
     */
    public function setBlank(bool $blank = true)
    {
        $this->blank = $blank;
        return $this;
    }

    public function getBlank()
    {
        return $this->blank;
    }
    
    /**
     * @return $this
     */
    public function setAbsolute($absolute = true)
    {
        $this->absolute = (bool) $absolute;
        return $this;
    }
    
    /**
     * Is it an ajax link ? (only for MVC links)
     * @param bool|null $isAjaxLink
     * @return $this
     */
    public function setIsAjaxLink(bool $isAjaxLink = true)
    {
        $this->isAjaxLink = $isAjaxLink;
        return $this;
    }

    /**
     * Link with absolute url ?
     * @return bool
     */
    public function getAbsolute(): bool
    {
        return (bool) $this->absolute;
    }
    
    public function render()
    {
        $hrefUrl = false;
        if (!$this->url) {
            $this->url($this->getMvcUrl());
            $hrefUrl = !$this->isAjaxLink;
        }
        if ($hrefUrl && $this->absolute) {
            $this->url = Router::getHttpHost() . $this->url;
        }
        if (!$hrefUrl) {
            $this->addCssClass('clickable');
        }
        $this->getBlank() && $hrefUrl && $this->addCssClass('extlink')->setAttribute('target', '_blank');
        $this->setAttributes($this->getUrlAttributes($hrefUrl, false));
        $this->setAttributes($this->getTooltipAttributes());
        $this->setAttributes($this->getPopoverAttributes());
        return Html::buildHtmlElement($this->htmlElement, $this->getAttributes(), $this->label);
    }
}
