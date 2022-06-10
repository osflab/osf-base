<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap small box
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class SmallBox extends AVH
{
    use Addon\Icon;
    use Addon\Color; 
    use EltDecoration;
    
    protected $titleOrValue;
    protected $subTitle;
    protected $linkLabel;
    protected $linkUrl;
    protected $linkIcon;
    
    /**
     * Information box with link
     * @param string $title
     * @param string $content
     * @param string $type
     * @return \Osf\View\Helper\Bootstrap\SmallBox
     */
    public function __invoke($titleOrValue, $subTitle, $icon = null, $color = null, $linkLabel = null, $linkUrl = null, $linkIcon = 'fa-arrow-circle-right')
    {
        $icon  === null && $icon  = AVH::ICON_INFO;
        $color === null && $color = AVH::COLOR_BLUE;
        $this->initValues(get_defined_vars());
        $linkUrl  === null || Checkers::checkUrl($linkUrl);
        $linkIcon === null || Checkers::checkIcon($linkIcon);
        $this->linkLabel = $linkLabel === null ? __("More info") : $linkLabel;
        $this->linkUrl = $linkUrl;
        $this->linkIcon = $linkIcon;
        $this->titleOrValue = (string) $titleOrValue;
        $this->subTitle     = (string) $subTitle;
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClasses(['small-box', 'bg-' . $this->color]);
        return $this
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('  <div class="inner">')
            ->html('    <h3>' . $this->titleOrValue . '</h3>')
            ->html('    <p>' . $this->subTitle . '</p>')
            ->html('  </div>')
            ->html('  <div class="icon">')
            ->html($this->getIconHtml())
            ->html('  </div>')
            ->html('  <a href="' . $this->linkUrl . '" class="small-box-footer">', $this->linkUrl)
            ->html('    ' . $this->linkLabel . '&nbsp;&nbsp;<i class="fa ' . $this->linkIcon . '"></i>', $this->linkUrl)
            ->html('  </a>', $this->linkUrl)
            ->html('</div>')
            ->getHtml();
    }
}