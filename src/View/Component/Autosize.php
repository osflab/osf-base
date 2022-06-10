<?php
namespace Osf\View\Component;

use Osf\View\Component;

/**
 * Autosize component (textarea)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 * @link http://www.jacklmoore.com/autosize/ Usage & options
 */
class Autosize extends AbstractComponent
{
    public function __construct()
    {
        if (Component::registerComponentScripts()) {
            $this->registerFootJs('/js/autosize.min.js', true);
        }
        $this->registerScript("autosize($('textarea'));");
    }
//    
//    public function registerSelect(ElementSelect $select)
//    {
//        $options = [
//            //'create: true'
//            ];
//        if (!$select->getRequired()) {
//            $options[] = 'allowEmptyOption: true';
//        }
//        if ($select->getMaxItems()) {
//            $options[] = 'maxItems: ' . $select->getMaxItems();
//        }
//        $this->registerScript($this->createScript($select->getName(), $options));
//    }
//    
//    public function registerTags(ElementTags $element)
//    {
//        $options = [
//            'create: true',
//            'createOnBlur : true',
//            'persist: false',
//            'delimiter: ","'
//            ];
//        $this->registerScript($this->createScript($element->getName(), $options));
//    }
}
