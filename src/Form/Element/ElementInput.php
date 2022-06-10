<?php
namespace Osf\Form\Element;

use Osf\View\Helper\Bootstrap\Tools\Checkers;
use Osf\View\Component\Inputmask;
use Osf\Exception\ArchException;
use Osf\Validator\Validator as V;
use Osf\Filter\Filter as F;
use Osf\Container\OsfContainer as Container;

/**
 * Input element
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 6 déc. 2013
 * @package osf
 * @subpackage form
 */
class ElementInput extends ElementAbstract
{
    use Addon\LeftRight;
    
    const PICKER_DATE  = 'Datepicker';
    const PICKER_TIME  = 'Timepicker';
    const PICKER_COLOR = 'Colorpicker';
    
    const TYPE_TEXT           = 'text';
    const TYPE_PASSWORD       = 'password';
    const TYPE_DATETIME       = 'datetime';
    const TYPE_DATETIME_LOCAL = 'datetime-local';
    const TYPE_DATE           = 'date';
    const TYPE_MONTH          = 'month';
    const TYPE_TIME           = 'time';
    const TYPE_WEEK           = 'week';
    const TYPE_NUMBER         = 'number'; // nombre, par défaut int
    const TYPE_PRICE          = 'price';  // nombre avec un step de .01 et options spécifiques aux tarifs
    const TYPE_FLOAT          = 'float';  // tout type de nombre à virgule
    const TYPE_EMAIL          = 'email';
    const TYPE_URL            = 'url';
    const TYPE_SEARCH         = 'search';
    const TYPE_TEL            = 'tel';
    const TYPE_COLOR          = 'color';
    
    const PICKERS = [
        self::PICKER_DATE,
        self::PICKER_TIME,
        self::PICKER_COLOR
    ];
    
    const TYPES = [
        self::TYPE_TEXT,
        self::TYPE_PASSWORD,
        self::TYPE_DATETIME,
        self::TYPE_DATETIME_LOCAL,
        self::TYPE_DATE,
        self::TYPE_MONTH,
        self::TYPE_TIME,
        self::TYPE_WEEK,
        self::TYPE_NUMBER,
        self::TYPE_PRICE,
        self::TYPE_FLOAT,
        self::TYPE_EMAIL,
        self::TYPE_URL,
        self::TYPE_SEARCH,
        self::TYPE_TEL,
        self::TYPE_COLOR
    ];
    
    protected $dataMask;
    protected $dataMaskOpt;
    protected $picker;
    protected $type = 'text';
    
    /**
     * @param string $mask
     * @return $this
     */
    public function setDataMask($mask, $options = null)
    {
        if ($mask !== null) {
            $this->dataMask = (string) $mask;
            $this->dataMaskOpt = $options === null ? null : (array) $options;
        }
        return $this;
    }
    
    /**
     * Input mask
     * @return string
     */
    public function getDataMask()
    {
        return $this->dataMask;
    }
    
    /**
     * Input mask
     * @return string
     */
    public function getDataMaskOpt()
    {
        return $this->dataMaskOpt;
    }
    
    /**
     * Attache a time, date, color picker to the element
     * @param type $picker
     * @return $this
     * @throws ArchException
     */
    public function setPicker($picker)
    {
        if (!in_array($picker, self::PICKERS)) {
            throw new ArchException('Bad picker [' . trim($picker) . ']');
        }
        if ($this->picker) {
            Checkers::notice('This element already have a picker [' . $this->picker . ']. Old one will be replaced.');
        }
        $this->picker = $picker;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function setPickerDate()
    {
        return $this->setPicker(self::PICKER_DATE);
    }
    
    /**
     * @return $this
     */
    public function setPickerTime()
    {
        return $this->setPicker(self::PICKER_TIME);
    }
    
    /**
     * @return $this
     */
    public function setPickerColor()
    {
        return $this->setPicker(self::PICKER_COLOR);
    }
    
    /**
     * Picker view component class
     * @return string
     */
    public function getPicker()
    {
        return $this->picker;
    }
    
    /**
     * Type attribute of input element
     * @param string $type
     * @return $this
     * @throws ArchException
     */
    public function setType(string $type)
    {
        if (!in_array($type, self::TYPES)) {
            throw new ArchException('Input element type [' . $type . '] not found.');
        }
        $this->type = $type;
        switch ($type) {
            case self::TYPE_TEXT : 
                break;
            case self::TYPE_PASSWORD :
                break;
            case self::TYPE_DATETIME :
                break;
            case self::TYPE_DATETIME_LOCAL :
                break;
            
            // @task [DATE] Pour pouvoir gérer le champ input/date, il faut utiliser la norme RFC 3339/ISO 8601 "wire format" YYYY-MM-DD
            case self::TYPE_DATE :
                //if (!Container::getDevice()->isMobileOrTablet()) {
                $this->setPickerDate();
                $this->setType(self::TYPE_TEXT);
                //}
                break;
            case self::TYPE_MONTH :
                break;
            case self::TYPE_TIME :
//                if (!Container::getDevice()->isMobileOrTablet()) {
//                    $this->setPickerTime();
//                }
                $this->setDataMask(Inputmask::MASK_TIME);
                break;
            case self::TYPE_EMAIL :
                $this->add(F::getStringTrim());
                $this->add(F::getStringToLower());
                $this->add(V::getEmailAddress());
                break;
            case self::TYPE_WEEK :
                break;
            case self::TYPE_FLOAT : 
            case self::TYPE_PRICE : 
            case self::TYPE_NUMBER :
                // Problème de conflit entre '.' (mysql) et ',' (php) :( :( :(
                //$this->add(V::getIsFloat()); 
                break;
            case self::TYPE_URL :
                $this->add(V::getUri());
                break;
            case self::TYPE_SEARCH :
                break;
            case self::TYPE_TEL :
                //$this->setDataMask(Inputmask::MASK_PHONE_FR);
                $this->add(F::getTelephone());
                $this->add(V::getTelephone());
                break;
            case self::TYPE_COLOR :
                if (!Container::getDevice()->isMobileOrTablet()) {
                    $this->setPickerColor();
                    $this->type = self::TYPE_TEXT;
                }
                break;
            default: 
                break;
        }
        return $this;
    }
    
    /**
     * Type attribute of input element
     * bool $htmlTypeAttribute get the value or HTML attribute type instead of the internal type
     * @return string
     */
    public function getType(bool $htmlTypeAttribute = true)
    {
        if (!$htmlTypeAttribute) {
            return $this->type;
        }
        switch ($this->type) {
            case self::TYPE_PRICE :
            case self::TYPE_FLOAT :
                return self::TYPE_NUMBER;
            default : 
                return $this->type;
        }
    }
    
    // Set types
    
    /**
     * @return $this
     */
    public function setTypeText()
    {
        return $this->setType(self::TYPE_TEXT);
    }

    /**
     * @return $this
     */
    public function setTypePassword()
    {
        return $this->setType(self::TYPE_PASSWORD);
    }

    /**
     * @return $this
     */
    public function setTypeDatetime()
    {
        return $this->setType(self::TYPE_DATETIME);
    }

    /**
     * @return $this
     */
    public function setTypeDatetimeLocal()
    {
        return $this->setType(self::TYPE_DATETIME_LOCAL);
    }

    /**
     * Type 'text' with a mask and a picker
     * @return $this
     */
    public function setTypeDate()
    {
        return $this->setType(self::TYPE_DATE);
    }

    /**
     * @return $this
     */
    public function setTypeMonth()
    {
        return $this->setType(self::TYPE_MONTH);
    }

    /**
     * @return $this
     */
    public function setTypeTime()
    {
        return $this->setType(self::TYPE_TIME);
    }

    /**
     * @return $this
     */
    public function setTypeWeek()
    {
        return $this->setType(self::TYPE_WEEK);
    }

    /**
     * @return $this
     */
    public function setTypeNumber()
    {
        return $this->setType(self::TYPE_NUMBER);
    }

    /**
     * @return $this
     */
    public function setTypePrice()
    {
        return $this->setType(self::TYPE_PRICE);
    }

    /**
     * @return $this
     */
    public function setTypeFloat()
    {
        return $this->setType(self::TYPE_FLOAT);
    }

    /**
     * @return $this
     */
    public function setTypeEmail()
    {
        return $this->setType(self::TYPE_EMAIL);
    }

    /**
     * @return $this
     */
    public function setTypeUrl()
    {
        return $this->setType(self::TYPE_URL);
    }

    /**
     * @return $this
     */
    public function setTypeSearch()
    {
        return $this->setType(self::TYPE_SEARCH);
    }

    /**
     * @return $this
     */
    public function setTypeTel()
    {
        return $this->setType(self::TYPE_TEL);
    }

    /**
     * @return $this
     */
    public function setTypeColor()
    {
        return $this->setType(self::TYPE_COLOR);
    }
}