<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Component\VueJs;

/**
 * Trait element for helpers with a link
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Url
{
    protected $url;
    protected $urlTarget;
    protected $urlEvent = VueJs::EVENT_CLIC;
    protected $urlReplaceTag = false;
    protected $urlAjaxLink = false;
    
    /**
     * An url and optional (div) id or selector target
     * @param ?string $url
     * @param ?string $target
     * @param ?string $event
     * @param bool $replaceTag
     * @param bool $isAjaxLink
     * @return $this
     */
    public function url($url, ?string $target = null, ?string $event = null, ?bool $replaceTag = null, ?bool $isAjaxLink = null)
    {
        $url !== null && Checkers::checkUrl($url);
        if ($url === null) {
            $this->url = null;
            $this->urlTarget = null;
            return $this;
        }
        if ($event !== null && !isset(VueJs::EVENTS_HTML[$event])) {
            Checkers::notice('Bad event, see VueJs component');
        }
        
        $this->url = $url;
        $this->urlEvent = $event ?? $this->urlEvent;
        $this->urlTarget = $target ?? $this->urlTarget;
        $this->urlAjaxLink = $isAjaxLink ?? $this->urlAjaxLink;
        $this->urlReplaceTag = $replaceTag ?? $this->urlReplaceTag;
        
        return $this;
    }
    
    /**
     * Build an ajax link instead of standard href url (no history, not bookmarkable)
     * @param bool $isAjaxLink
     * @return $this
     */
    public function isAjaxLink(bool $isAjaxLink = true)
    {
        $this->urlAjaxLink = $isAjaxLink;
        return $this;
    }
    
    /**
     * @param bool $href generate HREF element (for links) otherwise JS link
     * @return array
     */
    protected function getUrlAttributes(bool $href = false, ?bool $withHistory = null): array
    {
        $attrs = [];
        if ($href && $this->urlEvent === VueJs::EVENT_CLIC && !$this->urlTarget && !$this->urlAjaxLink) {
            $attrs['href'] = $this->url;
        } else if ($this->url) {
            $eventAttr = VueJs::EVENTS_HTML[$this->urlEvent];
            $target = $this->urlTarget === VueJs::EVENT_CLIC ? null : $this->urlTarget;
            $withHistory = $withHistory ?? !$this->urlAjaxLink;
            $attrs[$eventAttr] = VueJs::buildJsLink($this->url, $target, $withHistory, $this->urlReplaceTag);
        }
        return $attrs;
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initUrl(array $vars)
    {
        $url = isset($vars['url'])
                ? (string) $vars['url']
                : null;
        $urlTarget = isset($vars['urlTarget']) 
                ? (string) $vars['urlTarget'] 
                : null;
        $urlEvent = isset($vars['urlEvent']) 
                ? (string) $vars['urlEvent'] 
                : null;
        $this->url($url, $urlTarget, $urlEvent);
        return $this;
    }
}