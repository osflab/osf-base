<?php
namespace Osf\View\Helper\Addon;

use Osf\Stream\Text as T;

/**
 * Item with a title
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Title
{
    protected $title;
    
    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = T::strOrNull($title);
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initTitle(array $vars)
    {
        $this->setTitle(array_key_exists('title', $vars) ? $vars['title'] : null);
        return $this;
    }
}
