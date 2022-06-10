<?php
namespace Osf\Pdf\Document\Bean\Addon;

/**
 * Bean description
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
trait Description
{
    protected $description;
    
    /**
     * @param $description string|null
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description === null ? null : (string) $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
}
