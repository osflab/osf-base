<?php
namespace Osf\View\Helper\Addon;

use Osf\Stream\Text as T;

/**
 * Item with a html content
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Content
{
    protected $content;
    
    /**
     * @param string|null $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = T::strOrNull($content);
        return $this;
    }
    
    /**
     * @param string $content
     * @return $this
     */
    protected function appendContent(string $content)
    {
        $this->content .= $content;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }
    
    protected function initContent(array $vars)
    {
        $this->content = array_key_exists('content', $vars) ? $vars['content'] : null;
    }
}
