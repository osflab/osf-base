<?php
namespace Osf\View\Component;

/**
 * Element with picker (color, time, date, etc.)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
interface PickerInterface
{
    /**
     * Attach a javascript launcher to the element id
     * @param string $elementId
     * @return $this
     */
    public function registerElementId(string $elementId);
}