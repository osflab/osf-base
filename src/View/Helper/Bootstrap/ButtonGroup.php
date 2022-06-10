<?php
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Button;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap buttons group
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class ButtonGroup extends AVH
{
    use EltDecoration;
    
    protected $vertical = false;
    protected $buttons = [];

    /**
     * Button alignement vertical or horizontal
     * @param type $trueOrFalse
     * @return $this
     */
    public function vertical($trueOrFalse = true)
    {
        $this->vertical = (bool) $trueOrFalse;
        return $this;
    }
    
    /**
     * Add a new button
     * @param \Osf\View\Helper\Bootstrap\Button $button
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->buttons[] = $button;
        return $this;
    }
    
    /**
     * Create a new button
     * @param string $label
     * @param string $url
     * @param string $status
     * @param string $icon
     * @param bool $block
     * @param bool $disabled
     * @param bool $flat
     * @param string $size
     * @return $this
     */
    public function button(
            $label    = null,
            $url      = null,
            $status   = null,
            $icon     = null,
            $block    = false,
            $disabled = false,
            $flat     = false,
            $size     = Button::SIZE_NORMAL)
    {
        $button = new Button($label, $url, $status, $icon, $block, $disabled, $flat, $size);
        return $this->addButton($button);
    }

    /**
     * Button groups
     * @param array $buttons
     * @param bool $vertical
     * @return \Osf\View\Helper\Bootstrap\ButtonGroup
     */
    public function __invoke(array $buttons = array(), $vertical = false)
    {
        $this->resetDecorations();
        foreach ($buttons as $button) {
            $this->button($button);
        }
        $this->vertical($vertical);
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClass($this->vertical ? 'btn-group-vertical' : 'btn-group');
        $buttons = '';
        foreach ($this->buttons as $button) {
            $buttons .= (string) $button;
        }
        $this->buttons = [];
        return $this 
            ->html('<div' . $this->getEltDecorationStr() . '">' . $buttons . '</div>')
            ->getHtml();
    }
}