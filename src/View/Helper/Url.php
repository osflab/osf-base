<?php
namespace Osf\View\Helper;

use Osf\Stream\Text as T;
use Osf\Container\OsfContainer as Container;

/**
 * Url helper
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 20 nov. 2013
 * @package osf
 * @subpackage view
 */
class Url extends AbstractViewHelper
{
    /**
     * Build and return an url from current router
     * @param string $controller
     * @param string $action
     * @param ?array $params
     * @param mixed $transferParamKeys clés des valeurs à transferer de la requête courante, ou true pour toutes les valeurs
     * @return string
     */
    public function __invoke($controller = null, $action = null, ?array $params = null, $transferParamKeys = null)
    {
        // Ajout des paramètres de la requête courante (clés spécifiés dans un tableau)
        if (is_array($transferParamKeys) && $transferParamKeys) {
            $requestParams = Container::getRequest()->getParams();
            foreach ($transferParamKeys as $key) {
                if (!array_key_exists($key, $requestParams) || array_key_exists($key, $params)) {
                    continue;
                }
                $params[$key] = $requestParams[$key];
            }
        } 
        
        // Ajout de tous les paramètres de la requête courante
        else if ($transferParamKeys === true) {
            $params = array_merge(Container::getRequest()->getParams(), $params);
        }
        
        // Construction de l'url
        return Container::getRouter()->buildUri($params, T::strOrNull($controller), T::strOrNull($action), false);
    }
}
