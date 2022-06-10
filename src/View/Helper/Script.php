<?php
namespace Osf\View\Helper;

use Osf\Stream\Html;
use Osf\View\Component;
use Osf\View\HelperInterface;

/**
 * Javascript inclusion in header or footer of the page
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Script extends AbstractViewHelper
{
    protected $headScript = [];
    protected $footScript = [];
    
    /**
     * @return \Osf\View\Helper\Script
     */
    public function __invoke()
    {
        return $this;
    }
    
    public function addHeadScript($script) 
    {
        $this->headScript[] = trim($script);
    }
    
    public function addFootScript($script)
    {
        $this->footScript[] = trim($script);
    }
    
    protected function buildScripts(array $scripts)
    {
        return Html::buildHtmlScript(implode("\n", $scripts));
    }
    
    public function registerComponentsScripts(HelperInterface $layout)
    {
        return Component::registerScripts($layout);
    }
    
    public function buildHeadScripts()
    {
        return $this->buildScripts($this->headScript);
    }
    
    public function buildFootScripts()
    {
        return $this->buildScripts($this->footScript);
    }
}
