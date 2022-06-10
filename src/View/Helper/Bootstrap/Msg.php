<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Addon\Content;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;

/**
 * Bootstrap simplified callout message
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Msg extends AVH
{
    use Content;
    use EltDecoration;
    use Addon\Status;
    
    /**
     * Simple information box without title
     * @param string $content
     * @param string $status
     * @return \Osf\View\Helper\Bootstrap\Msg
     */
    public function __invoke($content, $status = AVH::STATUS_INFO)
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
            'callout',
            'callout-' . $this->getStatus()
        ]);
        return $this 
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html($this->content, $this->content)
            ->html('</div>')
            ->getHtml();
    }
}