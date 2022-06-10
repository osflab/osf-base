<?php
namespace Osf\Form\Helper;

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
class AbstractFormElementListHelper extends AbstractFormHelper
{
    protected $beforeOptions  = '';
    protected $betweenOptions = '';
    protected $afterOptions   = '';
    protected $beforeLabel    = '';
    protected $afterLabel     = '';
    
    public function getBeforeOptions()
    {
        return $this->beforeOptions;
    }

    public function getBetweenOptions()
    {
        return $this->betweenOptions;
    }

    public function getAfterOptions()
    {
        return $this->afterOptions;
    }

    /**
     * @param string $beforeOptions
     * @return \Osf\Form\Helper\AbstractFormOptionListHelper
     */
    public function setBeforeOptions($beforeOptions)
    {
        $this->beforeOptions = $beforeOptions;
        return $this;
    }

    /**
     * @param string $betweenOptions
     * @return \Osf\Form\Helper\AbstractFormOptionListHelper
     */
    public function setBetweenOptions($betweenOptions)
    {
        $this->betweenOptions = $betweenOptions;
        return $this;
    }

    /**
     * @param string $afterOptions
     * @return \Osf\Form\Helper\AbstractFormOptionListHelper
     */
    public function setAfterOptions($afterOptions)
    {
        $this->afterOptions = $afterOptions;
        return $this;
    }
    
    public function getBeforeLabel()
    {
        return $this->beforeLabel;
    }
    
    public function getAfterLabel()
    {
        return $this->afterLabel;
    }
    
    /**
     * @param string $beforeLabel
     * @return \Osf\Form\Helper\AbstractFormElementListHelper
     */
    public function setBeforeLabel($beforeLabel)
    {
        $this->beforeLabel = $beforeLabel;
        return $this;
    }
    
    /**
     * @param string $afterLabel
     * @return \Osf\Form\Helper\AbstractFormElementListHelper
     */
    public function setAfterLabel($afterLabel)
    {
        $this->afterLabel = $afterLabel;
        return $this;
    }
}
