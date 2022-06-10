<?php
namespace Osf\View\Helper;

/**
 * Head links
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates 2013
 * @version 1.0
 * @since OSF-2.0 - 7 déc. 2013
 * @package osf
 * @subpackage view
 */
class HeadTags extends AbstractViewHelper
{
    use Tags\Title;
    use Tags\Meta;
    use Tags\Link;
    use Tags\Script;
    
    /**
     * @return string
     */
    public function __invoke()
    {
        $output  = $this->buildTitle();
        $output .= $this->buildMeta();
        $output .= $this->buildCssLinks();
        $output .= $this->buildFiles();
        $output .= $this->buildScripts();
        return trim($output) . "\n";
    }
}
