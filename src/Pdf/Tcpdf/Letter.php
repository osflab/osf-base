<?php
namespace Osf\Pdf\Tcpdf;

use Osf\Container\OsfContainer as Container;
use Osf\Pdf\Tcpdf\Document;
use Osf\Exception\DisplayedException;
use Osf\Pdf\Document\Bean\LetterBean;
use Osf\Pdf\Document\Config\LetterConfig;
use Osf\Pdf\Document\Bean\BaseDocumentBean;

/**
 * Letter generator with tcpdf
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage pdf
 */
class Letter extends Document
{
    /**
     * @var \Osf\Pdf\Document\Bean\LetterBean
     */
    protected $bean;
    
    public function __construct(LetterBean $bean)
    {
        $this->bean = $bean;
        parent::__construct();
//        $this->setParams($bean->getParams());
    }
    
    /**
     * @return $this
     */
    public function setBean(BaseDocumentBean $bean)
    {
        throw new ArchException('Set the bean in constructor only please.');
    }

    /**
     * @return \Osf\Pdf\Document\Bean\LetterBean
     */
    public function getBean()
    {
        return parent::getBean();
    }
    
    /**
     * Main build class
     */
    protected function buildLetter()
    {
        // Configuration
        $b = $this->bean;
        $c = $b->getConfig();
        $this->buildConfiguration($c);
        
        // Meta, header, footer, address window
        $this->setSubject($b->getSubject());
        $this->setTitle($b->getTitle());
        $this->setHeadFootInfo($b->getProvider(), null, $b->getDate(), $b->getConfidential(), $b->getDisplayCreatedBy())
             ->addPage()
             ->addAddressWindow($b->getRecipient()->getAddress(), 11);

        // Body
        $this->buildBody($b, $c);
    }
    
    /**
     * Prepare document from configuration bean
     * @param LetterConfig $c
     */
    protected function buildConfiguration(LetterConfig $c)
    {
        if ($c->getFontFamily()) {
            $this->setDefaultFont($c->getFontFamily(), $c->getFontFamilyLight());
        }
        $this->setMargins($c->getMarginLeft(), $c->getMarginTop(), $c->getMarginRight(), true);
        $this->setHeaderMargin($c->getMarginTop());
    }
    
    /**
     * Build document body
     * @param LetterBean $b
     * @param LetterConfig $c
     * @throws \Osf\Exception\DisplayedException
     */
    protected function buildBody(LetterBean $b, LetterConfig $c)
    {
        $size = $c->getMaxFontSize();
        $bodyHtml = Container::getMarkdown()->text(htmlspecialchars($b->getBody()));
        $from = ['<p>'];
        $to   = ['<p style="text-align:justify">'];
        $bodyHtml = str_replace($from, $to, $bodyHtml);
        
        // Libelles
        $this->setFontSize(11);
        $this->writeLibs($b->getLibs());

        // Annulation des lettres trop longues via des transactions
        $this->startTransaction();
        $cpt = 0;
        while ($this->transactionInProgress()) {
            $this->setFont($this->getDefaultFontLight(), '', $size);

            // Body
            $this->setFont($this->getDefaultFontLight());
            if ($b->getDear()) {
                $this->setX($this->getMargins()['left']); //  + 30
                $this->cell(0, 20, $b->getDear(), 0, 1, 'L', false, '', 0, true, 'T', 'B');
            }
            $this->writeHTMLCell(0, 0, $this->getMargins()['left'], $this->getY() + 6, $bodyHtml, 0, 1, false);

            // Signature
            if ($b->getSignature()) {
                $this->multiCell(0, 0, $b->getSignature(), 0, 'L', false, 1, 90, $this->getY() + 25);
            }

            if ($this->getPage() > 1 || $this->getY() >= 250) {
                $this->rollbackTransaction();
                $size--;
            } else {
                $this->commitTransaction();
            }
            
            // Size too small
            if ($size < $c->getMinFontSize()) {
                throw new DisplayedException(__("Ce document contient trop de texte pour être affiché."));
            }
        }
    }

     /**
     * Send the document to a given destination: string, local file or browser.
     * In the last case, the plug-in may be used (if present) or a download ("Save
     * as" dialog box) may be forced.<br />
     * The method first calls Close() if necessary to terminate the document.
     * @param $name (string) The name of the file when saved. Note that special
     * characters are removed and blanks characters are replaced with the underscore
     * character.
     * @param $dest (string) Destination where to send the document. It can take
     * one of the following values:<ul><li>I: send the file inline to the browser
     * (default). The plug-in is used if available. The name given by name is used when
     * one selects the "Save as" option on the link generating the PDF.</li><li>D: send
     * to the browser and force a file download with the name given by name.</li><li>F:
     * save to a local server file with the name given by name.</li><li>S: return the
     * document as a string (name is ignored).</li><li>FI: equivalent to F + I
     * option</li><li>FD: equivalent to F + D option</li><li>E: return the document as
     * base64 mime multi-part email attachment (RFC 2045)</li></ul>
     * @return string
     * @public
     * @since 1.0
     * @see Close()
     */
    public function output($name = 'doc.pdf', $dest = 'I')
    {
        $this->buildLetter();
        return parent::output($name, $dest);
    }
    
    public function __toString()
    {
        return $this->output();
    }
}