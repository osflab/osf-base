<?php
namespace Osf\Controller;

/**
 * HTTP response
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf 
 * @subpackage controller
 */
class Response
{
    use Response\Callback;
    use Response\Headers;
    use Response\Body;
    use Response\Type;
    
    const CONTENT_TYPES = [
        'html' => 'Content-Type: text/html; charset=utf-8',
        'text' => 'Content-Type: text/plain; charset=utf-8',
        'xml'  => 'Content-Type: text/xml; charset=utf-8',
        'csv'  => 'Content-Type: text/csv; charset=utf-8',
        'json' => 'Content-Type: application/json; charset=utf-8',
        'pdf'  => 'Content-Type: application/pdf',
        'png'  => 'Content-Type: image/png',
        'jpeg' => 'Content-Type: image/jpeg',
        'xls'  => 'Content-Type: application/vnd.ms-excel',
        'xlsx' => 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ods'  => 'Content-Type: application/vnd.oasis.opendocument.spreadsheet'
    ];
    
    /**
     * Generate headers corresponding to selected type and put it in the response
     * @return $this
     */
    public function putTypeHeadersInResponse()
    {
        $this->setRawHeaders($this->getTypeHeaders());
        return $this;
    }
    
    /**
     * Clean the response content
     * @return $this
     */
    public function reset()
    {
        $this->clearHeaders();
        $this->clearBody();
        $this->clearType();
        return $this;
    }
    
    /**
     * Si aucun header n'est défini, on fixe à HTML (utilisé par Application)
     * @return $this
     */
    public function fixHeaders()
    {
        if (!$this->getHeaders() && !$this->getType()) {
            $this->setTypeHtml();
        }
        return $this;
    }
}