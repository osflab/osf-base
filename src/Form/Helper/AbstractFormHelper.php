<?php
namespace Osf\Form\Helper;

use Osf\View\Helper\AbstractViewHelper;
use Osf\Form\Element\ElementAbstract;
use Osf\Stream\Html;
use Osf\Container\OsfContainer as Container;
use Osf\View\Helper\Addon\EltDecoration;
use Osf\Exception\ArchException;

/**
 * Form view helper parent class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class AbstractFormHelper extends AbstractViewHelper
{
    use EltDecoration;
    
    const SIZES = [
        1 =>  1,
        2 =>  2,
        3 =>  3,
        4 =>  4,
        5 =>  5,
        6 =>  6,
        7 =>  4,
        8 =>  8,
        9 =>  9,
       11 => 11,
       12 => 12,
       31 =>  3,
       61 =>  6,
    ];
    
    protected $size;
    
    /**
     * @var ElementAbstract
     */
    protected $elt;
    
    /**
     * Size : min 1, max 12
     * @param int $size
     * @return $this
     * @throws ArchException
     */
    public function setSize(int $size)
    {
        if (!array_key_exists($size, self::SIZES)) {
            throw new ArchException('Bad size value [' . $size . ']');
        }
        $this->size = $size;
        return $this;
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * @param ElementAbstract $elt
     * @return $this
     */
    public function setElement(ElementAbstract $elt)
    {
        $this->elt = $elt;
        return $this;
    }
    
    /**
     * @return \Osf\Form\Element\ElementAbstract
     */
    public function getElement()
    {
        return $this->elt;
    }
    
    /**
     * Add JS script to page header
     * @param string $script
     * @return $this
     */
    protected function addHeadScript($script) 
    {
        Container::getViewHelperLayout()->script()->addHeadScript($script);
        return $this;
    }
    
    /**
     * Add JS script to page footer
     * @param string $script
     * @return $this
     */
    protected function addFootScript($script)
    {
        Container::getViewHelperLayout()->script()->addFootScript($script);
        return $this;
    }
    
    /**
     * Build an input element
     * @param ElementAbstract $element
     * @param array $attributes
     * @param array $cssClasses
     * @param string $htmlElementName
     * @return string
     */
    protected function buildStandardElement(
            ElementAbstract $element, 
            array $attributes = [],
            array $cssClasses = [],
            string $htmlElementName  = 'input',
            bool $valueInElement = false)
    {
        // Etoile pour les éléments requis ?
        $addStar = $element->getForm() && $element->getForm()->getStarsForRequired() && $element->getRequired();
        $requiredSuffix = $addStar ? ' *' : '';
        
        // Attributs optionnels
        $element->getId() !== null && !isset($attributes['id']) && $attributes['id'] = $element->getId();
        $element->getPlaceholder() && $attributes['placeholder'] = $element->getPlaceholder() . $requiredSuffix;
        if (!$valueInElement && $element->getValue() !== null) {
            $attributes['value'] = htmlentities($element->getValue());
        }
        
        // Attributs requis et classes CSS
        $attributes['name'] = $element->getName();
        $this->addCssClasses($cssClasses);
        
        // Enregistrement des attributs et récupération
        $this->setAttributes($attributes);
        $this->setAddonAttributes($element);
        $attributes = $this->getAttributes();
        if (isset($attributes['title']) && $attributes['title'] === '') {
            unset($attributes['title']);
        }
        
        // Génération de l'élément
        return Html::buildHtmlElement($htmlElementName, $attributes, $valueInElement ? htmlentities($element->getValue()) : null);
    }
    
    /**
     * Add attributes from addons (to use in __invoke() methods of elements)
     * @return $this
     */
    protected function setAddonAttributes(ElementAbstract $element)
    {
        $this->setAttributes($element->getTooltipAttributes());
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function resetValues()
    {
        $this->resetDecorations();
        $this->size = null;
        return $this;
    }
}