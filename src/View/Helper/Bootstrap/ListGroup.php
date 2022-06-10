<?php
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Stream\Html;

/**
 * Bootstrap button
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class ListGroup extends AVH
{
    //use Addon\Status;
    use EltDecoration;
    use Addon\Badges;
    
    protected $list = [];
    protected $listCount = 0;
    protected $ulList = true;

    /**
     * Add a badge to current item
     * @param string $label
     * @param string $colorOrStatus
     * @param string $toolTipLabel
     * @return $this
     */
    public function badge($label, $colorOrStatus = null, $toolTipLabel = null) {
        switch (true) {
            case !isset($this->list[$this->listCount - 1]) : 
                Checkers::notice('Unable to put badge [' . $label . '] in list group, no element.');
                break;
            case $this->list[$this->listCount - 1] instanceof Button : 
                Checkers::notice('Unable to badge a list group button. Use an element instead.');
                break;
            case is_array($this->list[$this->listCount - 1]) : 
                $this->badges[$this->listCount - 1][] = $this->getBadgeHtml($label, $colorOrStatus, $toolTipLabel);
                break;
            default : 
                Checkers::notice('Unable to find what to do with your badge [' . $label . ']...');
        }
        return $this;
    }
    
    /**
     * Simple list item
     * @param string $label
     * @param string $url
     * @param bool $active
     * @return $this
     */
    public function addItem($label, $url = null, $status = null, $active = false, array $cssClasses = [], array $attrs = [])
    {
        return $this->addContentItem($label, null, $url, $status, $active, $cssClasses, $attrs);
    }
    
    /**
     * List item with content
     * @param string $label
     * @param string $content
     * @param string $url
     * @param bool $active
     * @return $this
     */
    public function addContentItem($label, $content, $url = null, $status = null, $active = false, array $cssClasses = [], array $attrs = [])
    {
        Checkers::checkUrl($url);
        $status === null || Checkers::checkStatus($status, null, true);
        $url    === null || $this->ulList = false;
        $this->list[] = [(string) $label, $url, (string) $content, $status, (bool) $active, $cssClasses, $attrs];
        $this->listCount++;
        return $this;
    }
    
    /**
     * Button item
     * @param \Osf\View\Helper\Bootstrap\Button $button
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->list[] = $button;
        $this->listCount++;
        $this->ulList = false;
        return $this;
    }
    
    /**
     * @return \Osf\View\Helper\Bootstrap\ListGroup
     */     
    public function __invoke()
    {
        $this->initValues([]);
        $this->list = [];
        $this->listCount = 0;
        $this->ulList = true;
        $this->badges = [];
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $ul = $this->ulList ? 'ul' : 'div';
        $li = $this->ulList ? 'li' : 'a';
        $this->addCssClass('list-group');
        $this->html('<' . $ul . $this->getEltDecorationStr() . '>');
        foreach ($this->list as $key => $item) {
            switch (true) {
                case is_array($item) : 
                    $content = $item[2] == '' ? $item[0] : 
                    Html::buildHtmlElement('h4', ['class' => 'list-group-item-heading'], $item[0]) . 
                    Html::buildHtmlElement('p',  ['class' => 'list-group-item-text'],    $item[2]);
                    $badges = isset($this->badges[$key]) ? implode(' ', array_reverse($this->badges[$key])) : '';
                    $cssClasses = $item[5];
                    $cssClasses[] = 'list-group-item';
                    $item[3] && $cssClasses[] = 'list-group-item-' . $item[3];
                    $item[4] && $cssClasses[] = 'active';
                    $attrs = $item[6];
                    $item[1] && $attrs['href'] = $item[1];
                    $html = Html::buildHtmlElement($li, $attrs, $badges . $content, true, $cssClasses);
                    $this->html($html);
                    break;
                case $item instanceof Button : 
                    $this->html((string) $item->setCssClass('list-group-item'));
                    break;
                default :
                    Checkers::notice('Unknown item type');
            }
        }
        return $this->html('</' . $ul . '>')->getHtml();
    }
    
    public function __construct()
    {
        return $this->__invoke();
    }
}