<?php
namespace Osf\View\Component;

use Osf\View\Component;

/**
 * Jquery datepicker (text)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Datepicker extends AbstractComponent implements PickerInterface
{
    public function __construct()
    {
        if (Component::registerComponentScripts()) {
            $this->registerHeadCss('/plugins/datepicker/datepicker3.css', true);
            $this->registerFootJs('/plugins/datepicker/bootstrap-datepicker.js', true);
        }
        $this->registerScript('$.fn.datepicker.defaults.format = "dd/mm/yyyy";');
    }
    
    /**
     * Attach a javascript launcher to the element id
     * @param string $elementId
     * @return $this
     */
    public function registerElementId(string $elementId)
    {
        $this->registerScript('$(\'#' . $elementId . '\').datepicker({autoclose:true,language:\'fr\',orientation:\'bottom auto\'});');
        return $this;
    }
}
