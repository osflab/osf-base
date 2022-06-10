<?php
namespace Osf\Controller\Response;

/**
 * Response body management
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage controller
 */
trait Body
{
    protected $body;
    
    /**
     * @param string|null $body
     * @return $this
     */
    public function setBody(?string $body)
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * @param string|null $body
     * @return $this
     */
    public function appendBody(?string $body)
    {
        $this->body .= (string) $body;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasBody(): bool
    {
        return $this->body !== null;
    }
    
    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
    
    /**
     * @return $this
     */
    public function clearBody()
    {
        $this->body = null;
        return $this;
    }
}
