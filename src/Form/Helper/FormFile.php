<?php
namespace Osf\Form\Helper;

use Osf\Form\Element\ElementFile;
use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Helper\Html;
use Osf\Stream\Html as Htm;
use Osf\Container\OsfContainer as Container;
use Osf\View\Helper\Bootstrap\Addon\Status;

/**
 * File element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class FormFile extends AbstractFormHelper
{
    use Status;
    
    /**
     * @var ElementFile
     */
    protected $pickIcon;
    protected $pickLabel;
    protected $id;
    protected $imageUrl;
    
//    // Envoi javascript du pickup. On peut désactiver ceci au deuxième appel javascript 
//    // afin d'éviter que l'image soit envoyée en double (FormFile.js exécuté 2 fois)
//    protected $sendScript = true;
    
    // Défini manuellement, ne pas toucher à ces valeurs
    protected $pickIconFixed = false;
    protected $pickLabelFixed = false;
    
    /**
     * @var Html
     */
    protected $container;
    
    /**
     * @param ElementFile $element
     * @return \Osf\Form\Helper\FormFile
     */
    public function __invoke(ElementFile $element)
    {
        $this->setElement($element);
        $this->initValues(get_defined_vars());
        $this->statusSetDefault('default');
        $this->setPickIcon('folder-open-o');
        $this->setPickLabel(__("Choisir un fichier"));
        $this->setContainer(new Html());
        $this->getContainer()
                ->setElement('div')
                ->addCssClasses(['btn', 'btn-file'])
                ->setAttribute('tabindex', 500)
                ->escape(false)
                ->disableEscape();
        $this->setAttribute('draggable');
        $this->pickIconFixed = false;
        $this->pickLabelFixed = false;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setPickIcon($pickIcon)
    {
        Checkers::checkIcon($pickIcon, 'folder-open-o');
        $this->pickIcon = $pickIcon;
        $this->pickIconFixed = true;
        return $this;
    }

    public function getPickIcon()
    {
        return $this->pickIcon;
    }
    
    /**
     * @return $this
     */
    public function setPickLabel($pickLabel)
    {
        $this->pickLabel = (string) $pickLabel;
        $this->pickLabelFixed = true;
        return $this;
    }

    public function getPickLabel()
    {
        return $this->pickLabel;
    }
    
    /**
     * @return $this
     */
    public function setContainer(Html $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Div which contains the input element=
     * @return \Osf\View\Helper\Html
     */
    public function getContainer()
    {
        return $this->container;
    }
    
    /**
     * Url or the action which receive the file for an upload with progress
     * @param string $uploadUrl calculated if not specified
     * @return $this
     */
    public function setUploadUrl($uploadUrl)
    {
        $this->getElement()->setAutoUploadUrl($uploadUrl);
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadUrl()
    {
        
        $uploadUrl = $this->getElement() ? $this->getElement()->getAutoUploadUrl() : null;
        if (!$uploadUrl) {
            $h = Container::getViewHelper();
            if ($h) {
                $uploadUrl = $h->url('event', 'upload');
                $this->setUploadUrl($uploadUrl);
            }
        }
        return $uploadUrl;
    }
    
    /**
     * Url of image to display instead of input button
     * @return $this
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = (string) $imageUrl;
        return $this;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }
    
//    /**
//     * @return $this
//     */
//    public function setSendScript($sendScript = true)
//    {
//        $this->sendScript = (bool) $sendScript;
//        return $this;
//    }
//
//    /**
//     * @return bool
//     */
//    public function getSendScript()
//    {
//        return (bool) $this->sendScript;
//    }
    
    public function render()
    {
        if ($this->getElement()->getAccept() == ElementFile::ACCEPT_IMAGE) {
            $this->setAttribute('accept', 'image/*');
            if (!$this->pickIconFixed) {
                $this->setPickIcon('picture-o');
            }
            if (!$this->pickLabelFixed) {
                $this->setPickLabel(__("Choisir une image"));
            }
        }
        $ctn = $this->getContainer();
        $ctn->addCssClass('btn-' . $this->getStatus());
        $autoUpload = '';
        
        // Element input file
        $elt = $this->buildStandardElement($this->getElement(), ['type' => 'file']);
        
        // Auto upload : progress bar + javascript
        if ($this->getElement()->getAutoUpload()) {
            
            // Progress bar
            $ctn->setAttribute('id', $this->getAttributes()['id'] . 'c');
            $attrs = [
                'class' => 'progress-bar progress-bar-primary',
                'role' => 'progressbar',
                'aria-valuenow' => '0',
                'aria-valuemin' => '0',
                'aria-valuemax' => '100',
                'style' => 'min-width: 2em; width: 0%;'
            ];
            $progressId = $this->getAttributes()['id'] . 'p';
            $resultId = $this->getAttributes()['id'] . 'r';
            $autoUpload = '<div class="progress" id="' . $progressId . '" style="width: 100%; display: none">' 
                        . Htm::buildHtmlElement('div', $attrs, '0%') 
                        . '</div><div id="' . $resultId . '"></div>';
            
            // JS
            $tags = ['{{ID}}', '{{URL}}'];
            $values = [$this->getAttributes()['id'], $this->getUploadUrl()];
            $jsTemplate = file_get_contents(__DIR__ . '/FormFile.js'); // '/FormFile.min.js');
            $js = str_replace($tags, $values, $jsTemplate);
            $autoUpload .= '<script>' . $js . '</script>';
        }
        
        // Décoration de l'élément
        if ($this->getImageUrl()) {
            $ctn->html('<img src="' . $this->getImageUrl() . '" class="img-responsive" alt="Image" />');
            if (!Container::getDevice()->isMobileOrTablet()) {
                $ctn->setAttributes([
                    'data-toggle' => 'tooltip',
                    'title' => $this->getPickLabel(),
                    'data-placement' => 'top'
                ]);
            }
            $ctn->setAttribute('style', 'background: white');
        } else {
            $ctn->html('<i class="fa ' . $this->getPickIcon() . '"></i>&nbsp;', $this->getPickIcon());
            $ctn->html('<span class="hidden-xs">' . $this->getPickLabel() . '</span>', $this->getPickLabel());
        }
        $ctn->html($elt);
        
        return $this->getContainer() . $autoUpload;
    }
    
    public function registerAutouploadJs()
    {
        
    }
}