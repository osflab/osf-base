<?php
namespace Osf\View;

use Osf\Container\OsfContainer as Container;
use Osf\Exception\ArchException;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\RendererInterface as Renderer;

/**
 * View helpers super class
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 */
abstract class AbstractHelper implements HelperInterface
{
    const DEFAULT_VIEW_NAME = 'View';
    
    /**
     * Current view
     * @var \Osf\View\OsfView
     */
    protected $view;
    
    /**
     * Helpers keys and classes
     * @var array
     */
    protected $helpers = array();
    
    /**
     * Helpers container
     * @var array
     */
    protected static $helperInstances = array();
    
    /**
     * Name to use in order to get view from default container if not builded
     * @var string
     */
    protected $viewName;
    
    protected $initialized = false;

    /**
     * @param string $helperName
     * @param string $helperClass
     * @return multitype:
     */
    protected function buildHelper($helperName, $helperClass)
    {
        if (!array_key_exists($helperName, self::$helperInstances)) {
            $helper = new $helperClass();
            if ($helper instanceof Helper\MultiHelperInterface) {
                return $helper;
            }
            self::$helperInstances[$helperName] = new $helperClass();
            self::$helperInstances[$helperName]->setView($this->getView());
        }
        return self::$helperInstances[$helperName];
    }
    
    /**
     * Register a set of callable helpers
     * @param array $helpers
     */
    public function registerHelpers(array $helpers)
    {
        $this->helpers = array_merge($this->helpers, $helpers);
    }
    
    /**
     * Dynamic call for external helpers
     * @param string $helper
     * @param array $params
     * @throws ArchException
     * @return mixed
     */
    public function __get($helper)
    {
        $this->init();
        
        // Get Helper
        if (array_key_exists($helper, $this->helpers)) {
            return $this->buildHelper($helper, $this->helpers[$helper]);
        }
        
        // Helper not found
        throw new ArchException('View helper ' . $helper . ' not found');
    }

    public function __call($helper, $params)
    {
        $helper = $this->__get($helper);
        return call_user_func_array(array($helper, '__invoke'), $params);
    }

    /*
     * Redéfinition des getters de helpers de zend view
     */
    
    /**
     * @return \Laminas\View\Helper\EscapeHtml
     */
    protected function getEscapeHtmlHelper()
    {
        return $this->escapeHtml;
    }
    
    /**
     * @return \Laminas\View\Helper\EscapeHtmlAttr
     */
    protected function getEscapeHtmlAttrHelper()
    {
        return $this->escapeHtmlAttr;
    }

    /**
     * @return \Laminas\Form\View\Helper\FormLabel
     */
    protected function getLabelHelper()
    {
        return $this->formLabel;
    }

    /**
     * @return \Laminas\Form\View\Helper\FormElement
     */
    protected function getElementHelper()
    {
        return $this->formElement;
    }

    /**
     * @return \Laminas\Form\View\Helper\FormElementErrors
     */
    protected function getElementErrorsHelper()
    {
        return $this->formElementErrors;
    }
    
    
    /**
     * Set the View object
     * @param Renderer $view
     * @return HelperInterface
     */
    public function setView(Renderer $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Get the View object
     * @return \Osf\View\OsfView
     */
    public function getView()
    {
        $this->init();
        return $this->view;
    }
    
    /**
     * Get values from current view
     */
    public function __construct(?string $viewName = null)
    {
        $this->viewName = $viewName ?? self::DEFAULT_VIEW_NAME;
    }
    
    /**
     * Lazy loading process
     * @return void
     */
    public function init(): void
    {
        if ($this->initialized) {
            return;
        }
        
        // Vue courante
        if (!$this->view) {
            $containerGetter = 'get' . ucfirst($this->viewName);
            $this->setView(Container::$containerGetter());
        }

        // Helpers osf
        if (method_exists($this, 'getAvailableHelpers')) {
            $this->registerHelpers($this->getAvailableHelpers());
        }
        
        $this->initialized = true;
    }
    
    /**
     * Escape a string (htmlspecialchars)
     * @param string $txt
     * @return string
     */
    public function htmlEscape($txt)
    {
        return htmlspecialchars($txt);
    }
    
    /**
     * Escape a string from view params value
     * @param string $key
     * @return string
     */
    public function getHtmlEscape($key, $alternateContent = '')
    {
        return $this->htmlEscape($this->get($key, $alternateContent));
    }
    
    /**
     * Get a value from action controller
     * @param string $key
     * @param string $alternateContent displayd if $key not found
     * @return multitype:
     */
    public function get($key, $alternateContent = '')
    {
        $this->init();
        return isset($this->view->getValues()[$key]) ? $this->view->getValues()[$key] : $alternateContent;
    }
    
    /**
     * Get registered values in the current view
     * @return array:
     */
    public function getValues()
    {
        $this->init();
        return $this->view->getValues();
    }
    
    /**
     * Simulate isset on a dynamic property
     * @param string $key
     */
    public function __isset($key)
    {
        $this->init();
        return isset($this->view->getValues()[$key]);
    }
    
    /**
     * Get helpers from container
     * @return array
     */
    public static function getAvailableHelpers()
    {
        return [];
    }
}
