<?php
namespace Osf\Cache;

use Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;
use Osf\Exception\ArchException;

/**
 * PSR6 compatible invalid argument exception
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-3.0.0 - 2018
 * @package osf
 * @subpackage cache
 */
class InvalidArgumentException 
    extends ArchException 
    implements PsrInvalidArgumentException
{
}