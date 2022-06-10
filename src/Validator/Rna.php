<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * RNA registration number of associations
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class Rna extends AbstractValidator
{
    const BAD_SYNTAX = 'rnaSyntax';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_SYNTAX => "Un numéro RNA doit comporter 9 chiffres"
    ];

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        
        if (!preg_match('/^W[0-9]{9}$/', $value)) {
            $this->error(self::BAD_SYNTAX);
            return false;
        }
        
        return true;
    }
}
