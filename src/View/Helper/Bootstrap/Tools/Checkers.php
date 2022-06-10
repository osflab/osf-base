<?php

namespace Osf\View\Helper\Bootstrap\Tools;

use Osf\Exception\Error;
use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;

/**
 * Checkers for bootstrap helpers
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Checkers extends Error
{
    public static function checkIcon(&$icon, string $default = null, bool $canBeNull = false)
    {
        if (!$icon) {
            if ($canBeNull) {
                return true;
            } else {
                self::notice('Icon can not be empty');
            }
        }
        if (!preg_match('/^fa-.*$/', $icon)) {
            $icon = 'fa-' . $icon;
        }
        if (!preg_match('/^fa-[a-z0-9-]+$/', $icon)) {
            self::notice('Bad icon value [' . $icon . ']. Need to be a awesome font color class fa-xxx');
            $icon = $default;
            return false;
        }
        return true;
    }
    
    public static function checkColor(&$color, $default = null)
    {
        if (!in_array($color, AVH::COLOR_LIST)) {
            self::notice('Bad color [' . $color . '], not a known color.');
            $color = $default;
            return false;
        }
        return true;
    }
    
    public static function checkUrl(&$url, $default = null)
    {
        if (!($url === null || preg_match('/[a-zA-Z0-9\/_#:\.{}-]+$/', $url))) {
            self::notice('Bad url value [' . $url . ']. Need to be an url string.');
            $url = $default;
            return false;
        }
        return true;
    }
    
    public static function checkPercentage(&$percentage, $default = null)
    {
        if (!(is_int($percentage) || $percentage > 0 || $percentage < 100)) {
            self::notice('Bad percentage value [' . $percentage . ']. Need to be an int between 0 and 100');
            $percentage = $default;
            return false;
        }
        return true;
    }
    
    public static function checkStatus(&$status, $default = AVH::STATUS_DEFAULT, $excludePrimaryAndDefault = false)
    {
        if (!in_array($status, AVH::STATUS_LIST, true)) {
            self::notice('Bad status type [' . $status . ']. Need to be a valid bootstrap status.');
            $status = $default;
            return false;
        }
        if ($excludePrimaryAndDefault && ($status == AVH::STATUS_PRIMARY || $status == AVH::STATUS_DEFAULT)) {
            self::notice('Status type [' . $status . ']  is not allowed.');
            $status = $default;
            return false;
        }
        return true;
    }
    
    /**
     * Placement : top, bottom, left, right
     * @param string|null $placement
     * @param string|null $default
     * @return boolean
     */
    public static function checkPlacement(&$placement, $default = null)
    {
        if (!in_array($placement, ['top', 'bottom', 'left', 'right'])) {
            self::notice('Bad placement [' . $placement . ']');
            $placement = $default;
            return false;
        }
        return true;
    }
}