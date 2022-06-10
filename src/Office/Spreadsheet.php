<?php
namespace Osf\Office;

use PhpOffice\PhpSpreadsheet\Spreadsheet as PssSpreadsheet;
use PhpOffice\PhpSpreadsheet\Settings as PssSettings;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Cache\Adapter\Redis\RedisCachePool;
use Osf\Container\VendorContainer;

/**
 * phpspreadsheet proxy
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage office
 * @task [OFFICE] set redis cache optional?
 */
class Spreadsheet
{
    /**
     * Create a new spreadsheet
     * @return PssSpreadsheet
     */
    public static function newSpreadsheet(): PssSpreadsheet
    {
        self::init();
        return new PssSpreadsheet();
    }
    
    /**
     * Load a file
     * @param string $filename
     * @return PssSpreadsheet
     */
    public static function loadSpreadsheet(string $filename): PssSpreadsheet
    {
        self::init();
        return IOFactory::load($filename);
    }
    
    /**
     * Lazy initialization
     * @staticvar boolean $initialized
     */
    protected static function init()
    {
        static $initialized = false;
        
        if (!$initialized) {
            self::initCache();
        }
    }
    
    /**
     * Redis cache for php spreadsheet
     */
    protected static function initCache()
    {
        $client = VendorContainer::getRedis();
        $pool = new RedisCachePool($client);
        $simpleCache = new SimpleCacheBridge($pool);
        PssSettings::setCache($simpleCache);
    }
}
