<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;
use Osf\Stream\Text;

/**
 * Password validator with openstates politics
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class Password extends AbstractValidator
{
    const MAX    = 'lenMax';
    const MIN    = 'lenMin';
    const SYNTAX = 'stxErr';

    protected $options = [
        'max'   => 72,
        'min'   => 6
    ];
    
    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MAX    => "La longueur de votre mot de passe ne peut excéder %max% caractères",
        self::MIN    => "Votre mot de passe doit contenir au moins %min% caractères",
        self::SYNTAX => "Pour plus de sécurité, mettez des chiffres et des lettres dans votre mot de passe",
    ];

    /**
     * @var array
     */
    protected $messageVariables = [
        'max' => ['options' => 'max'],
        'min' => ['options' => 'min']
    ];
    
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $options['max'] = isset($options['max']) ? (float) $options['max'] : 72;
            $options['min'] = isset($options['min']) ? (float) $options['min'] : 6;
        }
        parent::__construct($options);
    }
    
    /**
     * Returns true if and only if $value only contains currency value
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $ok = true;
        
        if (!preg_match('/^.*[0-9].*$/', $value) || 
            !preg_match('/^.*[a-zA-Z].*$/', $value)) {
            $this->error(self::SYNTAX);
            return false;
        }
        
        if (Text::strLen($value) < $this->options['min']) {
            $this->error(self::MIN);
            $ok = false;
        }
        
        if (Text::strLen($value) > $this->options['max']) {
            $this->error(self::MAX);
            $ok = false;
        }

        return $ok;
    }
}
