<?php
namespace Osf\Controller;

/**
 * HTTP request
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage controller
 */
class Request
{
    const PARAM_CONTROLLER = 'controller';
    const PARAM_ACTION     = 'action';
    
    protected $actionParams = []; // Controller, Action
    protected $params = [];       // HTTP Params from router
    protected $uri = null;
    protected $baseUrl = null;
    
    /**
     * Get URI without baseurl
     * @return string
     * @task [URI] Voir comment gérer le baseUrl '/'
     */
    public function getUri($withBaseUrl = false)
    {
        $prefix = $withBaseUrl ? $this->getBaseUrl() : '';
        $uri = $prefix . $this->uri;
        $uri = '/' . ltrim($uri, '/');
        return $uri;
    }

    /**
     * Get base url without hostname
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Get current params
     * @return multitype:
     */
    public function getParams($withActionParams = false)
    {
        return $withActionParams ? array_merge($this->actionParams, $this->params) : $this->params;
    }
    
    /**
     * Get param value. Get action param if key = controller or action
     * @param string $key
     * @return string|null
     */
    public function getParam(string $key)
    {
        $isActionParam = in_array($key, [self::PARAM_CONTROLLER, self::PARAM_ACTION]);
        $params = $isActionParam ? $this->actionParams: $this->params;
        return array_key_exists($key, $params) ? $params[$key] : null;
    }
    
    // Global params
    
    /**
     * @param array $params
     * @param bool $merge
     * @return $this
     */
    public function setParams(array $params, bool $merge = true)
    {
        if (!$merge) {
            $this->params = [];
        }
        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParam(string $key, string $value)
    {
        if ($key == self::PARAM_CONTROLLER
        ||  $key == self::PARAM_ACTION) {
            $this->setActionParam($key, $value);
        } else {
            $this->params[$key] = $value;
        }
        return $this;
    }
    
    // Action params
    
    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    protected function setActionParam(string $key, string $value)
    {
        $this->actionParams[$key] = trim($value);
        return $this;
    }
    
    /**
     * @param string $controller
     * @return $this
     */
    public function setController(string $controller)
    {
        $this->setActionParam(self::PARAM_CONTROLLER, $controller);
        return $this;
    }
    
    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->setActionParam(self::PARAM_ACTION, $action);
        return $this;
    }
    
    /**
     * @param string $key
     * @param string $ucFirst
     * @return string|null
     */
    protected function getActionParam(string $key) 
    {
        return isset($this->actionParams[$key]) ? $this->actionParams[$key] : null;
    }
    
    /**
     * @param bool $ucFirst
     * @return string|null
     */
    public function getController()
    {
        return $this->getActionParam(self::PARAM_CONTROLLER);
    }
    
    /**
     * @param bool $ucFirst
     * @return string|null
     */
    public function getAction()
    {
        return $this->getActionParam(self::PARAM_ACTION);
    }

    /**
     * Clean request content
     * @return $this
     */
    public function reset()
    {
        $this->actionParams = [];
        $this->params = [];
        $this->uri = null;
        // $this->baseUrl = null;
        return $this;
    }
}
