<?php
namespace Osf\DocMaker\Template;

use Osf\View\Helper;

/**
 * Tab converter (todo?)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2010
 * @version 1.0
 * @since OSF-1.0 - 5 févr. 2010
 * @package osf
 * @subpackage docmaker
 */
class Tab implements TemplateInterface
{
    protected $prepends = array();
    protected $appends = array();
    
    /**
     * @var \Osf\View\Helper
     */
    protected $viewHelper = null;
    
    public function setViewHelper(Helper $viewHelper)
    {
        $this->viewHelper = $viewHelper;
    }
    
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
        $paragraphs = [];
        $retVal = [];
        
        if ($content['0']->getType() != 'plugin') {
            $retVal['title'] = $content[0]->getContent();
        }
        
        $licontext = 0;
        $title = '';
        $data = '';
        foreach($content as $key => $item) {
            $type = $item->getType();
            
            if (array_key_exists($type, $this->prepends)) {
                $data .= $this->prepends[$type];
            }
        
            if ($type != 'subli' && $licontext == 2) {
                $licontext = 1;
                $data .= '</li></ul>';
            }
            if ($type != 'li' && $type != 'subli' && $licontext == 1) {
                $licontext = 0;
                $data .= '</ul>';
            }
        
            if ($type == 'paragraph') {
                $data .= '<p>' . $item->getContent() . '</p>';
            }
        
            if ($type == 'title') {
                if (!$title) {
                    $retVal['items'][]['body'] = $data;
                } else {
                    $retVal['items'][] = ['title' => $title, 'body' => $data];
                }
                $title = $item->getContent();
                $data = '';
            }
        
            if ($type == 'subtitle') {
                $data .= '<h4>' . $item->getContent() . '</h4>';
            }
        
            if (($type == 'li' || $type == 'subli') && $licontext == 0) {
                $licontext = 1;
                $data .= '<ul>';
            }
        
            if ($type == 'subli' && $licontext == 1) {
                $licontext = 2;
                $data .= '<ul>';
            }
        
            if ($type == 'li' || $type == 'subli') {
                $data .= '<li>' . $item->getContent();
            }
        
            if ($type == 'php') {
                $data .=  '<code>';
                $data .= nl2br($item->getContent());
                $data .= '</code>';
            }
        
            if ($type == 'para') {
                $data .= $this->viewHelper->panel(null, $item->getContent());
            }
        
            if ($type == 'plugin') {
                $data .= $h->panel($item->getContent())->statusWarning();
            }

            if (array_key_exists($type, $this->appends)) {
                $data .= $this->appends[$type];
            }
        }
        
        if (!$title) {
            $retVal['items'][]['body'] = $data;
        } else {
            $retVal['items'][] = ['title' => $title, 'body' => $data];
        }
        
        return $retVal;
    }
}