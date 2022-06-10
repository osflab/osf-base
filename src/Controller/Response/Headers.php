<?php
namespace Osf\Controller\Response;

/**
 * Response headers management
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage controller
 */
trait Headers
{
    protected $headers = [];
    
    /**
     * @param type $header
     * @return $this
     */
    public function setRawHeader(string $header, $key = null)
    {
        if ($key === null) {
            $this->headers[] = trim($header);
        } else {
            $this->headers[(string) $key] = trim($header);
        }
        return $this;
    }
    
    /**
     * @param array $headers
     * @return $this
     */
    public function setRawHeaders(array $headers)
    {
        foreach ($headers as $key => $header) {
            $this->setRawHeader($header, is_numeric($key) ? null : $key);
        }
        return $this;
    }
    
    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * @param string $key
     * @return $this
     */
    public function clearHeader(string $key)
    {
        if (array_key_exists($key, $this->headers)) {
            unset($this->headers[$key]);
        }
        return $this;
    }
    
    /**
     * @return $this
     */
    public function clearHeaders()
    {
        $this->headers = [];
        return $this;
    }
    
    /**
     * @param type $contentDisposition
     * @return $this
     */
    public function setHeadersForPdfDocument(string $contentDisposition = 'inline')
    {
        $this->setRawHeader('Content-type: application/pdf');
        $this->setRawHeader('Content-Disposition: ' . $contentDisposition);
        return $this;
    }
    
    /**
     * Enregistre les headers qui désactivent le cache navigateur
     * @return $this
     */
    public function noCache()
    {
        return $this->setRawHeader('Cache-Control: no-cache, no-store, must-revalidate')
            ->setRawHeader('Pragma: no-cache')
            ->setRawHeader('Expires: 0'); 
    }
}