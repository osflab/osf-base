<?php
namespace Osf\Application;

/**
 * General configuration management
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 sept. 2013
 * @package osf
 * @subpackage application
 */
class Config
{
    protected $config = [];
    
    /**
     * @param array $config
     * @param boolean $merge
     * @return $this
     */
    public function appendConfig(array $config, $merge = true)
    {
        $this->config = $merge ? array_merge($this->config, $config): $config;
        return $this;
    }
    
    /**
     * Quick access to a configuration node
     * @param string|null $key
     * @param string|null $subKey
     * @return mixed
     */
    public function getConfig(?string $key = null, ?string $subKey = null)
    {
        if ($key === null) {
            return $this->config;
        }
        $key = (string) $key;
        $subKey = $subKey === null ? null : (string) $subKey;
        if (array_key_exists($key, $this->config)) {
            if ($subKey !== null) {
                return array_key_exists($subKey, $this->config[$key]) 
                    ? $this->config[$key][$subKey] 
                    : null;
            }
            return $this->config[$key];
        }
        return null;
    }
    
    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getConfig();
    }
}
