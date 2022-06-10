<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * BIC (rib)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class Bic extends AbstractValidator
{
    const BAD_SYNTAX = 'bicSyntax';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_SYNTAX => "Votre BIC doit comporter 8 ou 11 lettres alpha-numériques.",
    ];

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        
        // Syntaxe
        if (!preg_match('/^[A-Z0-9]{8}([A-Z0-9]{3})?$/', $value)) {
            $this->error(self::BAD_SYNTAX);
            return false;
        }
        
        return true;
    }
}
