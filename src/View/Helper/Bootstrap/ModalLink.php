<?php 

namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Stream\Html;
use Osf\View\Component;
use Osf\Container\OsfContainer as Container;

/**
 * Bootstrap alert message
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class ModalLink extends AVH
{
    use EltDecoration;
    use Addon\Modal;
    
    protected $label;
    protected $htmlElement;
    protected $loadUrl;
    
    /**
     * Build a link with url helper
     * @param string $label
     * @param string $modalId
     * @param array $attributes
     * @param string $htmlElement
     * @return \Osf\View\Helper\Bootstrap\ModalLink
     */
    public function __invoke(
            string $label,
            string $modalId,
            array  $attributes  = [],
            string $htmlElement = 'a', 
            bool   $escapeLabel = true)
    {
        $this->initValues(get_defined_vars());
        $this->label = $escapeLabel ? $this->esc($label) : (string) $label;
        $this->htmlElement = $htmlElement;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setLoadUrl($loadUrl)
    {
        $this->loadUrl = $loadUrl;
        return $this;
    }

    public function getLoadUrl()
    {
        return $this->loadUrl;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        if ($this->getLoadUrl()) {
            $load = Container::getViewHelper()->load();
            $js = '$("#' . $this->modalId . '")'
                    . '.on("show.bs.modal",function(e){'
                    . '$("#' . $this->modalId . ' .modal-body")'
                    . '.html(\'' . $load . '\')'
                    . '.load("' . $this->getLoadUrl() . '")});';
            Component::getJquery()->registerScript($js);
        }
        $this->setAttributes($this->getModalAttributes());
        $this->setAttribute('href', '#');
        $attributes = $this->getAttributes();
        return Html::buildHtmlElement($this->htmlElement, $attributes, $this->label, false);
    }
}
