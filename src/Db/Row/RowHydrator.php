<?php
namespace Osf\Db\Row;

use Laminas\Hydrator\HydratorInterface;
use Osf\Exception\ArchException;

/**
 * Hydrator for generated data models
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 9 nov. 2013
 * @package osf
 * @subpackage db
 */
class RowHydrator implements HydratorInterface
{
    protected function checkObject($object)
    {
        if (!is_object($object) || !($object instanceof RowGatewayInterface)) {
            throw new ArchException('Not a row object');
        }
    }
    
    public function extract($object)
    {
        $this->checkObject($object);
        return $object->toArray();
    }
    
    public function hydrate(array $data, $object)
    {
        $this->checkObject($object);
        $object->populate($data, true);
        return $object;
    }
}