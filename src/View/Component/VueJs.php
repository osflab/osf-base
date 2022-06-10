<?php
namespace Osf\View\Component;

use Osf\View\Component;

/**
 * VueJs component
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class VueJs extends AbstractComponent
{
    const EVENT_CLIC   = 'click';
    const EVENT_SELECT = 'select';
    const EVENT_CHANGE = 'change';
    const EVENT_FOCUS  = 'focus';
    const EVENT_READY  = 'ready';
    
    const EVENTS_JQUERY = [
        self::EVENT_CLIC,
        self::EVENT_SELECT,
        self::EVENT_CHANGE,
        self::EVENT_FOCUS,
        self::EVENT_READY
    ];
    
    const EVENTS_HTML = [
        self::EVENT_CLIC   => 'onclick',
        self::EVENT_SELECT => 'onselect',
        self::EVENT_CHANGE => 'onchange',
        self::EVENT_FOCUS  => 'onfocus'
    ];
    
    const URIPARAM_SELECT = [
        '[key]' => 'find(\'option:selected\').val()',
        '[value]' => 'find(\'option:selected\').text()'
    ];
    
    public function __construct()
    {
        // Include standalone builds of Vue.js
        if (Component::registerComponentScripts()) {
            if (Application::isProduction()) {
                $this->registerFootJs('https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js');
            } else {
                $this->registerFootJs('https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.js');
            }
        }
        // $this->registerScript($this->getAjaxScripts());
    }
    
    /**
     * Scripts to load ajax links + forms
     * @return string
     */
    public function getAjaxScripts():string
    {
        return '$(\'div#content a[href^="/"],div#content a[href=""],div.navbar-custom-menu a[href^="/"]\').not(".extlink").off(\'click\').click('
             . 'function(e){e.preventDefault();$.router.push($(this).attr(\'href\'))});'
             . '$(\'form\').off(\'submit\').on(\'submit\',function(e){e.preventDefault();$(this).find(\'input[type="submit"]\').prop(\'disabled\', true);$(this).find(\'i.form-wait\').removeClass(\'hidden\');$.ajaxCall($(this),$(this).attr(\'target\')?$(this).attr(\'target\'):$(this),$(this).attr(\'target\')?0:1)});';
    }
    
    /**
     * Redirection sans inscription dans l'historique
     * @param type $url
     */
    public function redirect($url)
    {
        if (preg_match(STATIC_URL_REGEX, $url)) {
            return $this->registerScript('window.location.replace("' . $url . '");');
        }
        return $this->registerScript('$.router.replace("' . $url . '");');
    }
    
    /**
     * Ajax call direct access (bypass the router)
     * @param string $url
     * @param string $target
     * @param bool $replaceTag
     * @param string|bool $waitTarget true = sablier affiché au bout de 500ms si connexion lente sur $target
     * @return $this
     */
    public function ajaxCall($url, $target = null, bool $replaceTag = false, $waitTarget = false)
    {
        $js  = '$.ajaxCall("' . $url . '"';
        $js .= $target !== null ? ',"' . $target . '"' : ($replaceTag || $waitTarget ? ',false' : '');
        $js .= $replaceTag ? ',1' : ($waitTarget ? ',false' : '');
        $js .= $waitTarget ? ',' . ($waitTarget === true ? 'true' : '"' . $waitTarget . '"') : '';
        $js .= ');';
        return $this->registerScript($js);
    }
    
    public function popup($url)
    {
        return $this->registerScript('var win = window.open("' . $url . '","_blank");if(win){win.focus();}else{alert("' . __("Pour ouvrir ce document, veuillez autoriser les popups.") . '");}');
    }
    
    /**
     * Register an ajax link
     * @param string $elmId
     * @param string $uriPattern
     * @param array $uriParams
     * @param string $event
     * @param string $targetId
     * @return $this
     */
    public function registerLink(
            string $elmId, 
            string $uriPattern, 
            array  $uriParams, 
            string $event    = self::EVENT_CLIC, 
            string $targetId = null)
    {
        $selector = '$("#' . $elmId . '").';
        $replaces = [];
        foreach ($uriParams as $key => $value) {
            $replaces[] = "replace('" . $key . "', " . $selector . $value . ')';
        }
        $target = $targetId ? ", '" . $targetId . "'" : '';
        $callOptions = "'" . $uriPattern . "'." . implode('.', $replaces) . $target;
        $script  = $selector . $event;
        $script .= '(function(e){e.preventDefault();$.ajaxCall(' . $callOptions . ')});';
        return $this->registerScript($script);
    }
    
    /**
     * Register an event to submit a form 
     * default is the form before the element which launch the event
     * @param string $elmId (without #)
     * @param string $formId (without #)
     * @param string $event
     * @param string $targetId
     * @return $this
     */
    public function registerSubmitLink(string $elmId, string $formId = '', string $event = self::EVENT_CLIC, string $targetId = '', bool $preventDefault = true)
    {
        $linkSelector = '$("#' . $elmId . '").';
        $script  = $linkSelector . $event . '(function(e){';
        $script .= $preventDefault ? 'e.preventDefault();' : '';
        $target  = $targetId ? ", '" . $targetId . "'" : '';
        $formSelector = $formId
                      ? '$("#' . $formId . '")' 
                      : $linkSelector . 'prev("form")';
        //$script .= 'console.log(' . $formSelector . ');';
        $script .= '$.ajaxCall(' . $formSelector . $target . ')});';
        return $this->registerScript($script);
    }
    
    /**
     * Simple ajax call
     * @param string|null $url
     * @param string|null $target
     * @param bool $history increment history if no target specified
     * @return string
     */
    public static function buildJsLink($url, $target = null, bool $history = true, bool $replaceTag = false)
    {
        $targetArg  = $target ? ($target[0] === '$' ? "," . $target : ",'#" . $target . "'") : '';
        $targetArg .= $replaceTag ? ',1' : '';
        if (!$history || $targetArg) {
            return "$.ajaxCall('" . $url . "'" . $targetArg . ");";
        } else {
            return "$.router.push('" . $url . "');";
        }
    }
}
