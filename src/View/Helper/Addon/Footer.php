<?php
namespace Osf\View\Helper\Addon;

use Osf\Stream\Text as T;

/**
 * Item with a footer part
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Footer
{
    protected $footer;
    protected $footerClasses = [];
    
    /**
     * @param string $content
     * @return $this
     */
    public function setFooter($content, $cssClasses = [])
    {
        $this->footer = T::strOrNull($content);
        if ($cssClasses) {
            $this->footerClasses = array_merge($this->footerClasses, $cssClasses);
        }
        return $this;
    }
    
    /**
     * @param array $footerClasses
     * @return $this
     */
    protected function setFooterClasses(array $footerClasses)
    {
        $this->footerClasses = $footerClasses;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getFooter()
    {
        return $this->footer;
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initFooter(array $vars)
    {
        $this->setFooter(array_key_exists('footer', $vars) ? $vars['footer'] : null);
        $this->setFooterClasses(array_key_exists('footerClasses', $vars) ? $vars['footerClasses'] : []);
        return $this;
    }
}
