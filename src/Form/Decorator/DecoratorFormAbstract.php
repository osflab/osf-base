<?php
namespace Osf\Form\Decorator;

/**
 * Form decorator super class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
abstract class DecoratorFormAbstract
{
    protected $beforeForm     = '';
    protected $afterForm      = '';
    protected $beforeElements = '';
    protected $afterElements  = '';
    protected $beforeLabel    = '';
    protected $afterLabel     = '';
    protected $beforeElement  = '';
    protected $afterElement   = '';
    protected $beforeErrors   = '';
    protected $afterErrors    = '';
    protected $beforeError    = '';
    protected $afterError     = '';
    protected $labelSuffix    = ' :';

    public function getBeforeError()
    {
        return $this->beforeError;
    }

    public function getAfterError()
    {
        return $this->afterError;
    }

    public function getBeforeForm()
    {
        return $this->beforeForm;
    }

    public function getAfterForm()
    {
        return $this->afterForm;
    }

    public function getBeforeElements()
    {
        return $this->beforeElements;
    }

    public function getAfterElements()
    {
        return $this->afterElements;
    }

    public function getBeforeLabel()
    {
        return $this->beforeLabel;
    }

    public function getAfterLabel()
    {
        return $this->afterLabel;
    }

    public function getBeforeElement()
    {
        return $this->beforeElement;
    }

    public function getAfterElement()
    {
        return $this->afterElement;
    }

    public function getBeforeErrors()
    {
        return $this->beforeErrors;
    }

    public function getAfterErrors()
    {
        return $this->afterErrors;
    }

    /**
     * @param string $beforForm
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setBeforeForm($beforeForm)
    {
        $this->beforeForm = $beforeForm;
        return $this;
    }

    /**
     * @param string $afterForm
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setAfterForm($afterForm)
    {
        $this->afterForm = $afterForm;
        return $this;
    }

    /**
     * @param string $beforeElements
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setBeforeElements($beforeElements)
    {
        $this->beforeElements = $beforeElements;
        return $this;
    }

    /**
     * @param string $afterElements
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setAfterElements($afterElements)
    {
        $this->afterElements = $afterElements;
        return $this;
    }

    /**
     * @param string $beforeLabel
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setBeforeLabel($beforeLabel)
    {
        $this->beforeLabel = $beforeLabel;
        return $this;
    }

    /**
     * @param string $afterLabel
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setAfterLabel($afterLabel)
    {
        $this->afterLabel = $afterLabel;
        return $this;
    }

    /**
     * @param string $beforeElement
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setBeforeElement($beforeElement)
    {
        $this->beforeElement = $beforeElement;
        return $this;
    }

    /**
     * @param string $afterElement
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setAfterElement($afterElement)
    {
        $this->afterElement = $afterElement;
        return $this;
    }

    /**
     * @param string $beforeErrors
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setBeforeErrors($beforeErrors)
    {
        $this->beforeErrors = $beforeErrors;
        return $this;
    }

    /**
     * @param string $afterErrors
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setAfterErrors($afterErrors)
    {
        $this->afterErrors = $afterErrors;
        return $this;
    }

    /**
     * @param string $beforeError
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setBeforeError($beforeError)
    {
        $this->beforeError = $beforeError;
        return $this;
    }

    /**
     * @param string $afterError
     * @return \Osf\Form\Decorator\DecoratorForm
     */
    public function setAfterError($afterError)
    {
        $this->afterError = $afterError;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLabelSuffix()
    {
        return $this->labelSuffix;
    }

    /**
     * @param unknown $labelSuffix
     * @return \Osf\Form\Decorator\DecoratorFormAbstract
     */
    public function setLabelSuffix($labelSuffix)
    {
        $this->labelSuffix = $labelSuffix;
        return $this;
    }
}