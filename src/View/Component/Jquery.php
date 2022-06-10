<?php
namespace Osf\View\Component;

use Osf\View\Component;

/**
 * Jquery component
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Jquery extends AbstractComponent
{
    public function __construct()
    {
        if (Component::registerComponentScripts()) {
            $this->registerFootJs('/plugins/jQuery/jquery-2.2.3.min.js', true);
        }
    }
    
    /**
     * @staticvar boolean $activated
     * @return $this
     */
    public function enablePopovers()
    {
        static $enabled = false;
        
        if (!$enabled) {
            $this->registerScript("$(function(){\$('[data-toggle=\"popover\"]').popover()})");
            $enabled = true;
        }
        
        return $this;
    }
    
    /**
     * Activate focus on elements with the attribute "autofocus"
     * @staticvar boolean $activated
     * @return $this
     */
    public function enableAutofocus()
    {
        static $enabled = false;
        
        if (!$enabled) {
            $script = "$(document).ready(function(){"
                    . "\$(this).find('[autofocus]:first').focus();"
                    . "});";
            $this->registerScript($script);
            $enabled = true;
        }
        
        return $this;
    }
}
