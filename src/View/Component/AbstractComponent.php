<?php
namespace Osf\View\Component;

use Osf\Container\OsfContainer as Container;

/**
 * Javascript and CSS components super class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
abstract class AbstractComponent
{
    protected $libs = [];
    
    /**
     * @param string $url
     * @param string $addBaseUrl
     * @param string $type
     * @param array $attributes
     * @return $this
     */
    protected function registerHeadJs($url, $addBaseUrl = false, $type = null, array $attributes = [])
    {
        $this->libs[] = ['headjs', $this->getUrl($url, $addBaseUrl), $type, $attributes];
        return $this;
    }
    
    /**
     * @param string $url
     * @param string $addBaseUrl
     * @param string $media
     * @param array $attributes
     * @return $this
     */
    protected function registerHeadCss($url, $addBaseUrl = false, $media = null, array $attributes = [])
    {
        $this->libs[] = ['headcss', $this->getUrl($url, $addBaseUrl), $media, $attributes];
        return $this;
    }
    
    /**
     * @param string $url
     * @param string $addBaseUrl
     * @param string $type
     * @param array $attributes
     * @return $this
     */
    protected function registerFootJs($url, $addBaseUrl = false, $type = null, array $attributes = [])
    {
        $this->libs[] = ['footjs', $this->getUrl($url, $addBaseUrl), $type, $attributes];
        return $this;
    }
    
    /**
     * @param string $url
     * @param string $addBaseUrl
     * @param string $media
     * @param array $attributes
     * @return $this
     */
    protected function registerFootCss($url, $addBaseUrl = false, $media = null, array $attributes = [])
    {
        $this->libs[] = ['footcss', $this->getUrl($url, $addBaseUrl), $media, $attributes];
        return $this;
    }
    
    /**
     * Script to execute with the request
     * @param string $script
     * @return $this
     */
    public function registerScript($script)
    {
        $this->libs[] = ['script', $script];
        return $this;
    }
    
    /**
     * Remove JS scripts (usefull for a redirect)
     * @return $this
     */
    public function clearScripts()
    {
        foreach ($this->libs as $key => $value) {
            if ($value[0] === 'script') {
                unset($this->libs[$key]);
            }
        }
        return $this;
    }
    
    private function getUrl($url, $addBaseUrl)
    {
        return $addBaseUrl ? Container::getViewHelper()->baseUrl($url, true) : $url;
    }
    
    /**
     * @return array
     */
    public function getScripts()
    {
        return $this->libs;
    }
}
