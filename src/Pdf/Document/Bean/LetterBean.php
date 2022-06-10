<?php
namespace Osf\Pdf\Document\Bean;

use Osf\Pdf\Document\Config\LetterConfig;
use Osf\Pdf\Document\Config\BaseDocumentConfig;
use Osf\Exception\ArchException;
use Osf\Bean\BeanHelper as BH;

/**
 * Bean which contains all data of a letter
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 31 oct. 2013
 * @package osf
 * @subpackage pdf
 */
class LetterBean extends BaseDocumentBean implements LetterBeanInterface
{
    const TYPES = [
        self::TYPE_LETTER
    ];
    
    protected $dear; // Madame, Monsieur, 
    protected $body = '';
    protected $signature = '';
    protected $markdown = true;
    protected $attachLetter = false;
    
    /**
     * Letter configuration
     * @see \Osf\Pdf\Document\Bean\BaseDocumentBean::setConfig()
     * @return \Osf\Pdf\Document\Bean\LetterBean
     */
    public function setConfig(BaseDocumentConfig $config)
    {
        if (!($config instanceof LetterConfig)) {
            throw new ArchException('Letter bean must be configured by a LetterConfig class instance');
        }
        parent::setConfig($config);
        return $this;
    }
    
    /**
     * @see \Osf\Pdf\Document\Bean\BaseDocumentBean::getConfig()
     * @return \Osf\Pdf\Document\Config\LetterConfig
     */
    public function getConfig(): LetterConfig
    {
        if (!$this->config) {
            $this->setConfig(new LetterConfig());
        }
        return parent::getConfig();
    }
    
    /**
     * Provider address
     * @param Bean\ContactBean|array $contact
     * @return \Osf\Pdf\Document\Bean\LetterBean
     */
    public function setProvider(ContactBean $contact = null)
    {
        $this->provider = $contact;
        if ($contact !== null && !$this->signature) {
            $this->signature = $contact->getFirstname() . ' ' . $contact->getLastname();
            if ($contact->getFunction()) {
                $this->signature .= "\n" . $contact->getFunction();
            }
        }
        return $this;
    }
    
    /**
     * Recipient address
     * @param Bean\ContactBean|array $contact
     * @return $this
     */
    public function setRecipient(ContactBean $contact = null)
    {
        $this->recipient = $contact;
        return $this;
    }
    
    /**
     * Dear xxx,
     * @param string $dear
     * @return $this
     */
    public function setDear(?string $dear)
    {
        if ($dear !== null) {
            $this->dear = trim($dear);
        }
        return $this;
    }
    
    /**
     * Letter content (required)
     * @param string $body
     * @return $this
     */
    public function setBody(?string $body)
    {
        $this->body = (string) $body;
        return $this;
    }
    
    /**
     * Letter author (signatory)
     * @param string $sign
     * @return $this
     */
    public function setSignature(?string $sign)
    {
        $this->signature = $sign;
        return $this;
    }
    
    /**
     * 'Dear' [xxx,]
     * @param bool $computeIfEmpty
     * @return string|null
     */
    public function getDear(bool $computeIfEmpty = true): ?string
    {
        if (!$this->dear && $computeIfEmpty) {
            return $this->getRecipient()->getComputedCivilityWithLastname() . ',';
        }
        return $this->dear;
    }
    
    /**
     * Letter body
     * @return string
     */
    public function getBody(bool $compute = false): string
    {
        return BH::filterMarkdownContent($this->body, $compute, true);
    }
    
    /**
     * Letter signature (author)
     * @param bool $compute
     * @return string
     */
    public function getSignature(bool $compute = true): ?string
    {
        if ($this->signature || !$compute) {
            return $this->signature;
        }
        $signature = $this->getProvider()->getComputedFullname();
        if ($signature) {
            $function = $this->getProvider()->getFunction();
            if ($function) {
                $signature .= ",\n" . $function;
            }
            return $signature;
        }
        return null;
    }
    
    /**
     * @param bool $markdown
     * @return $this
     */
    public function setMarkdown($markdown = true)
    {
        $this->markdown = (bool) $markdown;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMarkdown(): bool
    {
        return $this->markdown;
    }
    
    /**
     * Attach the letter to the generated e-mail?
     * @param bool $attachLetter
     * @return $this
     */
    public function setAttachLetter($attachLetter = true)
    {
        $this->attachLetter = (bool) $attachLetter;
        return $this;
    }

    /**
     * Attach the letter to the generated e-mail?
     * @return bool
     */
    public function getAttachLetter(): bool
    {
        return $this->attachLetter;
    }
}
