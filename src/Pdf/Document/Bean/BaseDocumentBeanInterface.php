<?php
namespace Osf\Pdf\Document\Bean;

/**
 * Base document interface
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 nov. 2013
 * @package osf
 * @subpackage pdf
 */
interface BaseDocumentBeanInterface
{
    public function getTitle();
    public function getSubject();
    
    /**
     * @return \Osf\Pdf\Document\Config\BaseDocumentConfig
     */
    public function getConfig();
}