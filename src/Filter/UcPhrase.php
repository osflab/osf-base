<?php
namespace Osf\Filter;

use Osf\Filter\AbstractFilter;
use Osf\Stream\Text;

/**
 * UcFirst for each words in a phrase
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage filter
 */
class UcPhrase extends AbstractFilter
{
    public function filter($value)
    {
        return Text::ucPhrase($value);
    }
}
