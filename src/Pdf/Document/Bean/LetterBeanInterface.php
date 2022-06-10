<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Pdf\Document\Config\LetterConfig;
use Osf\Pdf\Document\Bean\ContactBean;
use DateTime;

/**
 * Letter interface (usefull getters)
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 nov. 2013
 * @package osf
 * @subpackage pdf
 */
interface LetterBeanInterface extends BaseDocumentBeanInterface
{
    /**
     * Top of the page wording
     * @param string $key
     * @param bool $escape
     * @return string|null
     */
    public function getHeadlib(string $key, bool $escape = false): ?string;
    
    /**
     * Dear xxx,
     * @return string
     */
    public function getDear(): ?string;
    
    /**
     * Letter body
     * @param bool $compute
     * @return string
     */
    public function getBody(bool $compute = false): string;
    
    /**
     * Author
     * @return string
     */
    public function getSignature(): ?string;
    
    /**
     * Author
     * @return \Osf\Pdf\Document\Bean\ContactBean
     */
    public function getProvider(): ContactBean;
    
    /**
     * Recipient
     * @return \Osf\Pdf\Document\Bean\ContactBean
     */
    public function getRecipient(bool $computeTitle = true): ContactBean;
    
    /**
     * @see \Osf\Pdf\Document\Bean\BaseDocumentBean::getConfig()
     * @return \Osf\Pdf\Document\Config\LetterConfig
     */
    public function getConfig(): LetterConfig;
    
    /**
     * @return DateTime
     */
    public function getDate(): ?DateTime;
    
    /**
     * Wordings (object, etc.)
     * @param bool $escape
     * @return array
     */
    public function getLibs(bool $escape = false): array;
}