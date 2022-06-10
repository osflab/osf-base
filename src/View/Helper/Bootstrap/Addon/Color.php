<?php
namespace Osf\View\Helper\Bootstrap\Addon;

use Osf\View\Helper\Bootstrap\Tools\Checkers;

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
trait Color
{
    protected $color;
    
    /**
     * Valid bootstrap color
     * @param string $color
     * @return $this
     */
    public function color($color)
    {
        if ($color !== null) {
            Checkers::checkColor($color, null);
        }
        $this->color = $color;
        return $this;
    }
    
    protected function initColor(array $vars)
    {
        $this->color(isset($vars['color']) ? $vars['color'] : null);
    }
}
