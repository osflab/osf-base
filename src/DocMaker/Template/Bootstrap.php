<?php
namespace Osf\DocMaker\Template;

use Osf\View\Helper;

/**
 * Doc maker features and bootstrap
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2010
 * @version 1.0
 * @since OSF-1.0 - 5 févr. 2010
 * @package osf
 * @subpackage docmaker
 */
class Bootstrap implements TemplateInterface
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
        if ($this->viewHelper === null) {
            $this->viewHelper = \Osf\Container\OsfContainer::getViewHelper();
        }
        $h = $this->viewHelper;
        $paragraphs = [];
        
        $retVal = '';
        
        if ($content['0']->getType() != 'plugin') {
            $retVal .= '<h1>' . $content[0]->getContent() . '</h1>';
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
                    $retVal .= $this->box($h, null, $data);
                } else {
                    $paragraphs[] = '<a name="s' .  $key . '"></a>' . $this->box($h, $title, $data);
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
            $retVal .= $this->box($h, null, $data);
        } else {
            $paragraphs[] = '<a name="s' .  $key . '"></a>' . $this->box($h, $title, $data);
        }
        
        //$retVal .= implode('', $paragraphs); 
        
        $pgCount = count($paragraphs);
        if ($pgCount == 1) {
            $retVal .= $paragraphs[0];
        } elseif ($pgCount % 2 == 0) {
            for ($i = 0; $i < $pgCount; $i = $i + 2) {
                $retVal .= $h->grid->beginRow()->beginCell2() . $paragraphs[$i] 
                         . $h->grid->endCell()->beginCell2() . $paragraphs[$i + 1]
                         . $h->grid->endCell()->endRow();
            }
        } elseif ($pgCount > 1) {
            for ($i = 0; $i < $pgCount; $i = $i + 3) {
                $retVal .= $h->grid->beginRow()->beginCell3() . $paragraphs[$i] ;
                $retVal .= isset($paragraphs[$i + 1]) ? $h->grid->endCell()->beginCell3() . $paragraphs[$i + 1] : '';
                $retVal .= isset($paragraphs[$i + 2]) ? $h->grid->endCell()->beginCell3() . $paragraphs[$i + 2] : '';
                $retVal .= $h->grid->endCell()->endRow();
            }
        }
        
        if (isset($this->menu->plan->category)) {
            foreach($this->menu->plan->category as $item) {
                $retVal .= '- ' . $item['name'] . '<br />';
            }
        }
        
        return $retVal;
    }
    
    protected function box(Helper $h, $title, $data)
    {
        if ($title) {
            return $h->box($title, $data)->statusPrimary()->collapsable()->icon(Helper\Bootstrap\AbstractViewHelper::ICON_REMARK);
        } else if (trim($data)) {
            return $h->panel(null, $data);
        }
    }
}