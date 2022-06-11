<?php
namespace Osf\View;

use Laminas\View\Renderer\RendererInterface;
use Laminas\View\Resolver\ResolverInterface;
use Laminas\View\Model\ModelInterface;
use Osf\Container\OsfContainer as Container;
use Osf\Exception\ArchException;
use Osf\Stream\Text as T;

/**
 * Osf view component
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 sept. 2013
 * @package osf
 * @subpackage view
 */
class OsfView implements RendererInterface
{
    protected $values = [];
    
    /**
     * @param array $data
     * @return $this
     */
    public function register(array $values, $merge = true)
    {
        $this->mergeValues($values);
        return $this;
    }
    
    /**
     * Get current view values
     * @return array
     */
    public function getValues():array
    {
        return $this->values;
    }
    
    /**
     * Get value or null if not exits
     * @param string $key
     */
    public function getValue(string $key)
    {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }
    
    /**
     * Add a value in values table
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addValue($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }
    
    /**
     * Reset all the view values
     * @return $this
     */
    public function resetValues()
    {
        $this->values = [];
        return $this;
    }
    
    /**
     * Add values to view
     * @param array $values
     * @param bool $merge
     * @return $this
     */
    protected function mergeValues(array $values, $merge = true)
    {
        $this->values = $merge ? array_merge($this->values, $values) : $values;
        return $this;
    }
    
    /**
     * Render the view file (or default action view) and return content
     * @param string|ModelInterface $nameOrModel
     * @param null|array|\ArrayAccess $values $values
     * @throws \Exception
     * @return string
     */
    public function render($nameOrModel = null, $values = null)
    {
        // Default file = default controller/action view
        if ($nameOrModel == null) {
            $request = Container::getRequest();
            $file = APPLICATION_PATH . '/App/' 
                                     . T::ucFirst($request->getController()) . '/View/' 
                                     . $request->getAction() . '.phtml';
            if (!file_exists($file)) {
                throw new ArchException('View file ' . $file . ' not found');
            }
        } elseif (is_string($nameOrModel)) {
            $file = $nameOrModel;
        } elseif ($nameOrModel instanceof ModelInterface) {
            throw new ArchException('Template type not renderable');
        } else {
            throw new ArchException('Unknown template type');
        }
        if ($values !== null) {
            $this->register($values);
        }
        
        ob_start();
        include $file;
        return ob_get_clean();
    }
    
    /**
     * @return \Osf\View\OsfView
     */
    public function getEngine()
    {
        return $this;
    }
    
    /**
     * Set the resolver used to map a template name to a resource the renderer may consume.
     * @param  ResolverInterface $resolver
     * @return RendererInterface
    */
    public function setResolver(ResolverInterface $resolver)
    {
    }
    
    /**
     * Bind to view helpers (for zend forms)
     * @param string $method
     * @param array $params
     */
    public function __call($method, $params)
    {
        return call_user_func_array([Container::getViewHelper(), $method], $params);
    }
}