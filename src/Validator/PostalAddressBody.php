<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * Address body validator
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class PostalAddressBody extends AbstractValidator
{
    const MAX_LINES = 3;
    const MAX_LINE_LEN = 40;
    
    const TOO_LINES = 'addressTooMuchLines';
    const LINE_LONG = 'addressLineTooLong';

    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::TOO_LINES => "Votre adresse ne doit pas dépasser %lines% lignes afin d'être exploitable.",
        self::LINE_LONG => "Les lignes de votre adresse ne doivent pas dépasser %line_len% caractères.",
    ];
    
    /**
     * @var array
     */
    protected $messageVariables = [
        'lines'    => 'lines',
        'line_len' => 'lineLen'
    ];
    
    public function __construct($options = null) {
        parent::__construct($options);
        $this->lines = isset($options['lines']) ? (int) $options['lines'] : self::MAX_LINES;
        $this->lineLen = isset($options['line_len']) ? (int) $options['line_len'] : self::MAX_LINE_LEN;
    }

    /**
     * Returns true if and only if $value only contains currency value
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $lines = explode("\n", $value);
        
        // Trop de lignes
        if (count($lines) > $this->lines) {
            $this->error(self::TOO_LINES);
        }
        
        // Ligne trop longue
        foreach ($lines as $line) {
            if (strlen($line) > $this->lineLen) {
                $this->error(self::LINE_LONG);
                break;
            }
        }
        
        if ($this->getMessages()) {
            return false;
        }

        return true;
    }
}
