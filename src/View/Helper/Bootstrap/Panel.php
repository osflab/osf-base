<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\Title;
use Osf\View\Helper\Addon\Content;
use Osf\View\Helper\Addon\Footer;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\View\Helper\Bootstrap\Addon\StatusInterface;

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
class Panel extends AVH
{
    use Title;
    use Content;
    use Footer;
    use EltDecoration;
    use Addon\Status;
    use Addon\Icon;
    use Addon\Bufferize;
    
    protected $collapse;

    /**
     * Bootstrap panel
     * @param string $title
     * @param string $content
     * @param string $footer
     * @param string $status
     * @return \Osf\View\Helper\Bootstrap\Panel
     */
    public function __invoke(
            string $title  = null, 
            string $content = null, 
            string $footer  = null, 
            string $status  = null)
    {
        $this->initValues(get_defined_vars());
        $this->statusIsNullable(false);
        $this->statusSetDefault(AVH::STATUS_DEFAULT);
        $this->iconIsNullable(true);
        $this->setFooterClasses(['panel-footer']);
        return $this;
    }
    
    /**
     * Used internally by accordion helper
     * @param string $parent
     * @param string $id
     * @param bool $in
     * @return $this
     */
    public function setCollapsable(string $parent, string $id, bool $in)
    {
        $this->collapse = ['parent' => $parent, 'id' => $id, 'in' => ($in ? ' in' : '')];
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClasses(['panel', 'panel-' . $this->getStatus()]);
        $statusIcon = $this->getStatus() ? ($this->getStatus() === StatusInterface::STATUS_DANGER
                ? '<i class="fa fa-warning pull-right"></i>'
                : ($this->getStatus() === StatusInterface::STATUS_WARNING
                ? '<i class="fa fa-bell-o pull-right"></i>'
                : ($this->getStatus() === StatusInterface::STATUS_SUCCESS
                ? '<i class="fa fa-thumbs-o-up pull-right"></i>'
                : ''))) : '';
        return $this
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('  <div class="panel-heading clickable" data-toggle="collapse" data-parent="#' . $this->collapse['parent'] . '" href="#' . $this->collapse['id'] . '">' . $statusIcon, $this->title && $this->collapse)
            ->html('  <div class="panel-heading">' . $statusIcon, $this->title && !$this->collapse)
            ->html('   <h3 class="panel-title">', $this->title)
            ->html('   ' . $this->getIconHtml() . '&nbsp;', $this->title && $this->getIcon())
            ->html($this->title, $this->title)
            ->html('  </h3></div>', $this->title)
            ->html('  <div id="' . $this->collapse['id'] . '" class="panel-collapse collapse' . $this->collapse['in'] . '">', $this->collapse)
            ->html('    <div class="panel-body">' . $this->content . '</div>')
            ->html('    <div class="' . implode(' ', $this->footerClasses) . '">' . $this->footer . '</div>', $this->footer)
            ->html('  </div>', $this->collapse)
            ->html('</div>')
            ->getHtml();
    }
}