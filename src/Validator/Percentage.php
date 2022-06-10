<?php
namespace Osf\Validator;

use Osf\Validator\AbstractValidator;

/**
 * Percentage validator
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage validator
 */
class Percentage extends AbstractValidator
{
    const MAX       = 'percentageMax';
    const MIN       = 'percentageMin';
    const PRECISION = 'percentagePrecision';
    const NO_COMMA  = 'percentageNoComma';
    const SYNTAX    = 'percentageSyntax';

    protected $options = [
        'max'       => 100,
        'min'       => 0,
        'precision' => 0,
    ];
    
    /**
     * Validation failure message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MAX        => "La valeur maximale est %max%",
        self::MIN        => "La valeur minimale est %min%",
        self::PRECISION  => "Ne dépassez pas %precision% chiffre(s) après la virgule",
        self::NO_COMMA   => "Ce pourcentage doit être un nombre entier",
        self::SYNTAX     => "Cette valeur n'est pas un pourcentage valide",
    ];

    /**
     * @var array
     */
    protected $messageVariables = [
        'max'       => ['options' => 'max'],
        'min'       => ['options' => 'min'],
        'precision' => ['options' => 'precision']
    ];
    
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $options['max'] = isset($options['max']) ? (float) $options['max'] : 100;
            $options['min'] = isset($options['min']) ? (float) $options['min'] : 0;
            $options['precision'] = isset($options['precision']) ? (int) $options['precision'] : 0;
        } else if (is_int($options)) {
            $options = ['precision' => $options];
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
        
        if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $value)) {
            $this->error(self::SYNTAX);
            return false;
        }
        
        if ($value < $this->options['min']) {
            $this->error(self::MIN);
            $ok = false;
        }
        
        if ($value > $this->options['max']) {
            $this->error(self::MAX);
            $ok = false;
        }
        
        $parts = explode('.', $value);
        if (isset($parts[1]) && strlen($parts[1]) > $this->options['precision']) {
            if ($this->options['precision']) {
                $this->error(self::PRECISION);
            } else {
                $this->error(self::NO_COMMA);
            }
            $ok = false;
        }

        return $ok;
    }
}