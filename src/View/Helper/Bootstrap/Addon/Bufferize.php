<?php
namespace Osf\View\Helper\Bootstrap\Addon;

/**
 * Trait element for bufferized features 
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
trait Bufferize {
    
    protected $bufferized = false;

    /**
     * Start the output buffering
     * @return $this
     */
    public function start()
    {
        // On active la bufferisation dans l'objet cloné 
        // pour lui donner l'ordre de bufferiser, puis on 
        // désactive la bufferisation dans l'objet d'origine.
        // Ce mécanisme est nécessaire pour qu'on puisse 
        // cloner de l'extérieur sans activer la bufferisation.
        $this->bufferized = true;
        $newHelper = clone $this;
        $this->bufferized = false;
        return $newHelper;
    }
    
    /**
     * End the output buffering and display
     */
    protected function end()
    {
        $this->content = ob_get_clean();
        $this->bufferized = false;
        return $this;
    }
    
    public function __clone()
    {
        if ($this->bufferized) {
            ob_start();
        }
    }
    
    public function __toString() {
        if ($this->bufferized) {
            $this->end();
        }
        return $this->render();
    }
}