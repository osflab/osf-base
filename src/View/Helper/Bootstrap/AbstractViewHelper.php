<?php

namespace Osf\View\Helper\Bootstrap;

use Osf\Container\OsfContainer as Container;
use Osf\View\Helper\AbstractViewHelper as ParentAbstractViewHelper;
use Osf\View\Helper\Bootstrap\Addon\IconInterface;
use Osf\View\Helper\Bootstrap\Addon\ColorInterface;
use Osf\View\Helper\Bootstrap\Addon\StatusInterface;

/**
 * Bootstrap parent class view helper
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
abstract class AbstractViewHelper 
       extends ParentAbstractViewHelper
    implements IconInterface, ColorInterface, StatusInterface
{
    // Eventuellement déplacer dans une librairie si davantage utilisé
    public static function isCurrentUrl($url)
    {
        return $url == Container::getRequest()->getUri(true);
    }
    
    /**
     * Get a color related to a percentage
     * @param int $percentage
     * @param int $redLimit
     * @param int $orangeLimit
     * @return string
     */
    public static function getPercentageColor(int $percentage, int $redLimit = 30, int $orangeLimit = 70):string
    {
        return $percentage < $redLimit    ? self::COLOR_RED : (
               $percentage < $orangeLimit ? self::COLOR_ORANGE : 
                                            self::COLOR_GREEN);
    }
}
