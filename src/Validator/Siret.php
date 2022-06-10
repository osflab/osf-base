<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * SIRET number
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class Siret extends AbstractValidator
{
    const BAD_SYNTAX = 'siretSyntax';
    const LUHN_ERROR = 'siretLuhnError';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_SYNTAX => "Un SIRET doit comporter 14 chiffres",
        self::LUHN_ERROR => "Ce SIRET n'est pas valide, vérifiez-le bien...",
    ];

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        
        if (!preg_match('/^[0-9]{14}$/', $value)) {
            $this->error(self::BAD_SYNTAX);
            return false;
        }
        
        $sum = '';
        for ($i = strlen($value) - 1; $i >= 0; --$i) {
            $sum .= $i & 1 ? $value[$i] : $value[$i] * 2;
        }
        if (array_sum(str_split($sum)) % 10 !== 0) {
            $this->error(self::LUHN_ERROR);
            return false;
        }
        
        return true;
    }
}
