<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\Stream\Text as T;
use Osf\Stream\Html;
use Osf\Container\OsfContainer as Container;

/**
 * Trait element for helpers with status feature
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Icon
{
    private   $iconIsNullable = false;
    private   $defaultIcon    = null;
    protected $icon           = null;
    protected $iconColor      = null;
    protected $iconAnimated   = false;
    
    /**
     * Valid bootstrap icon
     * @param string|null $icon
     * @return $this
     */
    public function icon($icon, $color = null, bool $animated = null):self
    {
        Checkers::checkIcon($icon, null, $this->iconIsNullable);
        $this->icon = $icon;
        $this->setIconColor($color);
        $this->iconAnimated = $animated !== null ? $animated : $this->iconAnimated;
        return $this;
    }
    
    /**
     * Color of attached icon
     * @param string $color
     * @return $this
     */
    public function setIconColor($color = null)
    {
        $color === null || Checkers::checkColor($color, null);
        $this->iconColor = $color;
        return $this;
    }
    
    /**
     * Icon is optional
     * @param bool $trueOrFalse
     * @return $this
     */
    protected function iconIsNullable(bool $trueOrFalse = true)
    {
        $this->iconIsNullable = $trueOrFalse;
        return $this;
    }
    
    /**
     * Default icon
     * @param type $icon
     * @return $this
     */
    protected function iconSetDefault($icon)
    {
        $this->defaultIcon = T::strOrNull($icon);
        return $this;
    }
    
    /**
     * @return string|null
     */
    protected function getIcon()
    {
        return $this->icon ?: $this->defaultIcon;
    }
    
    /**
     * Get html <i> icon content
     * @return string
     */
    protected function getIconHtml(bool $animated = null, array $cssClasses = [])
    {
        $icon = $this->getIcon();
        if (!$icon) {
            return '';
        }
        $animated = $animated ?? $this->iconAnimated;
        
        // Mise en cache s'il n'y a pas de css spécifique
        if (!$cssClasses) {
            $key = 'OSFICO:' . (int) $animated . $icon . ':' . $this->iconColor;
            $iconHtml = Container::getCache()->get($key);
            if (!$iconHtml) {
                $iconHtml = $this->buildIcon($icon, $animated, []);
                Container::getCache()->set($key, $iconHtml);
            }
            return $iconHtml;
        }
        
        // Sinon...
        return $this->buildIcon($icon, $animated, $cssClasses);
    }
    
    /**
     * Build the HTML code of the icon
     * @param string $icon
     * @param bool $animated
     * @param array $cssClasses
     * @return string
     */
    protected function buildIcon(string $icon, bool $animated, array $cssClasses): string
    {
        return Html::buildHtmlElement('i', [], null, true, $this->getIconCssClasses($icon, $animated, $cssClasses));
    }
    
    /**
     * Build css classes of i élément
     * @param string $icon
     * @param bool $animated
     * @param array $cssClasses
     * @return string
     */
    protected function getIconCssClasses(?string $icon = null, bool $animated = false, array $cssClasses = [])
    {
        $cssClasses[] = 'fa';
        $cssClasses[] = $icon ?? $this->getIcon();
        if ($animated === true) {
            if ($this->getIcon() === 'fa-spinner') {
                $cssClasses[] = 'fa-pulse';
            } else if (in_array($this->getIcon(), ['fa-spinner', 'fa-circle-o-notch', 'fa-refresh', 'fa-cog'])) {
                $cssClasses[] = 'fa-spin';
            }
        }
        if ($this->iconColor) {
            $cssClasses[] = 'text-' . $this->iconColor;
        }
        return $cssClasses;
    }
    
    /**
     * @return $this
     */
    public function setIconAnimated(bool $iconAnimated = true)
    {
        $this->iconAnimated = $iconAnimated;
        return $this;
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initIcon(array $vars)
    {
        $this->iconIsNullable(array_key_exists('iconIsNullable', $vars) 
                ? (bool) $vars['iconIsNullable'] 
                : false);
        $this->iconSetDefault(array_key_exists('defaultIcon', $vars) 
                ? $vars['defaultIcon'] 
                : null);
        if (isset($vars['icon']) && $vars['icon']) {
            $this->icon($vars['icon']);
        } else {
            $this->icon = null;
        }
        if (isset($vars['iconColor']) && $vars['iconColor']) {
            Checkers::checkColor($vars['iconColor']);
            $this->iconColor = $vars['iconColor'];
        } else {
            $this->iconColor = null;
        }
        $this->setIconAnimated(isset($vars['animated']) && $vars['animated']);
        return $this;
    }
}