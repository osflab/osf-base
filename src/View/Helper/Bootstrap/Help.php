<?php 
namespace Osf\View\Helper\Bootstrap;

use Osf\Crypt\Crypt;
use Osf\Container\OsfContainer as Container;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Helper\Addon\MvcUrl;
use Osf\View\Helper\Addon\EltDecoration;

/**
 * Help icon + modal
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Help extends AVH
{
    const MODAL_KEY = 'helpmodal';
    
    use MvcUrl;
    use EltDecoration;
    use Addon\Icon;
    
    protected $helpFileBaseName;
    protected $hash;
    
    /**
     * Boite d'information avec lien
     * @param string $title
     * @param string $content
     * @param string $type
     * @param string $badge
     * @param bool $coloredTitleBox
     * @param bool $collapsable
     * @param bool $expandable
     * @param bool $removable
     * @return \Osf\View\Helper\Bootstrap\Box
     */
    public function __invoke(
            string $helpFileBaseName, 
            string $controller = 'info')
    {
        if (!preg_match('#^[a-z/_-]+$#', $helpFileBaseName)) {
            throw new ArchException('bad helpFileBaseName syntax [' . $helpFileBaseName . ']');
        }
        $this->hash = self::hash($helpFileBaseName);
        $this->helpFileBaseName = $helpFileBaseName;
        $action = 'help_' . $helpFileBaseName;
        $this->initValues(get_defined_vars());
        $this->iconSetDefault('fa-question-circle');
        return $this;
    }
    
    /**
     * @return string
     */
    public function render()
    {
//        static $generated = false;
//        
//        if (!$generated) {
        $modal = (string) Container::getViewHelper()
                ->modal($this->hash, null, ' ', null, AVH::STATUS_INFO);
        $this->html($modal);
//            $generated = true;
//        }
        $modalLink = (string) Container::getViewHelper()
                ->modalLink($this->getIconHtml(), $this->hash, [], 'a', false)
                ->setLoadUrl($this->getMvcUrl());
        return $this->html($modalLink)->getHtml();
    }
    
    /**
     * Get a file hash for the modal id
     * @param string $helpFileBaseName
     * @return string
     */
    public static function hash(string $helpFileBaseName)
    {
        return Crypt::hash($helpFileBaseName);
    }
}
