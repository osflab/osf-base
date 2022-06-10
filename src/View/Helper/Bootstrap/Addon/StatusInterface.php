<?php
namespace Osf\View\Helper\Bootstrap\Addon;

/**
 * Bootstrap status constants
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
interface StatusInterface extends IconInterface, ColorInterface
{
    const STATUS_DEFAULT = 'default';
    const STATUS_PRIMARY = 'primary';
    const STATUS_SUCCESS = 'success';
    const STATUS_INFO    = 'info';
    const STATUS_WARNING = 'warning';
    const STATUS_DANGER  = 'danger';
    
    const STATUS_ERROR   = self::STATUS_DANGER;  // ALIAS
    const STATUS_ONLINE  = self::STATUS_SUCCESS; // ALIAS
    const STATUS_OFFLINE = self::STATUS_DANGER;  // ALIAS
    
    const STATUS_LIST = [
        self::STATUS_DEFAULT,
        self::STATUS_PRIMARY,
        self::STATUS_SUCCESS,
        self::STATUS_INFO,
        self::STATUS_WARNING,
        self::STATUS_DANGER
    ];
    
    // Liste utile pour générer les couleurs de badges, etc. 
    const STATUS_COLOR_LIST = [
        self::STATUS_DEFAULT => self::COLOR_GRAY,
        self::STATUS_PRIMARY => self::COLOR_BLUE,
        self::STATUS_INFO    => self::COLOR_AQUA,
        self::STATUS_SUCCESS => self::COLOR_GREEN,
        self::STATUS_WARNING => self::COLOR_YELLOW,
        self::STATUS_ERROR   => self::COLOR_RED
    ];
    
    // Correspondances avec des icones
    const STATUS_ICONS = [
        self::STATUS_PRIMARY => self::ICON_CIRCLE,
        self::STATUS_DEFAULT => self::ICON_REMARK,
        self::STATUS_INFO    => self::ICON_INFO,
        self::STATUS_SUCCESS => self::ICON_SUCCESS,
        self::STATUS_WARNING => self::ICON_WARNING,
        self::STATUS_DANGER  => self::ICON_ERROR
    ];

}
