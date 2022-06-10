<?php
namespace Osf\Filter;

use Osf\Filter\AbstractFilter;

/**
 * Currency filter
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage filter
 */
class Currency extends AbstractFilter
{
    public function filter($value)
    {
        return str_replace(',', '.', trim($value));
    }
}
