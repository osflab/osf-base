<?php
namespace Osf\Filter;

use Osf\Filter\AbstractFilter;

/**
 * Telephone simple filter. This is not a formatter.
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage filter
 */
class Telephone extends AbstractFilter
{
    public function filter($value)
    {
        return trim(str_replace(['.', ' '], ['', ''], $value));
    }
}
