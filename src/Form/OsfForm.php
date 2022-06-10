<?php
namespace Osf\Form;

use Osf\Container\OsfContainer as C;
use Osf\Log\LogProxy as Log;
use Osf\Form\AbstractForm;

/**
 * Form class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage form
 */
class OsfForm extends AbstractForm
{
    const PERSIST_SESSION_PREFIX = 'FP_';
    
    protected $persistValues = false; // Persistance des valeurs en session
    
    /**
     * @param bool $persistValues Persistance des valeurs en session
     * @param string|null $focusedElt Focus sur un élément en particulier. Mettre x_y pour x = subform, y = elt name
     */
    public function __construct(bool $persistValues = false, ?string $focusedElt = null)
    {
        if (method_exists($this, 'init')) {
            $this->init();
        }
        if ($this->setPersistValues($persistValues)->getPersistValues()) {
            $values = C::getSession()->get($this->getPersistSessionKey());
            if ($values && is_array($values)) {
                $this->hydrate($values, null, true, true);
                $this->isValid();
            }
        }
        $this->setFocusedElt($focusedElt);
    }
    
    /**
     * Define the focused element
     * @param string $focusedElt
     * @return $this
     */
    public function setFocusedElt(?string $focusedElt = null)
    {
        if ($focusedElt !== null) {
            if (strpos($focusedElt, '~')) {
                $elt = explode('~', $focusedElt, 2);
                $sf = $this->getSubForm($elt[0]);
                $elt = $sf ? $sf->getElement($elt[1]) : null;
            } else {
                $elt = $this->getElement($focusedElt);
            }
            $elt && $elt->setfocus();
            $elt || Log::hack('Tentative de focus sur element manquant [' . $focusedElt . ']');
        }
        return $this;
    }
    
    /**
     * Adding persistance 
     * @param array|null $values
     */
    public function isValid($values = null)
    {
        $valid = parent::isValid($values);
        if ($this->getPersistValues() && $valid) {
            C::getSession()->set($this->getPersistSessionKey(), $this->getValues());
        }
        return $valid;
    }
    
    /**
     * Set values persistant in the session
     * @param bool $persistValues
     * @return $this
     */
    public function setPersistValues($persistValues = true)
    {
        $this->persistValues = (bool) $persistValues;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPersistValues(): bool
    {
        return $this->persistValues;
    }
    
    /**
     * Current form persistance session key
     * @return string
     */
    protected function getPersistSessionKey(): string
    {
        return self::PERSIST_SESSION_PREFIX . strtr(get_class($this), '\\', '_');
    }
}
