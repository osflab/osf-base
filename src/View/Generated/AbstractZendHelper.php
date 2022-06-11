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
 * @method \Laminas\Form\View\Helper\Form form(\Laminas\Form\FormInterface $form = null)
 * @method \Laminas\Form\View\Helper\FormButton formButton(\Laminas\Form\ElementInterface $element = null, $buttonContent = null)
 * @method \Laminas\Form\View\Helper\FormCaptcha formCaptcha(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormCheckbox formCheckbox(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormCollection formCollection(\Laminas\Form\ElementInterface $element = null, $wrap = true)
 * @method \Laminas\Form\View\Helper\FormColor formColor(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormDate formDate(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormDateSelect formDateSelect(\Laminas\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Laminas\Form\View\Helper\FormDateTime formDateTime(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormDateTimeLocal formDateTimeLocal(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormDateTimeSelect formDateTimeSelect(\Laminas\Form\ElementInterface $element = null, $dateType = 1, $timeType = 1, $locale = null)
 * @method \Laminas\Form\View\Helper\FormElement formElement(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormElementErrors formElementErrors(\Laminas\Form\ElementInterface $element = null, array $attributes = [])
 * @method \Laminas\Form\View\Helper\FormEmail formEmail(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormFile formFile(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormHidden formHidden(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormImage formImage(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormInput formInput(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormLabel formLabel(\Laminas\Form\ElementInterface $element = null, $labelContent = null, $position = null)
 * @method \Laminas\Form\View\Helper\FormMonth formMonth(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormMonthSelect formMonthSelect(\Laminas\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Laminas\Form\View\Helper\FormMultiCheckbox formMultiCheckbox(\Laminas\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Laminas\Form\View\Helper\FormNumber formNumber(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormPassword formPassword(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormRadio formRadio(\Laminas\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Laminas\Form\View\Helper\FormRange formRange(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormReset formReset(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormRow formRow(\Laminas\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \Laminas\Form\View\Helper\FormSearch formSearch(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormSelect formSelect(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormSubmit formSubmit(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTel formTel(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormText formText(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTextarea formTextarea(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTime formTime(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormUrl formUrl(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormWeek formWeek(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Dumb captchaDumb(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Figlet captchaFiglet(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Image captchaImage(\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\ReCaptcha captchaReCaptcha(\Laminas\Form\ElementInterface $element = null)
 */
abstract class AbstractZendHelper extends AbstractHelper
{

    protected $availableZendHelpers = [
        'form' => '\\Laminas\\Form\\View\\Helper\\Form',
        'formButton' => '\\Laminas\\Form\\View\\Helper\\FormButton',
        'formCaptcha' => '\\Laminas\\Form\\View\\Helper\\FormCaptcha',
        'formCheckbox' => '\\Laminas\\Form\\View\\Helper\\FormCheckbox',
        'formCollection' => '\\Laminas\\Form\\View\\Helper\\FormCollection',
        'formColor' => '\\Laminas\\Form\\View\\Helper\\FormColor',
        'formDate' => '\\Laminas\\Form\\View\\Helper\\FormDate',
        'formDateSelect' => '\\Laminas\\Form\\View\\Helper\\FormDateSelect',
        'formDateTime' => '\\Laminas\\Form\\View\\Helper\\FormDateTime',
        'formDateTimeLocal' => '\\Laminas\\Form\\View\\Helper\\FormDateTimeLocal',
        'formDateTimeSelect' => '\\Laminas\\Form\\View\\Helper\\FormDateTimeSelect',
        'formElement' => '\\Laminas\\Form\\View\\Helper\\FormElement',
        'formElementErrors' => '\\Laminas\\Form\\View\\Helper\\FormElementErrors',
        'formEmail' => '\\Laminas\\Form\\View\\Helper\\FormEmail',
        'formFile' => '\\Laminas\\Form\\View\\Helper\\FormFile',
        'formHidden' => '\\Laminas\\Form\\View\\Helper\\FormHidden',
        'formImage' => '\\Laminas\\Form\\View\\Helper\\FormImage',
        'formInput' => '\\Laminas\\Form\\View\\Helper\\FormInput',
        'formLabel' => '\\Laminas\\Form\\View\\Helper\\FormLabel',
        'formMonth' => '\\Laminas\\Form\\View\\Helper\\FormMonth',
        'formMonthSelect' => '\\Laminas\\Form\\View\\Helper\\FormMonthSelect',
        'formMultiCheckbox' => '\\Laminas\\Form\\View\\Helper\\FormMultiCheckbox',
        'formNumber' => '\\Laminas\\Form\\View\\Helper\\FormNumber',
        'formPassword' => '\\Laminas\\Form\\View\\Helper\\FormPassword',
        'formRadio' => '\\Laminas\\Form\\View\\Helper\\FormRadio',
        'formRange' => '\\Laminas\\Form\\View\\Helper\\FormRange',
        'formReset' => '\\Laminas\\Form\\View\\Helper\\FormReset',
        'formRow' => '\\Laminas\\Form\\View\\Helper\\FormRow',
        'formSearch' => '\\Laminas\\Form\\View\\Helper\\FormSearch',
        'formSelect' => '\\Laminas\\Form\\View\\Helper\\FormSelect',
        'formSubmit' => '\\Laminas\\Form\\View\\Helper\\FormSubmit',
        'formTel' => '\\Laminas\\Form\\View\\Helper\\FormTel',
        'formText' => '\\Laminas\\Form\\View\\Helper\\FormText',
        'formTextarea' => '\\Laminas\\Form\\View\\Helper\\FormTextarea',
        'formTime' => '\\Laminas\\Form\\View\\Helper\\FormTime',
        'formUrl' => '\\Laminas\\Form\\View\\Helper\\FormUrl',
        'formWeek' => '\\Laminas\\Form\\View\\Helper\\FormWeek',
        'captchaDumb' => '\\Laminas\\Form\\View\\Helper\\Captcha\\Dumb',
        'captchaFiglet' => '\\Laminas\\Form\\View\\Helper\\Captcha\\Figlet',
        'captchaImage' => '\\Laminas\\Form\\View\\Helper\\Captcha\\Image',
        'captchaReCaptcha' => '\\Laminas\\Form\\View\\Helper\\Captcha\\ReCaptcha',
    ];

}