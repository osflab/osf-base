<?php
namespace Osf\Form\Element;

use \Osf\View\Helper\Bootstrap\Addon\Status;

/**
 * Submit element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class ElementSubmit extends ElementAbstract
{
    use Status;
    
    protected $waitOnClick = true;
    protected $recaptchaSitekey = null;
    
    public function __construct($nameOrParams = null, array $params = null)
    {
        $this->setIgnore(); // Bouton submit ignoré par défaut
        parent::__construct($nameOrParams, $params);
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param $waitOnClick bool
     * @return $this
     */
    public function setWaitOnClick($waitOnClick = true)
    {
        $this->waitOnClick = (bool) $waitOnClick;
        return $this;
    }

    /**
     * @return bool
     */
    public function getWaitOnClick():bool
    {
        return $this->waitOnClick;
    }
    
    /**
     * @param string|null $recaptchaSitekey
     * @return $this
     */
    public function setRecaptchaSitekey($recaptchaSitekey)
    {
        $this->recaptchaSitekey = $recaptchaSitekey === null ? null : (string) $recaptchaSitekey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecaptchaSitekey()
    {
        return $this->recaptchaSitekey;
    }
    
    public function __toString() {
        $this->status || $this->statusPrimary();
        return parent::__toString();
    }
}