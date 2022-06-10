<?php
namespace Osf\Controller\Response;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Controller\Response;

/**
 * Response types management
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage controller
 */
trait Type
{
    protected $type = 'html';
    protected $types = Response::CONTENT_TYPES;
    protected $disposition;
    protected $dispositions = ['attachment', 'inline', 'form-data'];
    
    /**
     * Content-Type
     * @param string $type
     * @return $this
     */
    public function setType(string $type):self
    {
        if (!isset($this->types[$type])) {
            Checkers::notice('Unknown response type [' . $type . ']');
        } else {
            $this->type = $type;
        }
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setTypeHtml()
    {
        return $this->setType('html');
    }
    
    /**
     * @return $this
     */
    public function setTypePdf()
    {
        return $this->setType('pdf');
    }
    
    /**
     * @return $this
     */
    public function setTypeCsv()
    {
        return $this->setType('csv');
    }
    
    /**
     * @return $this
     */
    public function setTypeJson()
    {
        return $this->setType('json');
    }
    
    /**
     * @return $this
     */
    public function setTypeText()
    {
        return $this->setType('text');
    }
    
    /**
     * @return $this
     */
    public function setTypePng()
    {
        return $this->setType('png');
    }
    
    /**
     * @return $this
     */
    public function setTypeJpeg()
    {
        return $this->setType('jpeg');
    }
    
    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Content-Disposition
     * @param string $dispositionType
     * @param string $filename
     * @param string $name
     * @return $this
     */
    public function setDisposition(string $dispositionType, $filename = null, $name = null)
    {
        if (!in_array($dispositionType, $this->dispositions)) {
            Checkers::notice('Unknown response type [' . $type . ']');
        } else {
            $this->disposition['type'] = $dispositionType;
        }
        if ($filename !== null) {
            $this->disposition['filename'] = (string) $filename;
        }
        if ($name !== null) {
            $this->disposition['name'] = (string) $name;
        }
        return $this;
    }
    
    /**
     * Display doc in browser
     * @return $this
     */
    public function setDispositionInline()
    {
        return $this->setDisposition('inline');
    }
    
    /**
     * Tel browser to download and save file
     * @param string $filename
     * @return $this
     */
    public function setDispositionAttachment(string $filename = null)
    {
        return $this->setDisposition('attachment', $filename);
    }
    
    /**
     * @return array
     */
    public function getTypeHeaders(): array
    {
        $headers = [];
        if ($this->type) {
            $headers['type'] = $this->types[$this->type];
        }
        if ($this->disposition) {
            $disposition  = 'Content-Disposition: ' . $this->disposition['type'];
            $disposition .= isset($this->disposition['name']) 
                          ? '; name="' . $this->disposition['name'] . '"'
                          : '';
            $disposition .= isset($this->disposition['filename']) 
                          ? '; filename="' . $this->disposition['filename'] . '"'
                          : '';
            $headers['disposition'] = $disposition;
        }
        return $headers;
    }
    
    /**
     * @return $this
     */
    public function clearType()
    {
        $this->type = null;
        $this->disposition = null;
        return $this;
    }
}
