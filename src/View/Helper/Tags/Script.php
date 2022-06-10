<?php
namespace Osf\View\Helper\Tags;

use Osf\Stream\Html;

/**
 * Script head tags
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 13 déc. 2013
 * @package osf
 * @subpackage view
 */
trait Script
{
    protected $files         = [];
    protected $scripts       = [];

    /**
     * Javascript file
     * @param string $src
     * @param string $type
     * @param array $attributes
     * @return \Osf\View\Helper\HeadScript
     */
    public function appendFile($src, $type = null, array $attributes = array(), $onTop = false)
    {
        static $counter = 0;
        
        $attributes['src'] = $src;
        $type !== null && $attributes['type'] = $type;
        $this->files[$onTop == 'high' ? $counter : $counter + 1000] = Html::buildHtmlElement('script', $attributes, '');
        $counter++;
        return $this;
    }

    /**
     * Code javascript
     * @param string $content
     * @param string $type
     * @param array $attributes
     * @return \Osf\View\Helper\HeadScript
     */
    public function appendScript($content, $onTop = false)
    {
        static $counter = 0;
        
        $this->scripts[$onTop == 'high' ? $counter : $counter + 1000] = $content;
        $counter++;
        return $this;
    }
    
    public function buildFiles()
    {
        ksort($this->files);
        return implode("\n", $this->files) . "\n";        
    }

    public function buildScripts(bool $buildHtmlTag = true)
    {
        ksort($this->scripts);
        $js = implode("\n", $this->scripts);
        if ($buildHtmlTag) {
            return Html::buildHtmlScript($js);
        }
        return $js;
    }
}