<?php
namespace Osf\Application {

    use Laminas\I18n\Translator\Translator;
    use Osf\Container\LaminasContainer;
use Osf\Controller\Router;
use Osf\Container\OsfContainer as Container;
use Osf\Application\Locale;
    use Osf\Exception\ArchException;

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
    
    protected static bool $translatorBuilt = false;

    /**
     * Build default translator and __ function to translate anywhere
     * To call in the subclass
     * @param string $type
     * @param string $pattern
     * @return Translator
     * @throws ArchException
     */
    protected function buildTranslate(
        string $type    = self::DEFAULT_TRANSLATE_TYPE,
        string $pattern = self::DEFAULT_TRANSLATE_PATTERN): Translator
    {
        $lang = Container::getLocale()->getLangKey();
        $translator = LaminasContainer::getTranslate(false);
        $translator->setLocale($lang);
        
        if (defined('APPLICATION_PATH')) {
            $baseDir = APPLICATION_PATH . '/App/' . Router::getDefaultControllerName(true);
            $vTranslationFile = realpath(APPLICATION_PATH 
                    . '/../vendor/laminas/laminas-i18n-resources/languages/'
                    . $lang . '/Laminas_Validate.php');
            $translator->addTranslationFile('PhpArray', $vTranslationFile);
            $translator->addTranslationFilePattern($type, $baseDir, $pattern);
            $translator->setCache(Container::getCache()->getLaminasStorage());
        }
        self::$translatorBuilt = true;
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
    public static function isTranslatorBuilt():bool
    {
        return self::$translatorBuilt;
    }
}

} /* End namespace */

// The __ function for quick translation
namespace {

    use Osf\Application\Bootstrap;
    use Osf\Container\LaminasContainer;

    if (!function_exists('__')) {
        function __($message) {
            if (Bootstrap::isTranslatorBuilt()) {
                return LaminasContainer::getTranslate()->translate($message);
            }
            return $message;
        }
    }
}
