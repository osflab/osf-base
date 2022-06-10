<?php
namespace Osf\View\Helper;

use Osf\View\Helper\Tags\Script;

/**
 * Head links
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2017
 * @version 1.0
 * @since OSF-2.0 - 5 jan 2017
 * @package osf
 * @subpackage view
 */
class FootTags extends AbstractViewHelper
{
    use Script;
    
    public function __invoke()
    {
        $output  = $this->buildFiles();
        $output .= $this->buildScripts();
        return $output;
    }
}