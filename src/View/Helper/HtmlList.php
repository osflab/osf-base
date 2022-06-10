<?php
namespace Osf\View\Helper;

use Osf\View\Helper\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;

/**
 * Bootstrap panels
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class HtmlList extends AVH
{
    use Addon\EltDecoration;
    
    const TYPE_OL = 'ol';
    const TYPE_UL = 'ul';
    
    protected $type = self::TYPE_UL;
    protected $contentIfEmpty = '';
    protected $items = [];
    
    /**
     * @param string $type
     * @return \Osf\View\Helper\HtmlList
     */
    public function __invoke(string $type = null)
    {
        $this->initValues(get_defined_vars());
        $type && $this->setType($type);
        $this->items = [];
        $this->contentIfEmpty = '';
        return $this;
    }
    
    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        if ($type !== self::TYPE_UL && $type !== self::TYPE_OL) {
            Checkers::notice('Bad list type [' . $type . '].');
        } else {
            $this->type = $type;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function setTypeUl()
    {
        return $this->setType(self::TYPE_UL);
    }
    
    /**
     * @return $this
     */
    public function setTypeOl()
    {
        return $this->setType(self::TYPE_OL);
    }
    
    /**
     * @param string $html
     * @return $this
     */
    public function addItem(string $html)
    {
        $this->items[] = $html;
        return $this;
    }
    
    /**
     * @param array $items
     * @return $this
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setContentIfEmpty(string $contentIfEmpty)
    {
        $this->contentIfEmpty = $contentIfEmpty;
        return $this;
    }

    public function getContentIfEmpty()
    {
        return $this->contentIfEmpty;
    }
    
    /**
     * Return HTML content
     * @return string
     */
    protected function render()
    {
        if (!isset($this->items[0])) {
            return $this->contentIfEmpty;
        }
        
        $this->html('<' . $this->type . $this->getEltDecorationStr() . '>');
        foreach ($this->items as $item) {
            $this->html('<li>' . $item . '</li>');
        }
        $this->html('</' . $this->type . '>');
        return $this->getHtml();
    }
}
