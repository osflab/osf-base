<?php
namespace Osf\Application {

use Osf\Container\ZendContainer;
use Osf\Controller\Router;
use Osf\Container\OsfContainer as Container;
use Osf\Application\Locale;

/**
 * Bootstrap super class for application bootstraps
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 sept. 2013
 * @package osf
 * @subpackage application
 */
abstract class Bootstrap
{
    const DEFAULT_TRANSLATE_TYPE = 'gettext';
    const DEFAULT_TRANSLATE_PATTERN = '/Config/translate/%s.mo';
    
    abstract public function bootstrap();
    
    protected static $translatorBuilded = false;
    
    /**
     * Build default translator and __ function to translate anywhere
     * To call in the subclass
     * @param string $type
     * @param string $pattern
     * @return \Zend\I18n\Translator\Translator
     */
    protected function buildTranslate(
        $type    = self::DEFAULT_TRANSLATE_TYPE, 
        $pattern = self::DEFAULT_TRANSLATE_PATTERN)
    {
        $lang = Container::getLocale()->getLangKey();
        $translator = ZendContainer::getTranslate(false);
        $translator->setLocale($lang);
        
        if (defined('APPLICATION_PATH')) {
            $baseDir = APPLICATION_PATH . '/App/' . Router::getDefaultControllerName(true);
            $vTranslationFile = realpath(APPLICATION_PATH 
                    . '/../vendor/zendframework/zend-i18n-resources/languages/' 
                    . $lang . '/Zend_Validate.php');
            $translator->addTranslationFile('PhpArray', $vTranslationFile);
            $translator->addTranslationFilePattern($type, $baseDir, $pattern);
            $translator->setCache(Container::getCache()->getZendStorage());
        }
        self::$translatorBuilded = true;
        return $translator;
    }
    
    /**
     * Locale bootstrap
     * To call in the subclass
     * @return \Locale
     */
    public function buildLocale($auto = false, $default = 'fr-FR')
    {
        if ($auto && ($lang = filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE'))) {
            Locale::acceptFromHttp($lang);
        } else {
            Locale::setDefault($default);
        }
        if (Locale::getDefault() === 'fr-FR') {
            date_default_timezone_set('Europe/Paris');
            setlocale(LC_TIME, 'fr_FR.UTF-8','fra');
        }
        return Container::getLocale();
    }
    
    /**
     * @return bool
     */
    public static function isTranslatorBuilded():bool
    {
        return self::$translatorBuilded;
    }
}

} /* End namespace */

// The __ function for quick translation
namespace {
    if (!function_exists('__')) {
        function __($message) {
            if (\Osf\Application\Bootstrap::isTranslatorBuilded()) {
                return \Osf\Container\ZendContainer::getTranslate()->translate($message);
            }
            return $message;
        }
    }
}
