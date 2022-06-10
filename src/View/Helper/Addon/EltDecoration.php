<?php
namespace Osf\View\Helper\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Stream\Text;

/**
 * Html element decoration
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait EltDecoration
{
    static $eltIdCount = 0;
    protected $attributes = [];
    protected $cssClasses = [];
    protected $styles = [];
    
    /**
     * Attributes. If value is null, the ="xx" part is not generated
     * Attributes class is append. Other attributes are replaced or errors
     * @param string $name
     * @param type $value
     * @param bool $replaceIfExists
     * @param bool $condition perform operation if condition is true
     * @return $this
     */
    public function setAttribute(
            string $name, 
            $value = '', 
            bool $replaceIfExists = false, 
            $condition = true)
    {
        $name = trim(Text::toLower($name));
        if ($condition) {
            if (!preg_match('/^[a-z0-9_-]+$/', $name)) {
                Checkers::notice('Attribute name [' . $name . '] syntax error.');
            } else if ($name === 'class') {
                $this->addCssClass($value);
            } else if ($name === 'style') {
                $this->appendStyle($value);
            } else if (!$replaceIfExists && array_key_exists($name, $this->attributes)) {
                Checkers::notice('Attribute [' . $name . '] already exists.');
            } else {
                $value = $value !== null ? (string) str_replace('"', '\"', (string) $value) : null;
                $this->attributes[$name] = $value;
            }
        }
        return $this;
    }
    
    /**
     * @param array $attrs
     * @return $this
     */
    public function setAttributes(array $attrs, bool $replaceIfExists = false)
    {
        foreach ($attrs as $key => $value) {
            $this->setAttribute($key, $value, $replaceIfExists);
        }
        return $this;
    }
    
    /**
     * @param string $cssClass
     * @param bool $condition Perform operation if condition == true
     * @return $this
     */
    public function addCssClass(?string $cssClass, $condition = true)
    {
        if ($condition && $cssClass) {
            $cssClass = trim($cssClass);
            if (!preg_match('/^[a-z0-9 _-]+$/', $cssClass)) {
                Checkers::notice('Css class name [' . $cssClass . '] syntax error.');
            } else if (array_key_exists($cssClass, $this->cssClasses)) {
                Checkers::notice('Css class [' . $cssClass . '] already exists.');
            } else {
                $this->cssClasses[$cssClass] = null;
            }
        }
        return $this;
    }
    
    /**
     * Supprime un style css
     * @param type $cssClass
     * @return $this
     */
    public function removeCssClass($cssClass)
    {
        if (array_key_exists($cssClass, $this->cssClasses)) {
            unset($this->cssClasses[$cssClass]);
        }
        return $this;
    }
    
    /**
     * @param array $cssClasses
     * @return $this
     */
    public function addCssClasses(array $cssClasses) 
    {
        foreach ($cssClasses as $class) {
            $this->addCssClass((string) $class);
        }
        return $this;
    }
    
    /**
     * @param string $cssClass
     * @return bool
     */
    public function hasCssClass($cssClass)
    {
        return array_key_exists($cssClass, $this->cssClasses);
    }
    

    /**
     * @return $this
     */
    public function marginBottom()
    {
        return $this->addCssClass('margin-bottom');
    }
    
    /**
     * @return $this
     */
    public function marginTop()
    {
        return $this->addCssClass('margin-top');
    }
    
    /**
     * @return $this
     */
    public function marginLeft()
    {
        return $this->addCssClass('margin-left');
    }
    
    /**
     * @return $this
     */
    public function marginRight()
    {
        return $this->addCssClass('margin-right');
    }
    
    /**
     * @return $this
     */
    public function hidden()
    {
        return $this->addCssClass('hidden');
    }
    
    /**
     * Add css style to the element
     * @param string $cssStyle
     * @return $this
     */
    public function appendStyle($cssStyle)
    {
        $cssStyle = trim($cssStyle, " \n;");
        $cssStyle && $this->styles[] = $cssStyle;
        return $this;
    }
    
    /**
     * Css margin:
     * @param string $margin
     * @return $this
     */
    public function margin($margin)
    {
        is_int($margin) && $margin && $margin .= 'px';
        return $this->appendStyle('margin: ' . $margin);
    }
    
    /**
     * Css padding:
     * @param string $padding
     * @return $this
     */
    public function padding($padding)
    {
        is_int($padding) && $padding && $padding .= 'px';
        return $this->appendStyle('padding: ' . $padding);
    }
    
    /**
     * Attributes with values
     * @return array
     */
    public function getAttributes():array
    {
        $attributes = $this->attributes;
        if ($this->cssClasses) {
            $attributes['class'] = implode(' ', array_keys($this->cssClasses));
        }
        if ($this->styles) {
            $attributes['style'] = implode(';', $this->styles);
        }
        return $attributes;
    }
    
    /**
     * @param string $key
     * @return string|null
     */
    public function getAttribute(string $key)
    {
        return $this->hasAttribute($key) ? $this->attributes[$key] : null;
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function hasAttribute(string $key):bool
    {
        return array_key_exists($key, $this->attributes);
    }
    
    /**
     * @return string
     */
    public function getEltDecorationStr()
    {
        $output = '';
        foreach ($this->getAttributes() as $key => $value) {
            $output .= ' ' . $key . ($value === null ? '' : '="' . $value . '"');
        }
        return $output;
    }
    
    /**
     * Reset decorations values
     * @return $this
     */
    protected function resetDecorations()
    {
        $this->attributes = [];
        $this->cssClasses = [];
        $this->styles = [];
        return $this;
    }
    
    /**
     * Build an id attribute
     * @param string $eltId
     * @param bool $errorIfExists
     * @return $this
     * @throws \Osf\Exception\ArchException
     */
    protected function buildIdAttr($eltId = null, bool $errorIfExists = false)
    {
        if ($errorIfExists && isset($this->attributes['id'])) {
            throw new \Osf\Exception\ArchException('Id element already set');
        }
        if ($eltId === null) {
            $eltId = 'e' . self::$eltIdCount++;
        }
        $this->setAttribute('id', $eltId);
        return $this;
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initEltDecoration(array $vars)
    {
        $this->resetDecorations();
        array_key_exists('attributes', $vars) && $this->setAttributes($vars['attributes']);
        array_key_exists('cssClasses', $vars) && $this->addCssClasses($vars['cssClasses']);
        return $this;
    }
}