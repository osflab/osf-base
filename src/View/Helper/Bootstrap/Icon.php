<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap simple icon
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Icon extends AVH
{
    use Addon\Url;
    use Addon\Icon;
    use Addon\Status;
    use Addon\Tooltip;
    use Addon\Popover;
    use Addon\Modal;
    use Addon\Menu;
    use EltDecoration;

    protected $style;
    
    /**
     * Bootstrap Icon
     * @param string $icon
     * @param string $status
     * @param string $iconColor
     * @param bool $animated
     */
    public function __construct($icon = null, $status = null, $iconColor = null, bool $animated = false)
    {
        $this->init($icon, $status, $iconColor, $animated);
    }
    
    /**
     * Bootstrap Icon
     * @param string $icon
     * @param string $status
     * @param string $iconColor
     * @param bool $animated
     * @return \Osf\View\Helper\Bootstrap\Icon
     */
    public function __invoke($icon = null, $status = null, $iconColor = null, bool $animated = false)
    {
        $this->init($icon, $status, $iconColor, $animated);
        return $this;
    }
    
    /**
     * @param string $icon
     * @param string $status
     * @param string $iconColor
     * @param bool $animated
     * @return $this
     */
    protected function init($icon, $status, $iconColor, $animated)
    {
        $statusIsNullable = true;
        $this->initValues(get_defined_vars());
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClasses($this->getIconCssClasses());
        $this->addCssClasses($this->getMenuCssClasses());
        $this->addCssClass('text-' . $this->status, $this->status);
        //$this->addCssClass('text-' . $this->iconColor, $this->iconColor);
        $this->setAttributes($this->getTooltipAttributes());
        $this->setAttributes($this->getPopoverAttributes());
        $this->setAttributes($this->getModalAttributes());
        $this->setAttributes($this->getMenuAttributes());
        $this->setAttributes($this->getUrlAttributes());
        $menuOrientation = $this->menu ? ' ' . $this->menu->getOrientationClass() : '';
        return $this
            ->html('<div class="inline dropdown' . $menuOrientation . '">', $this->menu)
            ->html('<i' . $this->getEltDecorationStr() . '></i>', $this->icon)
            ->html((string) $this->menu, $this->menu)
            ->html('</div>', $this->menu)
            ->getHtml();
    }
}
