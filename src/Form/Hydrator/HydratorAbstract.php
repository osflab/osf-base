<?php
namespace Osf\Form\Hydrator;

use Osf\Form\AbstractForm;

/**
 * Form hydrator
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package package
 * @subpackage subpackage
 */
abstract class HydratorAbstract
{
    abstract public function hydrate(array $values, AbstractForm $form, bool $prefixedValues = true, bool $noError = false, bool $fullValues = false);
}
