<?php
namespace Osf\Cache;

use Zend\Cache\Storage\Adapter\RedisResourceManager as ZRRM;
use Osf\Exception\ArchException;
use Redis;

/**
 * Zend RedisResourceManager overload, which not detectd the Redis connection
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage cache
 */
class RedisResourceManager extends ZRRM
{
    /**
     * Set a resource
     *
     * @param string $id
     * @param array|Traversable|RedisResource $resource
     * @return RedisResourceManager Fluent interface
     */
    public function setResource($id, $resource)
    {
        if (!($resource instanceof Redis)) {
            throw new ArchException('Not a valid redis resource');
        }
        $this->resources[(string) $id] = [
            'persistent_id' => '',
            'lib_options'   => [],
            'server'        => [],
            'password'      => '',
            'database'      => 0,
            'resource'      => $resource,
            'initialized'   => true,
            'version'       => 0,
        ];
        return $this;
    }
}
