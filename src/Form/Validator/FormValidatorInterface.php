<?php
namespace Osf\Form\Validator;

use Osf\Form\AbstractForm;

/**
 * Additional validation class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage form
 */
interface FormValidatorInterface
{
    /**
     * @return bool
     */
    public function isValid(AbstractForm $form): bool;
}
