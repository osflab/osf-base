<?php
namespace Osf\Controller\Response;

use Osf\Exception\ArchException;

/**
 * Management of the callback function which generate the response
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage controller
 */
trait Callback
{
    protected $callback;
    protected $callbackParams = [];
    
    /**
     * Define the callback function. 
     * This function need to send a content feed on standard output. 
     * This callback is usefull to bypass the body in order to optimize performances.
     * @param string $callback
     * @return $this
     */
    public function setCallback(callable $callback, array $params = [])
    {
        if ($this->hasCallback()) {
            throw new ArchException('Callback already defined');
        }
        $this->callback = $callback;
        $this->callbackParams = $params;
        return $this;
    }
    
    /**
     * Callback defined
     * @return bool
     */
    public function hasCallback(): bool
    {
        return $this->callback !== null;
    }
    
    /**
     * @return mixed
     */
    public function executeCallback()
    {
        if ($this->hasCallback()) {
            return call_user_func_array($this->callback, $this->callbackParams);
        }
        return null;
    }
    
    /**
     * @return $this
     */
    public function clearCallback()
    {
        $this->callback = null;
        $this->callbackParams = [];
        return $this;
    }
}
