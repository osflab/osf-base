<?php
namespace Osf\Helper;

use Osf\Stream\Text;
use DateTime;

/**
 * Mysql helpers
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage helpers
 */
class Mysql
{
    /**
     * Transform a string or a tab to an SQL concatenation
     * 
     * <strong>|{user.name}|</strong> => concat(concat("<strong>", user.name), "</strong>")
     * ['<strong>', '|{user.name}|', </strong>'] => concat(concat("<strong>", user.name), "</strong>")
     * 
     * @param array|string $items
     * @return type
     */
    public static function concat($items)
    {
        // Parsing
        if (is_string($items)) {
            $lines = explode("\n", $items);
            $str = '';
            foreach ($lines as $line) {
                $str .= trim($line);
            }
            $items = explode('|', $str);
        }
        
        // Building
        $txt = '';
        foreach ($items as $value) {
            if (!preg_match('/^\{[^}]+\}$/', $value)) {
                $value = '"' . str_replace('"', '\"', $value) . '"';
            } else {
                $value = substr($value, 1, -1);
            }
            $txt = $txt === '' ? $value : 'CONCAT(' . $txt . ', ' . $value . ')';
        }
        return $txt;
    }
    
    public static function toDecimal($value)
    {
        return trim((string) (float) str_replace(',', '.', $value), '.');
    }
    
    public static function formatDateTime($dateTime, bool $short = false)
    {
        return Text::formatDateTime(self::mysqlToDateTime($dateTime), null, null, $short);
    }
    
    public static function formatDate($dateTime, bool $short = false)
    {
        return Text::formatDate(self::mysqlToDateTime($dateTime), null, $short);
    }
    
    /**
     * @param mixed $date
     * @return string
     */
    public static function dateToMysql($date): string
    {
        $date = $date instanceof DateTime ? $date : DateTime::createFromFormat('d/m/Y', $date);
        return $date->format('Y-m-d');
    }
    
    /**
     * @param string $mysqlDate
     * @return DateTime
     */
    public static function mysqlToDateTime(string $mysqlDate): DateTime
    {
        return new DateTime($mysqlDate);
    }
    
    /**
     * @param string $str
     * @param bool $left
     * @param bool $right
     * @return string
     */
    public static function like(string $str, bool $left = true, bool $right = true): string
    {
        return ($left ? '%' : '') . 
                str_replace('%', '\%', $str) . 
               ($right ? '%' : '');
    }
}