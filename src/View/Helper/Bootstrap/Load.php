<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\View\Helper\Addon\Content;
use Osf\View\Component;

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
class Load extends AVH
{
    const ID_PREFIX = 'load';
    const DEFAULT_ICON = 'hourglass-half';
    
    use EltDecoration;
    use Addon\Icon;
    use Addon\Url;
    use Content;
    
    /**
     * Loading box to display before the usefull content
     * @param string $icon
     * @return \Osf\View\Helper\Bootstrap\Load
     */
    public function __invoke(string $url = null, string $icon = null)
    {
        $this->initValues(get_defined_vars());
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        static $idCount = 0;
        
        if (!$this->icon) {
            $this->icon(self::DEFAULT_ICON);
        }
        $this->addCssClass('loadingbox');
        if ($this->url) {
            $id = self::ID_PREFIX . $idCount++;
            $this->setAttribute('id', $id);
            $js = '$.get("' . $this->url . '",function(data){$("#' . $id . '").replaceWith(data);});';
            Component::getJquery()->registerScript($js);
        }
        return $this 
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html($this->icon ? $this->getIconHtml(true) : '')
            ->html($this->getContent(), $this->getContent())
            ->html('</div>')
            ->getHtml();
    }
}