<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;
use Osf\Validator\Validator as V;

/**
 * French IBAN (rib)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class IbanFr extends AbstractValidator
{
    const BAD_SYNTAX = 'ibanSyntax';
    const RIB_KEY    = 'ribKey';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_SYNTAX => "Ce ce n'est pas un IBAN français valide.",
        self::RIB_KEY    => "La vérification de votre clé RIB a échouée, vérifiez votre saisie.",
    ];

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $value = (string) $value;
        $regex = '/^FR[0-9]{2}([0-9]{5})([0-9]{5})([0-9A-Z]{11})([0-9]{2})$/';
        $matches = [];
        
        // Syntaxe
        if (!preg_match($regex, $value, $matches)) {
            $this->error(self::BAD_SYNTAX);
            return false;
        }
        
        // Clé rib
        [$iban, $bank, $agency, $account, $key] = $matches;
        $account = strtr(strtoupper($account), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '12345678912345678923456789');
        if (97 - ((89 * (int) $bank + 15 * (int) $agency + 3 * (int) $account) % 97) !== (int) $key) {
            $this->error(self::RIB_KEY);
            return false;
        }
        
        // Validation IBAN
        return V::getIban()->isValid($iban);
    }
}
