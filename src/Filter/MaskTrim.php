<?php
namespace Osf\Filter;

use Osf\Filter\AbstractFilter;

/**
 * Filter values from data masks
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage filter
 */
class MaskTrim extends AbstractFilter
{
    public function filter($value)
    {
        return trim($value, "_\t\n\r\0\x0B");
    }
}
