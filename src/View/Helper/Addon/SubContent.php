<?php
namespace Osf\View\Helper\Addon;

use Osf\Stream\Text as T;

/**
 * Item with a html subContent
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait SubContent
{
    protected $subContent;
    
    /**
     * @param string|null $subContent
     * @return $this
     */
    public function setSubContent($subContent)
    {
        $this->subContent = T::strOrNull($subContent);
        return $this;
    }
    
    /**
     * @param string $subContent
     * @return $this
     */
    protected function appendSubContent(string $subContent)
    {
        $this->subContent .= $subContent;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getSubContent()
    {
        return $this->subContent;
    }
    
    protected function initSubContent(array $vars)
    {
        $this->subContent = array_key_exists('subContent', $vars) ? $vars['subContent'] : null;
    }
}
