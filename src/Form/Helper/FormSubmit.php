<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementSubmit;
use Osf\Exception\ArchException;

/**
 * Submit input element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class FormSubmit extends AbstractFormHelper
{
    /**
     * @param ElementSubmit $element
     * @return string
     */
    public function __invoke(ElementSubmit $element)
    {
        static $recaptchaUnique = false;
        
        $classes = ['btn', 'btn-' . $element->getStatus()];
        if ($element->getWaitOnClick()) {
            $classes[] = 'wait-on-click';
        }
        
        // @task [CATCHA] à faire plus tard...
        if ($element->getRecaptchaSitekey()) {
            throw new ArchException('Recaptcha à implémenter plus tard...');
            if ($recaptchaUnique) {
                throw new ArchException('Do not use more than one recaptcha by request');
            }
            $eltName = 'button';
            $valueInElement = true;
            $attrs = [];
            $attrs['data-sitekey'] = $element->getRecaptchaSitekey();
            $attrs['data-callback'] = 'rcSubmitForm';
            $attrs['id'] = 'rcbtn';
            $script = '<script>function rcSubmitForm(){console.log("submit...");$("#' . $attrs['id'] . '").parent("form").submit();}</script>';
        } 
        
        // Bouton normal
        else {
            $attrs = ['type' => 'submit'];
            $eltName = 'input';
            $valueInElement = false;
            $script = '';
        }
        return $script . $this->buildStandardElement($element, $attrs, $classes, $eltName, $valueInElement);
    }
}