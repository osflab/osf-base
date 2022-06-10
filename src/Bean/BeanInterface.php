<?php
namespace Osf\Bean;

/**
 * Main bean interface
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage bean
 */
interface BeanInterface
{
    public function populate(array $data);
}
