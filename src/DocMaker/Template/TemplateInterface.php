<?php
namespace Osf\DocMaker\Template;

/**
 * DocMaker template interface
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 21 déc. 2013
 * @package osf
 * @subpackage docmaker
 */
interface TemplateInterface
{
    /**
     * @param string $itemType
     * @param string $content
     * @return \Osf\DocMaker\Template\TemplateInterface
     */
    public function prependItem($itemType, $content);

    /**
     * @param string $itemType
     * @param string $content
     * @return \Osf\DocMaker\Template\TemplateInterface
     */
    public function appendItem($itemType, $content);

    /**
     * @param array $content
     * @return string
     */
    public function render(array $content);
}