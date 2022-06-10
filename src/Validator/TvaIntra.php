<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * International intra-community VAT
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 * @todo to optimize
 */
class TvaIntra extends AbstractValidator
{
    const BAD_SYNTAX = 'syntax';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_SYNTAX => "Votre saisie ne ressemble pas à un numéro de TVA intracommunautaire.",
    ];

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        $regex = '/^[A-Z]{2}[0-9A-Z ]{8,28}$/';
        
        // Syntaxe
        if (!preg_match($regex, $value)) {
            $this->error(self::BAD_SYNTAX);
            return false;
        }
        
        // Validation IBAN
        return true;
    }
}
