<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Addon\Content;
use Osf\View\Helper\Addon\SubContent;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;

/**
 * Big message (like jumbotron)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class BigMsg extends AVH
{
    use Content;
    use SubContent;
    use EltDecoration;
    use Addon\Status;
    
    /**
     * Very visible information box
     * @param string|null $content
     * @param string|null $subContent
     * @param string|null $status
     * @return \Osf\View\Helper\Bootstrap\BigMsg
     */
    public function __invoke(?string $content = null, ?string $subContent = null, ?string $status = AVH::STATUS_INFO)
    {
        $this->initValues(get_defined_vars());
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClasses([
            'bm',
            'bm-' . $this->getStatus()
        ]);
        return $this 
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('<div>' . $this->content . '</div>', $this->content)
            ->html('<small>' . $this->subContent . '</small>', $this->subContent)
            ->html('</div>')
            ->getHtml();
    }
}