<?php
namespace Osf\View\Helper\Tags;

use Osf\Stream\Html;

/**
 * Link head tags
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 13 déc. 2013
 * @package osf
 * @subpackage view
 */
trait Link
{
    protected $links = [];
    
    /**
     * Link to a CSS stylesheet
     * @param string $href
     * @param string $media
     * @param array $attributes
     * @return \Osf\View\Helper\HeadLink
    */
    public function appendStylesheet($href, $media = null, array $attributes = [], $priority = false)
    {
        static $counter = 0;
        
        $attributes['rel'] = 'stylesheet';
        $attributes['href'] = $href;
        //$attributes['type'] = 'text/css';
        $media && $attributes['media'] = $media;
        $this->links[$priority == 'high' ? $counter + 1000 : $counter] = Html::buildHtmlElement('link', $attributes);
        $counter++;
        return $this;
    }
    
    public function buildCssLinks()
    {
        ksort($this->links);
        return implode("\n", $this->links) . "\n";
    }
}

