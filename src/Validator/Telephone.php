<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * Telephone syntax checker
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class Telephone extends AbstractValidator
{
    const MAX_LINES = 3;
    const MAX_LINE_LEN = 40;
    
    const BAD_SYNTAX = 'telephoneSyntax';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_SYNTAX => "Numéro incorrect.",
    ];

    /**
     * Returns true if and only if $value only contains currency value
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!preg_match('/^\+?[0-9]{2,12}$/', $value)) {
            $this->error(self::BAD_SYNTAX);
            return false;
        }
        
        return true;
    }
}
