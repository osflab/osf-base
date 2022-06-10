<?php
namespace Osf\Application;

use Osf\Exception\HttpException;
use Osf\Exception\ArchException;
use Osf\Container\PluginManager;
use Osf\Container\OsfContainer as Container;
use Osf\Controller\Router;
use Osf\Controller\Request;
use Osf\Controller\Response;
use Exception;

/**
 * Simple application manager
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage application
 */
class OsfApplication
{
    use Addon\Env;
    
    private const DEBUG = false;
    
    const ENV_DEV = 'development';
    const ENV_STG = 'staging';
    const ENV_PRD = 'production';
    const ENV_DEM = 'demo';

    const RENDER_VIEW = 'View';
    const RENDER_LAYOUT = 'Layout';
    const RENDER_RESPONSE = 'Response';
    
    protected $renderView     = true;
    protected $renderLayout   = true;
    protected $renderResponse = true;
    
    protected $dispatched = false;
    
    /**
     * bootstrap + route + dispatch
     * @return $this
     * @throws \Exception
     */
    public function run()
    {
        $this->init();
        try {
            $this->bootstrap();
            $this->route();
            $this->dispatch();
        } catch (Exception $e) {
            if (APPLICATION_ENV == self::ENV_DEV) {
                var_dump($e);
            } else {
                throw $e;
            }
        }
        return $this;
    }
    
    /**
     * Environment checking
     * @return $this
     * @throws ArchException
     */
    public function init()
    {
        // "applications" directory
        if (!defined('APPLICATION_PATH')) {
            throw new ArchException('Please define the APPLICATION_PATH constant');
        }
        
        // production, staging, demo or development
        if (!defined('APPLICATION_ENV')) {
            $env = getenv('APPLICATION_ENV');
            if ($env && !in_array($env, [self::ENV_DEV, self::ENV_STG, self::ENV_DEM, self::ENV_PRD])) {
                throw new ArchException('Execution environment "' . $env . '" is not correct. '
                            . 'Please choose one of theses : ' . self::ENV_DEV . ', ' . self::ENV_STG . ', ' 
                            . self::ENV_DEM . ' or ' . self::ENV_PRD);
            }
            define('APPLICATION_ENV', $env ?: self::ENV_PRD);
        }
        
        return $this;
    }
    
    /**
     * Configuration + Bootstraping
     * @return $this
     * @throws ArchException
     */
    public function bootstrap()
    {
        // Get the application configuration
        $config = Container::getConfig();
        $commonConfigFile = APPLICATION_PATH . '/App/' . Router::getDefaultControllerName(true) . '/Config/application.php';
        $config->appendConfig(require $commonConfigFile);
        
        // Bootstrapping
        $bootstrapFile = APPLICATION_PATH . '/App/' . Router::getDefaultControllerName(true) . '/Bootstrap.php';
        if (!file_exists($bootstrapFile)) {
            throw new ArchException('Bootstrap file ' . $bootstrapFile . ' is needed');
        }
        
        //self::debug('Begin bootstrap');
        Container::getBootstrap()->bootstrap();
        //self::debug('End bootstrap');
        return $this;
    }
    
    /**
     * Call router before dispatching
     * @return $this
     */
    public function route()
    {
        PluginManager::handleApplicationPlugins(PluginManager::STEP_BEFORE_ROUTE);
        Container::getRouter()->route();
        self::debug('Route: ' . Container::getRouter()->buildUri());
        PluginManager::handleApplicationPlugins(PluginManager::STEP_AFTER_ROUTE);
        return $this;
    }
    
    /**
     * Launch the dispatch loop
     * @throws \Exception
     * @return $this
     */
    public function dispatch()
    {
        $request  = Container::getRequest();
        $response = Container::getResponse();
        
        PluginManager::handleApplicationPlugins(PluginManager::STEP_BEFORE_DISPATCH_LOOP);
        //self::debug('Begin dispatch loop...');
        
        // Dispatching loop
        while (!$this->dispatched) {
            
            try {
                PluginManager::handleApplicationPlugins(PluginManager::STEP_BEFORE_ACTION);
                //self::debug('Begin dispatch ' . $request->getController() . '::' . $request->getAction() . ' [' . json_encode($request->getParams()) . ']');
                $this->dispatchProcess($request, $response);
                //self::debug('End dispatch ' . $request->getController() . '::' . $request->getAction() . ' [' . json_encode($request->getParams()) . ']');
                PluginManager::handleApplicationPlugins(PluginManager::STEP_AFTER_ACTION);
            } 
            
            // Forward exception to error action and dispatch
            catch (Exception $e) {
                if ($request->getController(false) == 'event' 
                 && $request->getAction(false) == 'error') {
                    throw $e;
                }
                Container::getView()->addValue('exception', $e);
                $this->renderView = true;
                $request->setController('event')->setAction('error');
                $this->setDispatched(false);
            }
        }
        PluginManager::handleApplicationPlugins(PluginManager::STEP_AFTER_DISPATCH_LOOP);
        self::debug('End dispatch loop');

        // Layout render -> response
        if ($this->dispatched && $this->renderLayout) {
            $layout = Container::getConfig()->getConfig('layout');
            if ($layout !== null) {
                if (!file_exists($layout)) {
                    throw new ArchException('Layout file ' . $layout . ' not found');
                }
                $response->setBody(Container::getLayout(true)->render($layout));
            }
        }
        
        // Final rendering
        if ($this->renderResponse) {
            
            // Envoi des headers
            $response->fixHeaders()->putTypeHeadersInResponse();
            foreach ($response->getHeaders() as $header) {
                header($header);
            }
            
            // Exécution du callback
            $response->executeCallback();
            
            // envoi du body s'il existe
            if ($response->hasBody()) {
                echo $response->getBody();
            }
        }
        return $this;
    }
  
    /**
     * Set on or off dispatch step (view rendering, layout rendering and response send)
     * @param string $step
     * @param boolean $enable
     * @throws ArchException
     * @return $this
     */
    public function setDispatchStep(string $step, $enable)
    {
        switch ($step) {
            case self::RENDER_VIEW     : $this->renderView     = (bool) $enable; break;
            case self::RENDER_LAYOUT   : $this->renderLayout   = (bool) $enable; break;
            case self::RENDER_RESPONSE : $this->renderResponse = (bool) $enable; break;
            default : throw new ArchException('Dispatch step not found');
        }
        return $this;
    }
    
    /**
     * Set dispatch loop status
     * @param boolean $dispatched
     * @return $this
     */
    public function setDispatched(bool $dispatched)
    {
        $this->dispatched = $dispatched;
        return $this;
    }
    
    /**
     * Dispatch iteration
     * @param \Osf\Controller\Request $request
     * @param \Osf\Controller\Response $response
     * @throws ArchException
     * @throws HttpException
     * @return $this
     */
    protected function dispatchProcess(Request $request, Response $response)
    {
        // Controller and action detection
        if ($request->getController() === null || $request->getAction() === null) {
            throw new ArchException('Controller or action not defined. Router problem?');
        }
        $actionMethod = $request->getAction() . 'Action';

        // Start buffering and set dispatched true to enable action/view/layout execution
        ob_start();
        $this->dispatched = true;

        // Controller loading
        $controllerName = $request->getController();
        if (!$controllerName) {
            throw new ArchException('Controller is undefined');
        }
        $controller = Container::getController($controllerName);
        if (!($controller instanceof \Osf\Controller\Action)) {
            throw new ArchException('Your controller class for application ' . $request->getController() . ' must extends \Osf\Controller\Action');
        }
        if (!method_exists($controller, $actionMethod)) {
            throw new HttpException('Action ' . $actionMethod . '() not found in controller class of application ' . $request->getController(), 404);
        }

        // Execution
        if ($this->dispatched) {
            
            // Call plugins and action
            $controller->preDispatch();
            self::debug('Call ' . $controllerName . '::' . $actionMethod . '()');
            $viewParams = $controller->$actionMethod();
            $controller->postDispatch();
            
            // Put in the body content outputed directly in action (not in view)
            $content = ob_get_clean();
            $content && $response->appendBody($content);
            if ($viewParams === null) {
                $viewParams = [];
            } elseif (!is_array($viewParams)) {
                throw new ArchException('Action must return an array or null');
            }
        } else {
            self::debug('No call of ' . $controllerName . '::' . $actionMethod . '(), not dispatched');
            ob_clean();
        }

        // View render -> response
        if ($this->dispatched && $this->renderView) {
            $response->appendBody(Container::getView()->register($viewParams)->render());
        }

        return $this;
    }
    
    protected static function debug(string $msg): void
    {
        self::DEBUG && file_put_contents(sys_get_temp_dir() . '/app-ctrl.log', date('Ymd-His ') . trim($msg) . "\n", FILE_APPEND);
    }
}
