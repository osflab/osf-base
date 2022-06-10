<?php
namespace Osf\Validator;

use Zend\Validator\EmailAddress as ZEA;

/**
 * Email Address simplified validator
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class EmailAddress extends ZEA
{
    const EMAIL_NOT_VALID = 'emailAddressSimplifiedNotValid';
    
    public function __construct($options = array())
    {
        $this->messageTemplates[self::EMAIL_NOT_VALID] = __("Votre adresse e-mail semble incorrecte. Veuillez vérifier sa syntaxe.");
        parent::__construct($options);
    }
    
    public function isValid($value) {
        
        // Validation de l'email
        $value = parent::isValid($value);
        
        // Message simplifié
        if (!$value) {
            $this->abstractOptions['messages'] = [];
            $this->error(self::EMAIL_NOT_VALID);
        }
        
        return $value;
    }
}
