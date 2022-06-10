<?php
namespace Osf\View\Helper\Bootstrap\Addon;

/**
 * Trait element for helpers with status feature
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Modal
{
    protected $modalId;
    
    /**
     * Lauch a declared modal
     * @param string $modalId
     * @return $this
     */
    public function launchModal($modalId)
    {
        $this->modalId = (string) $modalId;
        return $this;
    }
    
    /**
     * Attributes to register
     * @return array
     */
    protected function getModalAttributes():array
    {
        if ($this->modalId) {
            return [
                'data-toggle' => 'modal',
                'data-target' => '#' . $this->modalId
            ];
        }
        return [];
    }
    
    /**
     * @param array $vars
     * @return $this
     */
    protected function initModal(array $vars)
    {
        if (isset($vars['modalId'])) {
            $this->launchModal($vars['modalId']);
        } else {
            $this->modalId = null;
        }
        return $this;
    }
}
