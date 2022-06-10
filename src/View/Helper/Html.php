<?php
namespace Osf\View\Helper;

use Osf\View\Helper\AbstractViewHelper as AVH;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Container\OsfContainer as Container;
use Osf\Stream\Text as T;

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
class Html extends AVH
{
    const CROP_AUTO = 'auto';
    
    use Addon\EltDecoration;
    use Bootstrap\Addon\Tooltip;
    use Bootstrap\Addon\Popover;
    use Bootstrap\Addon\Menu;
    
    protected $content;
    protected $elt;
    protected $escape;
    
    // Display for mobile or tablet null = display, true = only, false = no
    protected $mobile = null;
    protected $tablet = null; // = tablet + mobile
    protected $mobileCrop = null; // Limite de caractères pour petits écrans
    
    /**
     * @param string $type
     * @return \Osf\View\Helper\Html
     */
    public function __invoke($content, string $elt = null, array $attributes = [], bool $escape = true)
    {
        $this->resetDecorations();
        $this->initValues(get_defined_vars());
        $this->content = (string) $content;
        $this->setElement((string) $elt);
        $this->escape = $escape;
        $this->mobile = null;
        $this->tablet = null;
        $this->mobileCrop = null;
        return clone $this;
    }
    
    /**
     * Set an html element to decorate data
     * @param string $elt
     * @return $this
     */
    public function setElement(string $elt)
    {
        $this->elt = $elt;
        return $this;
    }
    
    /**
     * Generate only for mobiles
     * @return $this
     */
    public function mobileOnly()
    {
        $this->mobile = true;
        return $this;
    }
    
    /**
     * Do not generate for mobiles and hide content on little screens
     * @return $this
     */
    public function mobileExclude()
    {
        $this->mobile = false;
        return $this;
    }
    
    /**
     * Generate only for mobiles and tablets
     * @return $this
     */
    public function mobileAndTabletOnly()
    {
        $this->tablet = true;
        return $this;
    }
    
    /**
     * Do not generate for mobiles and tablets, hide content on little screens
     * @return $this
     */
    public function mobileAndTabletExclude()
    {
        $this->tablet = false;
        return $this;
    }
    
    /**
     * Maximum chars count for little screens
     * @param $nbChars int|null|'auto'
     * @return $this
     */
    public function mobileCrop($nbChars = self::CROP_AUTO)
    {
        $this->mobileCrop = $nbChars === null ? null : 
                ($nbChars === self::CROP_AUTO ? self::CROP_AUTO : (int) $nbChars);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMobileCrop()
    {
        return $this->mobileCrop;
    }
    
    /**
     * Escape output or not
     * @param bool $trueOrFalse
     * @return $this
     */
    public function escape(bool $trueOrFalse = true)
    {
        $this->escape = $trueOrFalse;
        return $this;
    }
    
    /**
     * @param string $type
     * @return \Osf\View\Helper\HtmlList
     */
    public function setType(string $type)
    {
        if ($type !== self::TYPE_UL && $type !== self::TYPE_OL) {
            Checkers::notice('Bad list type [' . $type . '].');
        } else {
            $this->type = $type;
        }
        return $this;
    }
    
    /**
     * @param string $html
     * @return \Osf\View\Helper\HtmlList
     */
    public function addItem(string $html)
    {
        $this->items[] = $html;
        return $this;
    }
    
    /**
     * Return HTML content
     * @return string
     */
    protected function render()
    {
        // S'il ne faut pas afficher aux mobiles et que c'est un mobile
        // Ou qu'il ne faut pas afficher aux Tablettes/mobiles et c'est l'un ou l'autre
        if ($this->mobile !== null || $this->tablet !== null) {
            if (($this->mobile === false && Container::getDevice()->isMobile())
            ||  ($this->tablet === false && Container::getDevice()->isMobileOrTablet())) {
                return '';
            }
            
            // Ou qu'il faut exclusivement afficher aux mobiles et que c'est pas un mobile
            // Ou qu'il faut exclusivement afficher aux Tablettes/mobiles et c'en est pas
            if (($this->mobile === true && !Container::getDevice()->isMobile())
            ||  ($this->tablet === true && !Container::getDevice()->isMobileOrTablet())) {
                $this->addCssClass('visible-xs-inline');
            }
            
            // S'il ne faut pas afficher aux tablettes ou mobiles et que s'en est pas
            // alors on affiche pas si l'écran est trop petit. S'il n'y a pas de balise
            // on ajoute automatiquement un span. 
            else if (($this->mobile === false || $this->tablet === false) 
            &&  !Container::getDevice()->isMobileOrTablet()) {
                $this->addCssClass('hidden-xs');
            }
        }
        
        // Ajoute dans le contenu les données insérées avec la méthode ->html()
        // et réinitialise les lignes de contenu 
        $this->content .= $this->getHtml();
        $this->lines = [];
        
        // S'il faut réduire la chaîne de caractères pour les petits écrans, 
        // on génère le contenu pour petit écran et/ou pour grand selon le
        // périphérique utilisé
        if ($this->mobileCrop && $this->mobileCrop !== self::CROP_AUTO) {
            $croppedContent = T::crop($this->content, $this->mobileCrop);
            if (Container::getDevice()->isMobile()) {
                $content = $this->escape ? $this->esc($croppedContent) : $croppedContent;
                unset($croppedContent);
            } else {
                $content = $this->escape ? $this->esc($this->content) : $this->content;
                $this->addCssClass('hidden-xs');
                $croppedContent = $this->escape ? $this->esc($croppedContent) : $croppedContent;
            }
        }
        
        // Si le contenu n'est toujours pas généré, on le génère
        if (!isset($content)) {
            $content = $this->escape ? $this->esc($this->content) : $this->content;
        }
        
        // S'il faut déléguer le crop au front-end, on ajoute la classe text-overflow
        if ($this->mobileCrop === self::CROP_AUTO) {
            $this->addCssClass('text-overflow');
        }
        
        // Ajout des tooltip / popover bootstrap
        $this->setAttributes($this->getTooltipAttributes());
        $this->setAttributes($this->getPopoverAttributes());
        
        // Décoration pour ajout d'un menu
        $this->addCssClasses($this->getMenuCssClasses());
        $this->setAttributes($this->getMenuAttributes());
        $menuOrientation = $this->menu ? ' ' . $this->menu->getOrientationClass() : '';
        
        // Ajoute un élément span par défaut s'il y a des attributs à attacher 
        // aux données.
        if (!$this->elt && $this->getAttributes()) {
            $this->setElement('span');
        }
        
        // Génère la balise et son contenu
        $this->elt !== '' && $this->html('<' . $this->elt . $this->getEltDecorationStr() . '>');
        $this->html($content);
        $this->elt !== '' && $this->html('</' . $this->elt . '>');
        
        // S'il y a un contenu croppé pour petits écrans, on le génère 
        if (isset($croppedContent)) {
            $this->removeCssClass('hidden-xs');
            $this->addCssClass('visible-xs-inline');
            $this->html('<' . $this->elt . $this->getEltDecorationStr() . '>');
            $this->html($croppedContent);
            $this->html('</' . $this->elt . '>');
        }
        
        // On récupère le contenu html
        $html = $this->getHtml();
        
        // Puis on décore avec un éventuel menu
        if ($this->menu) {
            $html = $this
                ->html('<div class="inline dropdown clickable' . $menuOrientation . '">')
                ->html($html)
                ->html((string) $this->menu)
                ->html('</div>')
                ->getHtml();
        }
        
        return $html;
    }
}
