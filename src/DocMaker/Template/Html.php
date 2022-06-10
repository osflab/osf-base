<?php
namespace Osf\DocMaker\Template;

/**
 * HTML converter
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2010
 * @version 1.0
 * @since OSF-1.0 - 5 févr. 2010
 * @package osf
 * @subpackage docmaker
 */
class Html implements TemplateInterface
{
    protected $prepends = array();
    protected $appends = array();
    
    public function prependItem($itemType, $content)
    {
        $this->prepends[$itemType] = $content;
    }
    
    public function appendItem($itemType, $content)
    {
        $this->appends[$itemType] = $content;
    }
    
    public function render(array $content)
    {
        $retVal = '';
        
        if ($content['0']->getType() != 'plugin') {
            $retVal .= '<h1>' . $content[0]->getContent() . '</h1>';
        }
        
        $licontext = 0;
        foreach($content as $key => $item) {
            $type = $item->getType();
            
            if (array_key_exists($type, $this->prepends)) {
                $retVal .= $this->prepends[$type];
            }
        
            if ($type != 'subli' && $licontext == 2) {
                $licontext = 1;
                $retVal .= '</li></ul>';
            }
            if ($type != 'li' && $type != 'subli' && $licontext == 1) {
                $licontext = 0;
                $retVal .= '</ul>';
            }
        
            if ($type == 'paragraph') {
                $retVal .= '<p>' . $item->getContent() . '</p>';
            }
        
            if ($type == 'title') {
                $retVal .= '<a name="s' .  $key . '"></a><h1>' . $item->getContent() . '</h1>';
            }
        
            if ($type == 'subtitle') {
                $retVal .= '<h3>' . $item->getContent() . '</h3>';
            }
        
            if (($type == 'li' || $type == 'subli') && $licontext == 0) {
                $licontext = 1;
                $retVal .= '<ul>';
            }
        
            if ($type == 'subli' && $licontext == 1) {
                $licontext = 2;
                $retVal .= '<ul>';
            }
        
            if ($type == 'li' || $type == 'subli') {
                $retVal .= '<li>' . $item->getContent();
            }
        
            if ($type == 'php') {
                $retVal .=  '<div class="code">';
                $retVal .= nl2br($item->getContent());
                $retVal .= '</div>';
            }
        
            if ($type == 'para') {
                $retVal .= '<div class="para">';
                $retVal .= $item->getContent();
                $retVal .= '</div>';
            }
        
            if ($type == 'plugin') {
                $retVal .= '<div class="plugin">';
                $retVal .= $item->getContent();
                $retVal .= '</div>';
            }

            if (array_key_exists($type, $this->appends)) {
                $retVal .= $this->appends[$type];
            }
        }
        
        if (isset($this->menu->plan->category)) {
            foreach($this->menu->plan->category as $item) {
                $retVal .= '- ' . $item['name'] . '<br />';
            }
        }
        
        return $retVal;
    }
}