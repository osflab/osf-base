<?php
namespace Osf\View\Helper\Addon;

use Osf\Container\OsfContainer as Container;

/**
 * Url de type MVC
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait MvcUrl
{
    protected $mvcUrl = [
        'controller' => null,
        'action' => null,
        'params' => []
    ];
    
    /**
     * Define the MVC parameters of the url
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return $this
     */
    public function setMvcUrl($controller = null, $action = null, array $params = [])
    {
        $this->mvcUrl = [
            'controller' => trim($controller),
            'action'     => trim($action),
            'params'     => $params
        ];
        return $this;
    }
    
    /**
     * Define a MVC parameter individually
     * @param string $paramName
     * @param string $value
     * @return $this
     */
    public function setMvcParam(string $paramName, $value = null)
    {
        $value = $value === null ? null : trim($value);
        switch ($paramName) {
            case 'controller' : 
            case 'action' : 
                $this->mvcUrl[$paramName] = $value;
                break;
            default : 
                $this->mvcUrl['params'][$paramName] = $value;
                break;
        }
        return $this;
    }
    
    protected function getMvcUrl()
    {
        return $this->getView()->url($this->mvcUrl['controller'], $this->mvcUrl['action'], $this->mvcUrl['params']);
    }
    
    protected function initMvcUrl(array $vars)
    {
        $controller = array_key_exists('controller', $vars) ? $vars['controller'] : Container::getRequest()->getController();
        $action = array_key_exists('action', $vars) ? $vars['action'] : null;
        $params = array_key_exists('params', $vars) && is_array($vars['params']) ? $vars['params'] : [];
        $this->setMvcUrl($controller, $action, $params);
    }
}
