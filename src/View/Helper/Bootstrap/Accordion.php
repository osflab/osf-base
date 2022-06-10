<?php 

namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Crypt\Crypt;

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
class Accordion extends AVH
{
    use EltDecoration;
    
    const TYPE_PANEL = 'panel';
    const TYPE_BOX   = 'box';
    
    protected static $id;
    protected static $itemId = 0;
    protected $items = [];
    protected $itemType;
    protected $itemStatus;
    protected $openedItems = [];

    /**
     * Accordion or panels or boxes
     * @param string $status status replacement for each items
     * @return \Osf\View\Helper\Bootstrap\Accordion
     */
    public function __invoke(string $status = null)
    {
        self::$id = Crypt::getRandomHash();
        self::$itemId = 0;
        $this->resetDecorations();
        if ($status && Tools\Checkers::checkStatus($status)) {
            $this->itemStatus = $status;
        }
        return $this;
    }
    
    /**
     * Display the box at the end of configuration
     * @return string
     */
    protected function render()
    {
        if (!isset($this->items[0])) {
            return '';
        }
        foreach ($this->items as $itemId => $item) {
            $item->setCollapsable(
                self::$id, 
                self::$id . $itemId, 
                in_array($itemId, $this->openedItems));
        }
        $this->setAttribute('id', self::$id);
        $this->addCssClass($this->itemType . '-group');
        return $this
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html(implode("\n", $this->items))
            ->html('</div>')
            ->getHtml();
    }
    
    /**
     * 0...n : N° of opened element
     * @param int $id if null, current item
     * @return $this
     */
    public function setOpenedItem($id = null)
    {
        $openedItem = $id === null ? $this->getCurrentItemId() : (int) $id;
        $this->openedItems[$openedItem] = $openedItem;
        return $this;
    }
    
    /**
     * Id of opened items
     * @return string
     */
    public function getOpenedItems()
    {
        return $this->openedItems;
    }
    
    /**
     * @return $this
     */
    public function closeAllItems()
    {
        $this->openedItems = [];
        return $this;
    }
    
    /**
     * @return $this
     */
    public function openAllItems()
    {
        foreach (array_keys($this->items) as $key) {
            $this->setOpenedItem($key);
        }
        return $this;
    }
    
    /**
     * @param \Osf\View\Helper\Bootstrap\Panel $panel
     * @return $this
     */
    public function addPanel(Panel $panel):self
    {
        $panel = clone $panel;
        if ($this->itemStatus) {
            $panel->status($this->itemStatus);
        }
        if (!$this->itemType) {
            $this->itemType = self::TYPE_PANEL;
        } else if ($this->itemType !== self::TYPE_PANEL) {
            Tools\Checkers::notice('Can not put panels and boxes in an single accordion');
        }
        $this->items[self::$itemId] = $panel;
        self::$itemId++;
        return $this;
    }
    
    public function getCurrentItemId()
    {
        return self::$itemId - 1;
    }
}