<?php
namespace Osf\Form\Element;

use Osf\Exception\ArchException;

/**
 * Elements with collection of options
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 déc. 2013
 * @package osf
 * @subpackage form
 */
abstract class ElementListAbstract extends ElementAbstract
{
    protected $options = [];
    
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * @param string $key
     */
    public function getOptionValue($key)
    {
        return array_key_exists($key, $this->options) ? $this->options[$key] : null;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        if ($options !== null) {
            if (!is_array($options)) {
                throw new ArchException('Options must be an array');
            }
            $this->options = $options;
        }
        return $this;
    }
    
    /**
     * Add and option
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }
}
