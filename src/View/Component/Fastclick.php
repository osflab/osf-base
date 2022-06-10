<?php
namespace Osf\View\Component;

use Osf\View\Component;

/**
 * Fastclick
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 * @link https://github.com/ftlabs/fastclick
 */
class Fastclick extends AbstractComponent
{
    public function __construct()
    {
        if (Component::registerComponentScripts()) {
            $this->registerFootJs('/plugins/fastclick/fastclick.min.js', true);
        }
        $this->registerScript('$(function() {FastClick.attach(document.body)});');
    }
}