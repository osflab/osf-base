<?php
namespace Osf\Generator\Laminas;

use Laminas\Code\Generator\ParameterGenerator as ZPG;

/**
 * Zend ParameterGenerator with simple types generation
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage generator
 */
class ParameterGenerator extends ZPG
{
    protected static $simple = ['resource', 'mixed', 'object'];
}
