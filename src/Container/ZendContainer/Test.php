<?php
namespace Osf\Container\LaminasContainer;

use Osf\Container\LaminasContainer;
use Osf\Test\Runner as OsfTest;

/**
 * Zend container unit tests
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
        
        if (class_exists('Zend\I18n\Translator\Translator')) {
            
            // Needed components
            self::assert(extension_loaded('intl'), 'intl extension for Zend Translator is not loaded');

            // Registered object
            $translator = ZendContainer::getTranslate(false);
            self::assert($translator instanceof \Laminas\I18n\Translator\Translator, 'Translator not found');
        }
        
        return self::getResult();
    }
}
