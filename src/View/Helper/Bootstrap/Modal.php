<?php 

namespace Osf\View\Helper\Bootstrap;

use Osf\View\Helper\Addon\Title;
use Osf\View\Helper\Addon\Content;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
// use Osf\View\Component;
// use Osf\Container\OsfContainer as Container;

/**
 * Bootstrap modal
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Modal extends AVH
{
    const SIZE_LARGE = ' modal-lg';
    const SIZE_SMALL = ' modal-sm';
    const SIZE_NORMAL = '';    
    
    use Title;
    use Content;
    use EltDecoration;
    use Addon\Status;
    use Addon\Bufferize;
    
    protected $id      = null;
    protected $footer  = null;
    protected $fade    = true;
    protected $size    = self::SIZE_NORMAL;

    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param string $footer
     * @return $this
     */
    public function setFooter($footer)
    {
        $this->footer = (string) $footer;
        return $this;
    }
    
    public function getFooter()
    {
        return $this->footer;
    }
    
    /**
     * @return $this
     */
    public function setSizeNormal()
    {
        $this->size = self::SIZE_NORMAL;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setSizeLarge()
    {
        $this->size = self::SIZE_LARGE;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setSizeSmall()
    {
        $this->size = self::SIZE_SMALL;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setFade($trueOrFalse)
    {
        $this->fade = (bool) $trueOrFalse;
        return $this;
    }
    
    /**
     * Bootstrap panel
     * @param string $header
     * @param string $content
     * @param string $footer
     * @param string $status
     * @return \Osf\View\Helper\Bootstrap\Modal
     */
    public function __invoke($id, $title = null, $content = null, $footer = null, $status = null)
    {
        $statusIsNullable = true;
        $this->initValues(get_defined_vars());
        trim($id) || Tools\Checkers::notice('Modal id is required.');
        $this->id = (string) $id;
        $this->setFooter($footer);
        return $this;
    }
    
    /**
     * @return string
     */
    protected function render()
    {
        $this->addCssClass('modal');
        $this->addCssClass('modal-' . $this->status, $this->status);
        $this->addCssClass('fade', $this->fade);
        $this->setAttributes([
            'id'   => $this->id,
            'role' => 'dialog',
            'tabindex' => -1,
            'aria-labelledby' => $this->id . 'Label'
        ]);
        
        // Ferme le modal correctement quand on appuie sur le bouton "back"
        // @task ne fonctionne pas sur les téléphones android
//        if (!Container::getDevice()->isMobileOrTablet()) {
//            $script = "\$('#" . $this->id . "').on('shown.bs.modal',function(){"
//            . "history.pushState(null,null,'#' + $(this).attr('id'));});"
//            . "\$(window).on('popstate',function(){\$('#" . $this->id . "').modal('hide');});";
//            Component::getJquery()->registerScript($script);
//        }
        
        return $this
            ->html('<div' . $this->getEltDecorationStr() . '>')
            ->html('  <div class="modal-dialog' . $this->size . '" role="document">')
            ->html('    <div class="modal-content">')
            ->html('      <div class="modal-header">')
            ->html('        <button type="button" class="close" data-dismiss="modal" aria-label="Close">')
            ->html('          <span aria-hidden="true">&times;</span>')
            ->html('        </button>')
            ->html('        <h4 class="modal-title" id="' . $this->id . 'Label">' . $this->title . '</h4>')
            ->html('      </div>')
            ->html('      <div class="modal-body">' . $this->content . '</div>', $this->content)
            ->html('      <div class="modal-footer">' . $this->footer . '</div>', $this->footer)
            ->html('    </div>')
            ->html('  </div>')
            ->html('</div>')
            ->getHtml();
    }
}