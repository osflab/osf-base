<?php
namespace Osf\Stream;

use Osf\Exception\ArchException;

/**
 * Yaml parser proxy
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage yaml
 */
class Yaml
{
    const ENGINE_NATIVE  = 'php';
    const ENGINE_SYMFONY = 'symfony';
    const ENGINE_NONE    = null;
    
    /**
     * @param string $yamlStream
     * @return mixed
     */
    public static function parse(string $yamlStream) 
    {
        switch (self::getEngine()) {
            case self::ENGINE_NATIVE : 
                return yaml_parse($yamlStream);
            case self::ENGINE_SYMFONY : 
                return \Symfony\Component\Yaml\Yaml::parse($yamlStream);
            default : 
                self::noEngine();
        }
    }
    
    /**
     * @param string $yamlFile
     * @return mixed
     */
    public static function parseFile(string $yamlFile)
    {
        switch (self::getEngine()) {
            case self::ENGINE_NATIVE : 
                return yaml_parse_file($yamlFile);
            case self::ENGINE_SYMFONY : 
                return self::parse(file_get_contents($yamlFile));
            default : 
                self::noEngine();
        }
    }
    
    /**
     * Auto set the library to use (php extension first, symfony otherwise)
     * @staticvar string $engine
     * @return string|null
     */
    protected static function getEngine(): ?string
    {
        static $engine = null;
        
        if ($engine === null) {
            if (extension_loaded('yaml')) {
                $engine = self::ENGINE_NATIVE;
            } else  {
                $engine = class_exists('\Symfony\Component\Yaml\Yaml')
                        ? self::ENGINE_SYMFONY
                        : self::ENGINE_NONE;
            }
        }
        
        return $engine;
    }
    
    /**
     * No engine exception
     * @throws ArchException
     */
    protected static function noEngine()
    {
        throw new ArchException('No engine found to process yaml');
    }
}
