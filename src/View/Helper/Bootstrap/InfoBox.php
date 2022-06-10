<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Helper\Addon\Title;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap info box
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class InfoBox extends AVH
{
    use Title;
    use Addon\Icon;
    use Addon\Color; 
    use EltDecoration;
    
    protected $value;
    protected $percentage;
    protected $progressDescription;
    
    /**
     * Information box with a numeric value
     * @param string $title
     * @param string $value
     * @param string $icon
     * @param string $color
     * @param int $percentage
     * @param string $progressDescription
     * @return \Osf\View\Helper\Bootstrap\InfoBox
     */
    public function __invoke($title, $value, $icon = null, $color = null, $percentage = null, $progressDescription = null)
    {
        $icon  === null && $icon  = AVH::ICON_INFO;
        $color === null && $color = AVH::COLOR_BLUE;
        $this->initValues(get_defined_vars());
        $percentage === null || Checkers::checkPercentage($percentage);
        $this->value = (string) $value;
        $this->percentage = $percentage;
        $this->progressDescription = (string) $progressDescription;
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClass('info-box');
        $this->addCssClass('bg-' . $this->color, $this->percentage);
        return $this
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('  <span class="info-box-icon' . ($this->percentage === null ? ' bg-' . $this->color : '') . '">' . $this->getIconHtml() . '</span>')
            ->html('  <div class="info-box-content">')
            ->html('    <span class="info-box-text">' . $this->title . '</span>')
            ->html('    <span class="info-box-number">' . $this->value . '</span>')
            ->html('    <div class="progress"><div class="progress-bar" style="width: ' . (int) $this->percentage . '%"></div></div>', $this->percentage !== null)
            ->html('    <span class="progress-description">' . $this->progressDescription . '</span>', $this->progressDescription)
            ->html('  </div>')
            ->html('</div>')
            ->getHtml();
    }
}