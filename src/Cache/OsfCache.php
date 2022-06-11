<?php
namespace Osf\Cache;

use Laminas\Cache\Storage\Adapter\Redis as RedisAdapter;
use Laminas\Cache\Storage\Adapter\RedisOptions;
use Osf\Container\OsfContainer as Container;
use Osf\Cache\InvalidArgumentException;
use Osf\Cache\RedisResourceManager;
use Osf\Container\VendorContainer;
use Osf\Exception\ArchException;
use Psr\SimpleCache\CacheInterface;
use Redis;

/**
 * Simple and fast cache using Redis and PSR6 compatible
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 24 sept. 2013
 * @package osf
 * @subpackage cache
 */
class OsfCache implements CacheInterface
{
    const DEFAULT_NAMESPACE = 'osfcache';
    const NSKSEP = ':'; // Namespace / key separator
    const DEV_NOCACHE = false; // Cache disable in dev mode ?
    
    protected $lastKey = null;
    protected $redis = null;
    protected $namespace;
    
    public function __construct(string $namespace = self::DEFAULT_NAMESPACE, \Redis $redis = null)
    {
        if (!preg_match('/^[a-zA-Z_]{1,16}$/', $namespace)) {
            throw new ArchException('Invalid namespace syntax');
        }
        $this->namespace = $namespace;
        $this->redis = $redis;
    }
    
    // ------------------------------------------------------------------------
    // PSR6 METHODS
    // ------------------------------------------------------------------------
    
    /**
     * PSR6: Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     * @param string $key
     * @param mixed $value
     * @param null|int|DateInterval $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null): bool
    {
        if ($this->noCache()) {
            return false;
        }
        $this->checkKey($key);
        $this->filterTtl($ttl);
        $result = $ttl === null ?
            $this->getRedis()->set($this->filterKey($key), $value) : 
            $this->getRedis()->set($this->filterKey($key), $value, (int) $ttl);
        if (!$result) {
            trigger_error('Unable to set in redis cache: ' . $this->getRedis()->getLastError());
            return false;
        }
        return true;
    }
    
    /**
     * PSR6: Fetches a value from the cache.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->checkKey($key);
        if ($this->noCache()) {
            return $default;
        }
        $value = $this->getRedis()->get($this->filterKey($key));
        return $value === false ? $default : $value;
    }
    
    /**
     * PSR6: Delete an item from the cache by its unique key.
     * @param string $key
     * @return bool
     */
    public function delete($key): bool
    {
        $this->checkKey($key);
        return (bool) $this->clean($key);
    }
    
    /**
     * PSR6: Wipes clean the entire cache's keys.
     * @return bool
     */
    public function clear(): bool
    {
        return (bool) $this->cleanAll();
    }
    
    /**
     * PSR6: Obtains multiple cache items by their unique keys.
     * @param iterable $keys
     * @param mixed $default
     * @return iterable
     */
    public function getMultiple($keys, $default = null): iterable
    {
        $this->checkIterable($keys);
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }
        return $values;
    }
    
    /**
     * PSR6: Persists a set of key => value pairs in the cache, with an optional TTL.
     * @param iterable $values
     * @param null|int|DateInterval $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $this->checkIterable($values);
        $this->filterTtl($ttl);
        $return = true;
        foreach ($values as $key => $value) {
            $return = $return && $this->set($key, $value, $ttl);
        }
        return $return;
    }
    
    /**
     * PSR6: Deletes multiple cache items in a single operation.
     * @param iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        $this->checkIterable($keys);
        $return = true;
        foreach ($keys as $key) {
            $return = $return && $this->delete($key);
        }
        return $return;
    }
    
    /**
     * PSR6: Determines whether an item is present in the cache.
     * @param string $key The cache item key.
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function has($key): bool
    {
        $this->checkKey($key);
        return (bool) $this->getRedis()->keys($this->filterKey($key));
    }
    
    // ------------------------------------------------------------------------
    // ADDITIONAL METHODS
    // ------------------------------------------------------------------------
    
    /**
     * Start cache via buffer
     * @param string $key
     * @return $this
     */
    public function start(string $key)
    {
        $filteredKey = $this->filterKey($key);
        $value = $this->get($filteredKey);
        if ($value === null) {
            $this->lastKey = $filteredKey;
            ob_start();
        } else {
            $this->lastKey = null;
            trigger_error('Cache start failed');
        }
        return $this;
    }
    
    /**
     * Stop buffer and put it in cache
     * @param float $timeout
     * @return $this
     */
    public function stop(float $timeout = 0.0): self
    {
        if ($this->lastKey !== null) {
            $this->set($this->lastKey, ob_get_clean(), $timeout);
        }
        $this->lastKey = null;
        return $this;
    }
    
    /**
     * Clean all values of the current namespace
     * @return int Number of deleted fields
     */
    public function cleanAll(): int
    {
        $cpt = 0;
        $keys = $this->getRedis()->keys($this->namespace . ':*');
        if ($keys) {
            foreach ($keys as $key) {
                $this->getRedis()->del($key);
                $cpt++;
            }
        }
        return $cpt;
    }
    
    /**
     * Call Redis::del()
     * @param string $key
     * @return int Number of deleted fields
     */
    public function clean(string $key): int
    {
        return $this->getRedis()->del($this->filterKey($key));
    }
    
    // ------------------------------------------------------------------------
    // UTILITIES
    // ------------------------------------------------------------------------
    
    /**
     * @return \Redis
     */
    public function getRedis(): Redis
    {
        if ($this->redis === null) {
            if (class_exists('\Osf\Container\VendorContainer')) {
                $this->redis = VendorContainer::getRedis();
            } else {
                $this->redis = new \Redis();
                $this->redis->pconnect('127.0.0.1', 6379);
                $this->redis->setOption(Redis::OPT_SERIALIZER, 
                        defined('Redis::SERIALIZER_IGBINARY')
                        ? Redis::SERIALIZER_IGBINARY
                        : Redis::SERIALIZER_PHP);
            }
        }
        return $this->redis;
    }

    /**
     * Build and return a zend cache storage using OSF cache configuration
     * @staticvar array $storages
     * @param string $namespace
     * @return RedisAdapter
     * @throws ArchException
     */
    public function getLaminasStorage(string $namespace = 'default'): RedisAdapter
    {
        static $storages = [];
        
        if (!isset($storages[$namespace])) {
            $rrm = new RedisResourceManager();
            $rrm->setResource('default', $this->getRedis());
            $rrm->setLibOption('default', Redis::OPT_SERIALIZER, $this->redis->getOption(Redis::OPT_SERIALIZER));
            $options = new RedisOptions();
            $options->setResourceManager($rrm);
            $storages[$namespace] = new RedisAdapter($options);
        }

        return $storages[$namespace];
    }
    
    /**
     * No cache detection
     * @return bool
     */
    protected function noCache()
    {
        return self::DEV_NOCACHE && Container::getApplication()->isDevelopment();
    }
    
    /**
     * Check key syntax
     * @param mixed $key
     * @return void
     * @throws InvalidArgumentException
     */
    protected function checkKey($key): void
    {
        if (!is_string($key) || !preg_match('/^[a-zA-Z0-9:+_-]{1,1000}$/', $key)) {
            throw new InvalidArgumentException('Invalid cache key [' . $key . ']');
        }
    }
    
    /**
     * Check if keys is iterable
     * @param mixed $keys
     * @return void
     * @throws InvalidArgumentException
     */
    protected function checkIterable($keys): void
    {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException('Cache keys are not iterable');
        }
    }
    
    /**
     * Mixed ttl to seconds
     * @param mixed $ttl
     * @return void
     */
    protected function filterTtl(&$ttl): void
    {
        if ($ttl !== null) {
            if ($ttl instanceof \DateInterval) {
                $ttl = (int) $ttl->format('%s');
            }
            $ttl = (int) $ttl;
        }
    }
    
    /**
     * Key with namespace
     * @param string $key
     * @return string
     */
    protected function filterKey(string $key): string
    {
        return $this->namespace . self::NSKSEP . $key;
    }
}
