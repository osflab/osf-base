<?php

namespace Osf\Controller {

    use Osf\Console\ConsoleHelper as ConsoleBase;
    use Osf\Application\OsfApplication as Application;
    use Osf\Generator\DbGenerator;
    use Osf\Generator\LaminasGenerator;
    use Osf\Generator\OsfGenerator;
    use Osf\Container\LaminasContainer;
    use Osf\Stream\Text as T;
    use Osf\Test\Runner as OsfTest;

    /**
     * Command line controller
     * 
     * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
     * @copyright OpenStates
     * @version 1.0
     * @since OSF-2.0 - 11 sept. 2013
     * @package osf
     * @subpackage controller
     */
    class Cli extends ConsoleBase
    {
        const MAX_LOAD_AVERAGE = 2;

        // Configuration properties: be completed in application Cli class
        protected static $configFile      = null; 
        protected static $databases       = null;
        protected static $generators      = [];
        protected static $deferredActions = [];

        /**
         * Copy this function in classes extended classes
         * @return string
         */
        protected static function getCurrentClass()
        {
            return __CLASS__;
        }

        // GENERAL ACTIONS

        /**
         * Run unit tests of the application.
         */
        protected static function testAction()
        {
            $args = func_get_args();
            $rootPath = self::getRootPath($args);
            self::display("Running tests in " . $rootPath);
            set_include_path($rootPath . ':' . get_include_path());
            OsfTest::runDirectory($rootPath);
        }

        /**
         * Execute deferred actions (log register, cache generation...)
         */
        protected static function tickAction()
        {
            // Si la charge machine est trop importante, ces actions secondaires 
            // ne sont pas exécutées
            $load = sys_getloadavg();
            if ($load[0] >= self::MAX_LOAD_AVERAGE) {
                $percent = T::percentageFormat($load[0] * 100);
                self::display('Load average too high to execute deferred actions (' . $percent . ')');
                return;
            }

            // Pas d'actions différées
            if (!self::$deferredActions) {
                self::display('No deferred action');
                return;
            }

            // Exécution des actions différées enregistrées
            foreach (self::$deferredActions as $class) {
                $action = new $class();
                if (!($action instanceof Cli\DeferredActionInterface)) {
                    self::displayError($action . ' is not a deferred action class');
                    continue;
                }
                echo self::beginActionMessage($action->getName());
                switch ($action->execute()) {
                    case true  : echo self::endActionOK();   break;
                    case null  : echo self::endActionSkip(); break;
                    default    : echo self::endActionFail();
                }
                foreach ($action->getMessages() as $msg) {
                    self::display($msg);
                }
                foreach ($action->getErrors() as $msg) {
                    self::displayError($msg);
                }
            }
        }

        // DEFERRED REGISTRATION

        protected static function registerDeferredClass($className)
        {
            self::$deferredActions[] = $className;
        }

        // GENERATORS

        /**
         * Generate DB models, helpers and auto-updatable classes
         */
        protected static function generateAction()
        {
            self::checkOnlyDevEnv();
            $generators = array_merge(['all', 'db', 'osf', 'zend'], static::$generators);
            $args = func_get_args();
            if (!isset($args[0]) || !in_array($args[0], $generators)) {
                self::displayError('specify an item to generate (' . implode('|', $generators) . ')');
            }
            if ($args[0] == 'all') {
                foreach ($generators as $genKey) {
                    if ($genKey == 'all') {
                        continue;
                    }
                    self::lauchGenerator($genKey);
                }
            } else {
                self::lauchGenerator($args[0]);
            }
        }

        protected static function lauchGenerator($genKey)
        {
            $generatorMethod = 'generate' . T::camelCase($genKey);
            if (!method_exists(__CLASS__, $generatorMethod)) {
                self::displayError('Generator [' . $genKey . '] do not exists. Bad generators configuration.');
            }
            return self::$generatorMethod();
        }

        protected static function generateDb()
        {
            if (static::$configFile) {
                \Osf\Container\OsfContainer::getConfig()->appendConfig(include static::$configFile);
            }

            if (!isset(static::$databases[0])) {
                echo self::beginActionMessage('Databases generator: no database configured');
                echo self::endActionSkip();
                return;
            }

            foreach (static::$databases as $db) {
                echo self::beginActionMessage('Database models generation: ' . $db['comment']);
                try {
                    (new DbGenerator(ZendContainer::getDbAdapterFromKey($db['adapter']), $db['generatorParams']))->generateClasses();
                } catch (\Exception $e) {
                    echo self::endActionFail();
                    self::displayError($e->getMessage());
                }
                echo self::endActionOK();
            }

        }

        protected static function generateZend()
        {
            $zendGenerators = [
                'Filters', 'Validators', 
                'ViewHelpers', 'FormElements'
            ];
            foreach ($zendGenerators as $name) {
                echo self::beginActionMessage('Extractions from ZF: ' . $name);
                try {
                    $method = 'generate' . $name;
                    (new ZendGenerator())->$method();
                } catch (\Exception $e) {
                    echo self::endActionFail();
                    self::displayError($e->getMessage());
                }
                echo self::endActionOK();
            }
        }

        protected static function generateOsf()
        {
            echo self::beginActionMessage('OpenStates Framework Helpers');
            try {
                (new OsfGenerator())->generateAll();
                echo self::endActionOK();
            } catch (\Exception $e) {
                echo self::endActionFail();
                self::displayError($e->getMessage());
            }
        }

        // TOOLS

        /**
         * Detect the root path of the current framework.
         * @param array $args arguments of the command ligne.
         * @return string
         */
        protected static function getRootPath($args = null)
        {
            static $path = null;

            if (isset($args[0])) {
                $rootPath = (string) $args[0];
            } else {
                if ($path !== null) {
                    return $path;
                }
                if (defined('APP_PATH')) {
                    $rootPath = APP_PATH;
                } else {
                    $rootPath = $_SERVER['PWD'];
                }
                $path = $rootPath;
            }
            return $rootPath;
        }

        /**
         * Check if the zend framework url of the configuration file is OK.
         * @param string $url
         */
        protected static function checkZendFramework($url)
        {
            static $checked = null;

            if ($checked === null) {
                self::display('Testing zend framework access...');
                if (!file_get_contents($url, null, null, null, 10)) {
                    self::displayError('Unable to access Zend Framework libraries from this server !');
                }
                $checked = true;
            }
        }

        /**
         * Display an error and exit if not a development environment
         * @return void
         */
        protected static function checkOnlyDevEnv(): void
        {
            $env = defined('APPLICATION_ENV') ? APPLICATION_ENV : (getenv('APPLICATION_ENV') ?? null);
            if ($env !== Application::ENV_DEV && !defined('OSF_INSTALL')) {
                self::displayError('Use this command only in development environment');
            }
        }
    }
}

// Call of __ or simulation if the application component is not available
namespace {
    if (class_exists('\Osf\Application\Bootstrap')) {
        Osf\Application\Bootstrap::isTranslatorBuilt();
    }
    if (!function_exists('__')) {
        function __($txt) {
            return $txt;
        }
    }
}
