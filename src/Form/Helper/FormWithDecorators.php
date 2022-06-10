<?php
namespace Osf\Form\Helper;

use Osf\Form\Decorator\DecoratorFormAbstract;
use Osf\Form\Helper\AbstractFormHelper;
use Osf\Form\AbstractForm;
use Osf\Stream\Html;
use Osf\Container\OsfContainer as Container;

/**
 * Display html form
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 * @deprecated
 */
class FormWithDecorators extends AbstractFormHelper
{
    /**
     * @var DecoratorFormAbstract
     */
    protected static $defaultDecorator = null;
    
    /**
     * @var DecoratorFormAbstract
     */
    protected $decorator;
    
    protected $attributes = array(
    	'method' => 'POST',
        'accept-charset' => 'UTF-8'
    );
    
    protected function getHtmlErrors(array $errors, DecoratorFormAbstract $decorator)
    {
        $output = '';
        if (count($errors)) {
            $output .= $decorator->getBeforeErrors();
            foreach ($errors as $message) {
                $output .= $decorator->getBeforeError()
                        .  $message
                        .  $decorator->getAfterError();
            }
            $output .= $decorator->getAfterErrors();
        }
        return $output;
    }

    public function __invoke(AbstractForm $form)
    {
        $decorator = $this->getDecorator();
        $formContent = $decorator->getBeforeElements();
        $labelHelper = Container::getViewHelper()->label;
        foreach ($form->getElements() as $element) {
            $label = $element->getLabel() ? $element->getLabel() . $decorator->getLabelSuffix() : '';
            $errorContent = $this->getHtmlErrors($element->getErrors(), $decorator);
            if ($errorContent) {
                $element->getHelper()->setAttribute('class', 'error');
            }
            $formContent .= $decorator->getBeforeLabel() 
                          . $labelHelper($label, $element->getName())
                          . $decorator->getAfterLabel();
            $formContent .= $decorator->getBeforeElement() 
                          . $element 
                          . $decorator->getAfterElement();
            $formContent .= $errorContent;
        }
        $formContent .= $decorator->getAfterElements();
        $attributes = $this->getAttributes();
        $formContentHtml = Html::buildHtmlElement('form', $attributes, $formContent);
        return $decorator->getBeforeForm() . $formContentHtml . $decorator->getAfterForm();
    }

    /**
     * @return DecoratorFormAbstract
     */
    public function getDecorator()
    {
        if (!$this->decorator) {
            if (self::$defaultDecorator instanceof DecoratorFormAbstract) {
                $this->decorator = self::$defaultDecorator;
            } else {
                $this->decorator = new \Osf\Form\Decorator\DecoratorFormList;
            }
        }
        return $this->decorator;
    }
    
    public function setDecorator(DecoratorFormAbstract $decorator)
    {
        $this->decorator = $decorator;
    }
    
    public static function setDefaultFormDecorator(DecoratorFormAbstract $decorator)
    {
        self::$defaultDecorator = $decorator;
    }
}