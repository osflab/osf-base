<?php
namespace Osf\View\Generated;

use Osf\View\AbstractHelper;

/**
 * Zend Helpers builders
 *
 * @version 1.0
 * @author Guillaume Ponçon - OpenStates Framework PHP Generator
 * @since OSF 3.0.0
 * @package osf
 * @subpackage generated
 * @method \Zend\Form\View\Helper\Form form(\Zend\Form\FormInterface $form = null)
 * @method \Zend\Form\View\Helper\FormButton formButton(\Zend\Form\ElementInterface $element = null, $buttonContent = null)
 * @method \Zend\Form\View\Helper\FormCaptcha formCaptcha(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormCheckbox formCheckbox(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormCollection formCollection(\Zend\Form\ElementInterface $element = null, $wrap = true)
 * @method \Zend\Form\View\Helper\FormColor formColor(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDate formDate(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDateSelect formDateSelect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormDateTime formDateTime(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDateTimeLocal formDateTimeLocal(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDateTimeSelect formDateTimeSelect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $timeType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormElement formElement(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormElementErrors formElementErrors(\Zend\Form\ElementInterface $element = null, array $attributes = [])
 * @method \Zend\Form\View\Helper\FormEmail formEmail(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormFile formFile(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormHidden formHidden(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormImage formImage(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormInput formInput(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormLabel formLabel(\Zend\Form\ElementInterface $element = null, $labelContent = null, $position = null)
 * @method \Zend\Form\View\Helper\FormMonth formMonth(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormMonthSelect formMonthSelect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormMultiCheckbox formMultiCheckbox(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Zend\Form\View\Helper\FormNumber formNumber(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormPassword formPassword(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormRadio formRadio(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Zend\Form\View\Helper\FormRange formRange(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormReset formReset(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormRow formRow(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \Zend\Form\View\Helper\FormSearch formSearch(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormSelect formSelect(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormSubmit formSubmit(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTel formTel(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormText formText(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTextarea formTextarea(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTime formTime(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormUrl formUrl(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormWeek formWeek(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\Captcha\Dumb captchaDumb(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\Captcha\Figlet captchaFiglet(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\Captcha\Image captchaImage(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\Captcha\ReCaptcha captchaReCaptcha(\Zend\Form\ElementInterface $element = null)
 */
abstract class AbstractZendHelper extends AbstractHelper
{

    protected $availableZendHelpers = [
        'form' => '\\Zend\\Form\\View\\Helper\\Form',
        'formButton' => '\\Zend\\Form\\View\\Helper\\FormButton',
        'formCaptcha' => '\\Zend\\Form\\View\\Helper\\FormCaptcha',
        'formCheckbox' => '\\Zend\\Form\\View\\Helper\\FormCheckbox',
        'formCollection' => '\\Zend\\Form\\View\\Helper\\FormCollection',
        'formColor' => '\\Zend\\Form\\View\\Helper\\FormColor',
        'formDate' => '\\Zend\\Form\\View\\Helper\\FormDate',
        'formDateSelect' => '\\Zend\\Form\\View\\Helper\\FormDateSelect',
        'formDateTime' => '\\Zend\\Form\\View\\Helper\\FormDateTime',
        'formDateTimeLocal' => '\\Zend\\Form\\View\\Helper\\FormDateTimeLocal',
        'formDateTimeSelect' => '\\Zend\\Form\\View\\Helper\\FormDateTimeSelect',
        'formElement' => '\\Zend\\Form\\View\\Helper\\FormElement',
        'formElementErrors' => '\\Zend\\Form\\View\\Helper\\FormElementErrors',
        'formEmail' => '\\Zend\\Form\\View\\Helper\\FormEmail',
        'formFile' => '\\Zend\\Form\\View\\Helper\\FormFile',
        'formHidden' => '\\Zend\\Form\\View\\Helper\\FormHidden',
        'formImage' => '\\Zend\\Form\\View\\Helper\\FormImage',
        'formInput' => '\\Zend\\Form\\View\\Helper\\FormInput',
        'formLabel' => '\\Zend\\Form\\View\\Helper\\FormLabel',
        'formMonth' => '\\Zend\\Form\\View\\Helper\\FormMonth',
        'formMonthSelect' => '\\Zend\\Form\\View\\Helper\\FormMonthSelect',
        'formMultiCheckbox' => '\\Zend\\Form\\View\\Helper\\FormMultiCheckbox',
        'formNumber' => '\\Zend\\Form\\View\\Helper\\FormNumber',
        'formPassword' => '\\Zend\\Form\\View\\Helper\\FormPassword',
        'formRadio' => '\\Zend\\Form\\View\\Helper\\FormRadio',
        'formRange' => '\\Zend\\Form\\View\\Helper\\FormRange',
        'formReset' => '\\Zend\\Form\\View\\Helper\\FormReset',
        'formRow' => '\\Zend\\Form\\View\\Helper\\FormRow',
        'formSearch' => '\\Zend\\Form\\View\\Helper\\FormSearch',
        'formSelect' => '\\Zend\\Form\\View\\Helper\\FormSelect',
        'formSubmit' => '\\Zend\\Form\\View\\Helper\\FormSubmit',
        'formTel' => '\\Zend\\Form\\View\\Helper\\FormTel',
        'formText' => '\\Zend\\Form\\View\\Helper\\FormText',
        'formTextarea' => '\\Zend\\Form\\View\\Helper\\FormTextarea',
        'formTime' => '\\Zend\\Form\\View\\Helper\\FormTime',
        'formUrl' => '\\Zend\\Form\\View\\Helper\\FormUrl',
        'formWeek' => '\\Zend\\Form\\View\\Helper\\FormWeek',
        'captchaDumb' => '\\Zend\\Form\\View\\Helper\\Captcha\\Dumb',
        'captchaFiglet' => '\\Zend\\Form\\View\\Helper\\Captcha\\Figlet',
        'captchaImage' => '\\Zend\\Form\\View\\Helper\\Captcha\\Image',
        'captchaReCaptcha' => '\\Zend\\Form\\View\\Helper\\Captcha\\ReCaptcha',
    ];

}