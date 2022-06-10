<?php
namespace Osf\Form\Helper;

use Osf\Form\AbstractForm;
use Osf\Form\Helper\AbstractFormHelper;
use Osf\View\OsfView as View;
use Osf\Form\TableForm;
use View\Helper\Bootstrap\Tools\Checkers;

/**
 * Display boostrap form
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 */
class Form extends AbstractFormHelper
{
    const DEFAULT_ATTRIBUTES = [
    	'method' => 'POST',
        'accept-charset' => 'UTF-8'
    ];
    const OR_VERTICAL   = 'v';
    const OR_HORIZONTAL = 'h';
    
    /**
     * @var AbstractForm
     */
    protected $form;
    protected $skin;
    protected $orientation = self::OR_VERTICAL;
    protected $attributes = [];
    
    protected function getDefaultSkin()
    {
        return __DIR__ . '/FormSkin/bootstrap.phtml';
    }
    
    /**
     * Skin file (View file)
     * @param string $skinFile
     * @return $this
     */
    public function setSkin($skinFile)
    {
        if (!file_exists($skinFile)) {
            throw new \Osf\Exception\ArchException('Unable to use the skin [' . $skinFile . ']. Not found.');
        }
        $this->skin = realpath($skinFile);
        return $this;
    }
    
    /**
     * Get form skin file
     * @return string
     */
    public function getSkin()
    {
        return $this->skin ? $this->skin : $this->getDefaultSkin();
    }
    
    /**
     * @return $this
     */
    public function setHorizontal()
    {
        $this->orientation = self::OR_HORIZONTAL;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setVertical()
    {
        $this->orientation = self::OR_VERTICAL;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }
    
    /**
     * @return \Osf\Form\AbstractForm
     */
    public function getForm()
    {
        return $this->form;
    }
    
    /**
     * Cible dans laquelle afficher le contenu renvoyé par le lien du formulaire
     * @param string $target
     * @return $this
     */
    public function setTarget(string $target)
    {
        $this->setAttribute('target', $target);
        return $this;
    }
    
    /**
     * Cible = page courante
     * @return $this
     */
    public function setTargetDefault()
    {
        $this->setTarget('#content');
        return $this;
    }
    
    /**
     * GET or POST
     * @return $this
     */
    public function setMethod(string $method)
    {
        $method = strtoupper($method);
        if (!in_array($method, ['POST', 'GET'])) {
            Checkers::notice('Bad form method');
        }
        $this->setAttribute('method', $method, true);
        return $this;
    }

    /**
     * @return $this
     */
    public function setMethodGet()
    {
        $this->setMethod('GET');
        return $this;
    }

    /**
     * @return $this
     */
    public function setMethodPost()
    {
        $this->setMethod('POST');
        return $this;
    }
    
    /**
     * Render form
     * @param AbstractForm $form
     * @return \Osf\Form\Helper\Form
     */
    public function __invoke(AbstractForm $form)
    {
        $this->form = $form;
        $this->skin = null;
        $this->orientation = self::OR_VERTICAL;
        $this->resetValues()->setAttributes(self::DEFAULT_ATTRIBUTES);
        return $this;
    }
    
    public function render()
    {
        if ($this->form instanceof TableForm) {
            $this->form->buildIfNotAlreadyBuilded();
        }
        return (new View)->render($this->getSkin(), ['form' => $this]);
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
