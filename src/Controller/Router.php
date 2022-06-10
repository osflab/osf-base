<?php
namespace Osf\Controller;

use Osf\Container\OsfContainer as Container;

/**
 * Default HTTP router
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage controller
 */
class Router
{
    const DEFAULT_CONTROLLER = 'common';
    const DEFAULT_ACTION = 'index';
    
    protected $baseUrl = null;
    protected $params = array();
    
    /**
     * @param array $params
     */
    public function __construct(array $params = array())
    {
        $this->params = $params;
    }
    
    /**
     * Get the application URI calculated from REQUEST_URI
     * @return string
     */
    public function getAppUri()
    {
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $scriptPath = $this->getBaseUrl();
        $appUri = substr($uri, strlen($scriptPath));
        return $appUri === false ? '' : $appUri;
    }
    
    /**
     * Get the application base url
     * @return string
     * @task [ROUTER] gerer https
     */
    public function getBaseUrl(bool $withHostName = false):string
    {
        // Calcul du hostname si pas déjà fait
        if (!$this->baseUrl) {
            $this->baseUrl = dirname(filter_input(INPUT_SERVER, 'SCRIPT_NAME'));
        }
        
        // Retour
        return ($withHostName ? self::getHttpHost() : '') . $this->baseUrl;
    }
    
    /**
     * Get http(s)://[hostname]
     * @staticvar type $hostname
     * @return string
     * @task [router] https
     */
    public static function getHttpHost():string
    {
        static $hostname = null;
        
        if (!$hostname) {
            $http = filter_input(INPUT_SERVER, 'HTTPS') ? 'https://' : 'http://';
            $hostname = $http . filter_input(INPUT_SERVER, 'HTTP_HOST');
        }
        return $hostname;
    }
    
    /**
     * Fix the base Url (not recommended, usefull for tests)
     * @param string $fixedBaseUrl
     * @return Router
     */
    public function setBaseUrl($fixedBaseUrl)
    {
        $this->baseUrl = (string) $fixedBaseUrl;
        return $this;
    }
    
    /**
     * Update Request with params from url
     * @param string $uri
     * @return Router
     */
    public function route($uri = null)
    {
        // Build app uri if not specified
        if ($uri === null) {
            $uri = $this->getAppUri();
        }
        
        // Clean request : remove & from jquery ajax nocache
        //$uri = ltrim(preg_replace('/^([^&]*)&.*$/', '$1', $uri), '/');
        
        $urlElements = parse_url($uri);
        $uri      = isset($urlElements['path'])     ? $urlElements['path']     : '';
        $query    = isset($urlElements['query'])    ? $urlElements['query']    : null;
        $fragment = isset($urlElements['fragment']) ? $urlElements['fragment'] : null;
        
        // /xx/yy/a/b/c/d -> $params
        $urlTab = explode('/', trim($uri, ' /'));
        $request = Container::getRequest();
        $request->setUri($uri);
        $request->setBaseUrl($this->getBaseUrl());
        $controller = array_shift($urlTab);
        
        // Find action
        $action = array_shift($urlTab);
        
        // Ajout du controleur, de l'action et des paramètres
        $request->setController($controller ? $controller : self::DEFAULT_CONTROLLER);
        $request->setAction($action ? $action : self::DEFAULT_ACTION);
        while (count($urlTab)) {
            $key = trim(array_shift($urlTab));
            $value = array_shift($urlTab);
            if ($value !== null) {
                $request->setParam($key, $value);
            }
        }
        
        // Ajout des paramètres additionnels sous la forme id=12&sort=name
        if ($query !== null) {
            $params = null;
            parse_str($query, $params);
            foreach ($params as $key => $value) {
                $request->setParam($key, $value);
            }
        }
        
        return $this;
    }
    
    protected function prepareUri($params, $controller, $action)
    {
        // Récupération des paramètres par défaut
        if ($params === null) {
            $params = Container::getRequest()->getParams();
        }
        $request = Container::getRequest();
        $controller = $controller ?? $request->getController();
        $action     = $action     ?? $request->getAction();
        $uriParams  = array($params, $controller, $action);
        
        return $uriParams;
    }
    
    /**
     * Build an URL from specified params
     * @param array $params
     * @param string $controller
     * @param string $action
     * @param bool $prepareUri
     * @return string
     */
    public function buildUri(array $params = null, $controller = null, $action = null, $prepareUri = true)
    {
        // Récupération et initialisation de la requête
        $request = Container::getRequest();
        $request->setBaseUrl($this->getBaseUrl());
        
        // Récupération des paramètres par défaut
        if ($prepareUri) {
            [$params, $controller, $action] = $this->prepareUri($params, $controller, $action);
        } else {
            $params = $params ?? [];
            $controller = $controller ?? self::DEFAULT_CONTROLLER;
            $action = $action ?? self::DEFAULT_ACTION;
        }
        
        // Correction des paramètres
        if (!is_array($params)) {
            $params = [];
        }
        if (isset($params['controller']) && ($controller === self::DEFAULT_CONTROLLER)) {
            $controller = $params['controller'];
            unset($params['controller']);
        }
        if (isset($params['action']) && ($action === self::DEFAULT_ACTION)) {
            $action = $params['action'];
            unset($params['action']);
        }
        
        // Données pour la construction de l'uri
        $hasParams = count($params);
        $hasController = $controller != self::DEFAULT_CONTROLLER;
        $hasAction = $action != self::DEFAULT_ACTION;
        
        // Construction de l'uri
        $uri  = $hasParams || $hasAction || $hasController ? '/' . $controller : '/';
        $uri .= $hasParams || $hasAction ? '/' . $action : '';
        foreach ($params as $key => $value) {
            $uri .= '/' . $key . '/' . $value;
        }
        
        $uri = '/' . ltrim(rtrim($request->getBaseUrl(), '/') . $uri, '/');
        return $uri;
    }

    /**
     * @staticvar string|null $ucfName
     * @param bool $ucFirst
     * @return string
     */
    public static function getDefaultControllerName(bool $ucFirst = false)
    {
        static $ucfName = null;
        
        if ($ucFirst) {
            if ($ucfName === null) {
                $ucfName = ucfirst(self::DEFAULT_CONTROLLER);
            }
            return $ucfName;
        }
        return self::DEFAULT_CONTROLLER;
    }
    
    /**
     * @staticvar type $ucfName
     * @param bool $ucFirst
     * @return string
     */
    public static function getDefaultActionName(bool $ucFirst = false)
    {
        static $ucfName = null;
        
        if ($ucFirst) {
            if ($ucfName === null) {
                $ucfName = ucfirst(self::DEFAULT_ACTION);
            }
            return $ucfName;
        }
        return self::DEFAULT_ACTION;
    }
}