<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\Title;
use Osf\View\Helper\Addon\Content;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Bootstrap alert message
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Alert extends AVH
{
    use Title;
    use Content;
    use EltDecoration;
    use Addon\Status;
    use Addon\Icon;
    use Addon\Removable;
    
    /**
     * Information box deletable with title and icon
     * @param string $title
     * @param string $content
     * @param string $status
     * @param string $icon
     * @return \Osf\View\Helper\Bootstrap\Alert
     */
    public function __invoke(
            string $title   = null, 
            string $content = null, 
            string $status  = null, 
            string $icon    = null)
    {
        $this->resetDecorations();
        $this->statusSetDefault(self::STATUS_INFO);
        $this->initValues(get_defined_vars());
        $this->removable(true);
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        if (!$this->icon && $this->status) {
            $this->icon(self::STATUS_ICONS[$this->status]);
        }
        $this->addCssClasses(['alert', 'alert-' . $this->status]);
        $this->removable && $this->addCssClass('alert-dismissible');
        return $this 
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', $this->removable)
            ->html('  <h4>', $this->title && $this->content)
            ->html($this->icon ? $this->getIconHtml() . ' ' : '')
            ->html($this->title, $this->title)
            ->html('  </h4>', $this->title && $this->content)
            ->html($this->content, $this->content)
            ->html('</div>')
            ->getHtml();
    }
}
