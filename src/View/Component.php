<?php
namespace Osf\View;

use Osf\Container\OsfContainer as Container;
use Osf\Exception\ArchException;
use Osf\View\HelperInterface;

/**
 * Javascript and CSS components manager
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Component
{
    protected static $components = [];
    protected static $registerComponentScripts = false;
    
    /**
     * Manage components instances
     * @param string $className
     * @return type
     * @throws \Osf\Exception\ArchException
     * @return \Osf\View\Component\AbstractComponent
     */
    protected static function buildComponent(string $className)
    {
        if (!isset(self::$components[$className])) {
            self::$components[$className] = Container::buildObject("\\Osf\\View\\Component\\" . $className);
            if (!(self::$components[$className] instanceof Component\AbstractComponent)) {
                throw new ArchException('Component [' . $className . '] must be an AbstractComponent');
            }
        }
        return self::$components[$className];
    }
    
    /**
     * @return \Osf\View\Component\Bootstrap
     */
    public static function getBootstrap()
    {
        return self::buildComponent('Bootstrap');
    }
    
    /**
     * @return \Osf\View\Component\Jquery
     */
    public static function getJquery()
    {
        return self::buildComponent('Jquery');
    }
    
    /**
     * @return \Osf\View\Component\Selectize
     */
    public static function getSelectize()
    {
        return self::buildComponent('Selectize');
    }
    
    /**
     * @return \Osf\View\Component\Autosize
     */
    public static function getAutosize()
    {
        return self::buildComponent('Autosize');
    }
    
    /**
     * @return \Osf\View\Component\Inputmask
     */
    public static function getInputmask()
    {
        return self::buildComponent('Inputmask');
    }
    
    /**
     * @return \Osf\View\Component\Datepicker
     */
    public static function getDatepicker()
    {
        return self::buildComponent('Datepicker');
    }
    
    /**
     * @return \Osf\View\Component\Timepicker
     */
    public static function getTimepicker()
    {
        return self::buildComponent('Timepicker');
    }
    
    /**
     * @return \Osf\View\Component\Colorpicker
     */
    public static function getColorpicker()
    {
        return self::buildComponent('Colorpicker');
    }
    
    /**
     * @return \Osf\View\Component\Fastclick
     */
    public static function getFastclick()
    {
        return self::buildComponent('Fastclick');
    }
    
    /**
     * @return \Osf\View\Component\VueJs
     */
    public static function getVueJs()
    {
        return self::buildComponent('VueJs');
    }
    
    /**
     * Attache les fichiers et styles des composants au layout
     * @param \Osf\View\Helper $layout
     */
    public static function registerScripts(HelperInterface $layout)
    {
        foreach (self::$components as $component) {
            foreach ($component->getScripts() as $script) {
                switch ($script[0]) {
                    case 'headjs' : 
                        $layout->headTags->appendFile($script[1], $script[2], $script[3]);
                        break;
                    case 'headcss' : 
                        $layout->headTags->appendStylesheet($script[1], $script[2], $script[3]);
                        break;
                    case 'footjs' : 
                        $layout->footTags->appendFile($script[1], $script[2], $script[3]);
                        break;
                    case 'footcss' : 
                        $layout->footTags->appendStylesheet($script[1], $script[2], $script[3]);
                        break;
                    case 'script' : 
                        $layout->footTags->appendScript($script[1]);
                        break;
                }
            }
        }
    }
    
    /**
     * Components register scripts ?
     * @param bool $register
     */
    public static function setRegisterComponentScript(bool $register)
    {
        self::$registerComponentScripts = (bool) $register;
    }
    
    /**
     * For components: generate <script> balise or not
     * @return bool
     */
    public static function registerComponentScripts()
    {
        return self::$registerComponentScripts;
    }
}
