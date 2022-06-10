<?php
namespace Osf\View\Helper\Addon;

use Osf\Stream\Text as T;

/**
 * Content to put after the element. If there is a container, we stay in it.
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Append
{
    protected $appendContent;
    
    /**
     * @param string|null $content
     * @return $this
     */
    public function setAppend($content)
    {
        $this->appendContent = T::strOrNull($content);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAppend()
    {
        return $this->appendContent;
    }
    
    /**
     * @return string|null
     */
    public function hasAppend()
    {
        return $this->appendContent !== null;
    }
    
    protected function initAppend(array $vars)
    {
        $this->appendContent = array_key_exists('appendContent', $vars) ? $vars['appendContent'] : null;
    }
}
