<?php
namespace Osf\Filter;

use Osf\Filter\AbstractFilter;

/**
 * Date filter for devices which use a specific input date
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage filter
 * @todo complete with some other date formats
 */
class DateWire extends AbstractFilter
{
    protected const DW_PATTERN = '/^(2[0-9]{3})-([0-9]{2})-([0-9]{2})$/';
    
    public function filter($value)
    {
        switch (true) {
            
            // Spécifications wire RFC3339/ISO8601 (Android, MySQL, etc.)
            case preg_match(self::DW_PATTERN, $value) : 
                return preg_replace(self::DW_PATTERN, '$3/$2/$1', $value);
            
            // D'autres formats ? 
            
            // Sinon on retourne la chaîne inconnue
            default : 
                return $value;
        }
    }
}
