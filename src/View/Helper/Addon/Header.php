<?php
namespace Osf\View\Helper\Addon;

use Osf\Stream\Text as T;

/**
 * Item with a header part
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Header
{
    protected $header;
    
    /**
     * @param string $content
     * @return $this
     */
    public function setHeader($content)
    {
        $this->header = T::strOrNull($content);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getHeader()
    {
        return $this->header;
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initHeader(array $vars)
    {
        $this->setHeader(array_key_exists('header', $vars) ? $vars['header'] : null);
        return $this;
    }
}
