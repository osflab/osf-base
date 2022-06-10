<?php
namespace Osf\View\Component;

use Osf\View\Component;

/**
 * Bootstrap component
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Bootstrap extends AbstractComponent
{
    // TODO : déclarer les fichiers dans l'applicatif plutot que les librairies
    public function __construct()
    {
        Component::getJquery();
        if (Component::registerComponentScripts()) {
            $this->registerHeadCss('/bootstrap/css/bootstrap.min.css', true);
            $this->registerHeadCss('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            //$this->registerHeadCss('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css');
            $this->registerFootJs('/bootstrap/js/bootstrap.min.js', true);
            $this->registerFootJs('/dist/js/app.min.js', true);
        }
    }
}
