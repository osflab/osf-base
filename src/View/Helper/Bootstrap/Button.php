<?php
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Component;
use Osf\View\Component\VueJs;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Stream\Text as T;

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
class Button extends AVH
{
    use Addon\Url;
    use Addon\Icon;
    use Addon\Status;
    use Addon\Modal;
    use Addon\Menu;
    use Addon\Tooltip;
    use Addon\Popover;
    use EltDecoration;
    
    const SIZE_XSMALL = 'xs';
    const SIZE_SMALL  = 'sm';
    const SIZE_NORMAL = null;
    const SIZE_LARGE  = 'lg';
    const SIZE_HUGE   = 'hg';
    const SIZES = [
        'small'  => self::SIZE_SMALL, 
        'xsmall' => self::SIZE_XSMALL, 
        'normal' => self::SIZE_NORMAL, 
        'large'  => self::SIZE_LARGE,
        'huge'   => self::SIZE_HUGE
    ];
    
    protected static $idCount = 0;
    
    protected $id;
    protected $label;
    protected $block = false;
    protected $flat = false;
    protected $disabled = false;
    protected $size;
    protected $cssClass = 'btn';

    /**
     * Button type "block"
     * @param bool $trueOrFalse
     * @return $this
     */
    public function block($trueOrFalse = true)
    {
        $this->block = (bool) $trueOrFalse;
        return $this;
    }

    /**
     * Button type "flat"
     * @param bool $trueOrFalse
     * @return $this
     */
    public function flat($trueOrFalse = true)
    {
        $this->flat = (bool) $trueOrFalse;
        return $this;
    }

    /**
     * Disable button
     * @param bool $trueOrFalse
     * @return $this
     */
    public function disable($trueOrFalse = true)
    {
        $this->disabled = (bool) $trueOrFalse;
        return $this;
    }
    
    /**
     * Button size
     * @param string $size
     * @return $this
     */
    public function size($size = null)
    {
        if (!in_array($size, self::SIZES)) {
            Checkers::notice('Button size error, size [' . $size . '] unknown');
            $this->size = self::SIZE_NORMAL;
        } else {
            $this->size = $size;
        }
        return $this;
    }
    
    /**
     * @return $this
     */
    public function sizeSmall()  { return $this->size(self::SIZE_SMALL); }
    
    /**
     * @return $this
     */
    public function sizeXsmall() { return $this->size(self::SIZE_XSMALL); }
    
    /**
     * @return $this
     */
    public function sizeNormal() { return $this->size(self::SIZE_NORMAL); }
    
    /**
     * @return $this
     */
    public function sizeLarge()  { return $this->size(self::SIZE_LARGE); }

    /**
     * @return $this
     */
    public function sizeHuge()  { return $this->size(self::SIZE_HUGE); }
    
    /**
     * Add a new css class for styling
     * @param string $class
     * @return $this
     */
    public function setCssClass($class) 
    {
        $this->cssClass = trim($class);
        return $this;
    }
    
    /**
     * Set submit action
     * @staticvar boolean $attached
     * @param string $formId (without #)
     * @param string $targetId (without #)
     * @param bool $preventDefault
     * @return $this
     */
    public function submitForm(string $formId = '', string $targetId = '', bool $preventDefault = true)
    {
        static $attached = false;
        
        if ($attached) {
            Checkers::notice('This button is already attached to a form.');
            return $this;
        }
        $this->id = 'btnf' . self::$idCount++;
        Component::getVueJs()->registerSubmitLink($this->id, $formId, VueJs::EVENT_CLIC, $targetId, $preventDefault);
        return $this;
    }
    
    /**
     * @param string $label
     * @param string $url
     * @param string $status
     * @param string $icon
     * @param bool $block
     * @param bool $disabled
     * @param bool $flat
     * @param string $size
     * @return \Osf\View\Helper\Bootstrap\Button
     */
    public function __invoke($label = null, $url = null, $status = null, $icon = null, $block = false, $disabled = false, $flat = false, $size = self::SIZE_NORMAL)
    {
        $status === null && $status = AVH::STATUS_DEFAULT;
        $this->initValues(get_defined_vars());
        $this->id = null;
        $this->label = T::strOrNull($label);
        $this->block($block);   
        $this->disable($disabled);
        $this->flat($flat);
        $this->size($size);
        return clone $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        if ($this->menu && $this->modalId) {
            Checkers::notice('Warning: possible conflict between modal and menu on this button');
        }
        if ($this->menu && $this->id) {
            Checkers::notice('Warning: possible conflict between submit link and menu on this button');
        }
        
        $this->registerCss()->registerAttrs();
        $content  = $this->icon ? $this->getIconHtml() : '';
        $content .= $content && $this->label ? '&nbsp;&nbsp;' . $this->label : $this->label;
        $content .= $this->menu && !$this->icon ? ' <span class="caret"></span> ' : '';
        $menuOrientation = $this->menu ? ' ' . $this->menu->getOrientationClass() : '';
        $eltName = $this->url ? 'a' : 'button';
        return $this 
            ->html('<div class="btn-group' . $menuOrientation . '">', $this->menu)
            ->html('<' . $eltName . $this->getEltDecorationStr() . '>' . $content . '</' . $eltName . '>')
            ->html((string) $this->menu, $this->menu)
            ->html('</div>', $this->menu)
            ->getHtml();
    }

    /**
     * @return $this
     */
    protected function registerCss()
    {
        $this->addCssClasses($this->getMenuCssClasses());
        $this->addCssClass($this->cssClass);
        $this->addCssClass('btn-block', $this->block);
        $this->addCssClass('btn-flat', $this->flat);
        $this->addCssClass('btn-' . $this->size, $this->size);
        $this->addCssClass('btn-' . $this->status, $this->status);
        $this->addCssClass('disabled', $this->disabled);
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function registerAttrs()
    {
        $this->setAttributes($this->getMenuAttributes(), true);
        $this->setAttributes($this->getModalAttributes(), true);
        $this->setAttribute('id', $this->id, false, $this->id);
        $this->setAttribute('role', 'button', true, $this->url);
        $this->setAttribute('type', 'button', true, $this->url);
        if ($this->url) {
            $this->setAttributes($this->getUrlAttributes(true));
        }
        $this->setAttributes($this->getTooltipAttributes());
        $this->setAttributes($this->getPopoverAttributes());
        return $this;
    }
    
    /**
     * @param string $label
     * @param string $url
     * @param string $status
     * @param string $icon
     * @param bool $block
     * @param bool $disabled
     * @param bool $flat
     * @param string $size
     */
    public function __construct($label = null, $url = null, $status = null, $icon = null, $block = false, $disabled = false, $flat = false, $size = self::SIZE_NORMAL)
    {
        return $this->__invoke($label, $url, $status, $icon, $block, $disabled, $flat, $size);
    }
}