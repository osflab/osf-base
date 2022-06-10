<?php
namespace Osf\View\Helper\Bootstrap\Addon;

/**
 * Bootstrap color constants
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
interface ColorInterface
{
    const COLOR_GREEN = 'green';
    const COLOR_AQUA = 'aqua';
    const COLOR_BLUE = 'blue';
    const COLOR_BLUE_LIGHT = 'light-blue';
    const COLOR_YELLOW = 'yellow';
    const COLOR_YELLOW_LIGHT = 'light-yellow';
    const COLOR_RED = 'red';
    const COLOR_BLACK = 'black';
    const COLOR_NAVY = 'navy';
    const COLOR_TEAL = 'teal';
    const COLOR_OLIVE = 'olive';
    const COLOR_LIME = 'lime';
    const COLOR_ORANGE = 'orange';
    const COLOR_FUCHSIA = 'fuchsia';
    const COLOR_PURPLE = 'purple';
    const COLOR_MAROON = 'maroon';
    const COLOR_GRAY = 'gray';
    
    const COLOR_LIST = [
        self::COLOR_GREEN,
        self::COLOR_AQUA,
        self::COLOR_BLUE,
        self::COLOR_BLUE_LIGHT,
        self::COLOR_YELLOW,
        self::COLOR_YELLOW_LIGHT,
        self::COLOR_RED,
        self::COLOR_BLACK,
        self::COLOR_NAVY,
        self::COLOR_TEAL,
        self::COLOR_OLIVE,
        self::COLOR_LIME,
        self::COLOR_ORANGE,
        self::COLOR_FUCHSIA,
        self::COLOR_PURPLE,
        self::COLOR_MAROON,
        self::COLOR_GRAY
    ];
}