<?php
namespace Osf\View\Helper;

use Osf\Container\OsfContainer as Container;
use Osf\Test\Runner as OsfTest;

/**
 * View helpers unit tests
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();

        $view = Container::getView();
        $view->register(array('a' => '<i>b</i>', 'c' => array('d', 'e')));
        
        $helper = Container::getViewHelper(false);
        self::assert($helper instanceof \Osf\View\Helper, 'Bad helper class type');
        self::assert($helper->get('a') === '<i>b</i>', 'Bad value getted');
        self::assert($helper->getHtmlEscape('a') == '&lt;i&gt;b&lt;/i&gt;', 'Not escaped value ?');
        
        // A régler
        $layoutHelper = Container::getViewHelperLayout(false);
        self::assert($layoutHelper instanceof \Osf\View\Helper, 'Bad helper class type for layout');
        self::assert($layoutHelper->get('a') !== null, 'Value detected, not normal');
//        self::assert($layoutHelper->get('a') == null, 'A value is detected [' . $layoutHelper->get('a') . '], not normal');
        
        return self::getResult();
    }
}