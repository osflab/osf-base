<?php
namespace Osf\DocMaker;

use Osf\DocMaker\Item;
use Osf\DocMaker\Template\Html;
use Osf\DocMaker\Template\TemplateInterface;

/**
 * Class used to generate html documentation with a text.
 *
 * @author Guillaume Ponçon <guillaume.poncon@wanadoo.fr>
 * @version 2.0
 * @since 2.0 Thu Sep 21 23:20:54 CEST 2006
 * @copyright 2006 Guillaume Ponçon - all rights reserved
 * @package osf
 * @subpackage docmaker
 */
class DocMaker {

    protected $contentTxt = null;
    protected $content = [];
    protected $current_dir = null;
    protected $template = null;

    /**
     * @param string $content
     */
    public function __construct($content = null)
    {
        if ($content !== null) {
            $this->setContent($content);
        }
    }
    
    /**
     * Set content to parse
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->contentTxt = (string) $content;
        return $this;
    }
    
    protected static function text($txt) {
        return trim($txt);
    }

    protected static function stripPhpTags($code) {
        $code = nl2br($code);
        $code = str_replace(chr(13), '', $code);
        $code = str_replace(chr(10), '', $code);
        $code = preg_replace('/^.*?(<span[^>]+>[a-z])/', '$1', $code);
        $code = preg_replace('/^.*?(<span[^>]+>)&lt;\?php<br \/>([a-z])/', '$1$2', $code);
        $code = preg_replace('/(<br \/>|&nbsp;)*&lt;\?php(<br \/>|&nbsp;)*/', '', $code);
        $code = preg_replace('/<span[^>]+>(<br \/>)*\?&gt;(<br \/>)*<\/span>/', '', $code);
        $code = preg_replace('/<\/span><br \/><\/code>/', '</span></code>', $code);
        $code = preg_replace('/<br \/><\/span><\/code>/', '</span></code>', $code);
        return $code;
    }

    protected function addItem($type, $content) {
        $item = new Item();
        $item->setType($type);
        $item->setContent($content);
        $this->content[] = $item;
    }

    /**
     * Parse the document
     * @param array $doc
     */
    protected function parse(array $doc) {
        $this->content = [];
        $context = 0; // Context : 1 -> paragraphe, 2 -> code
        $contextContainer = '';
        $doc[] = '';
        foreach ($doc as $key => $line) {

            // Titre du document
            if ($key == 0) {
                $this->addItem('doctitle', self::text($line));
                continue;
            }

            switch ($context) {

                // Si nous ne sommes pas dans un contexte (code, paragraphe, etc.)
                case 0 :

                    // Ligne vide
                    if (!trim($line)) {
                        break;
                    }

                    // Commentaire
                    if (substr($line, 0, 1) == ';') {
                        break;
                    }

                    // Petit titre
                    if (substr($line, 0, 2) == '!!') {
                        $this->addItem('subtitle', self::text(substr($line, 2)));
                        break;
                    }

                    // Grand titre
                    if (substr($line, 0, 1) == '!') {
                        $this->addItem('title', self::text(substr($line, 1)));
                        break;
                    }

                    // Puce
                    if (substr($line, 0, 2) == '- ') {
                        $this->addItem('li', self::text(substr($line, 2)));
                        break;
                    }

                    // Petit puce
                    if (substr($line, 0, 3) == ' - ') {
                        $this->addItem('subli', self::text(substr($line, 3)));
                        break;
                    }

                    // Inclusion d'un fichier PHP
                    if (substr($line, 0, 5) == '#php ') {
                        $file = self::$current_dir.'/'.trim(substr($line, 5));
                        if (file_exists($file)) {
                            $code = highlight_file($file, true);
                            $this->addItem('php', self::stripPhpTags($code));
                        }
                        break;
                    }

                    // Début de contexte code
                    if (substr(trim($line), 0, 5) == '<?php') {
                        $context = 2;
                    }

                    // Début de contexte remarque
                    elseif (substr(trim($line), 0, 6) == '<para>') {
                        $line = substr(trim($line), 6);
                        $context = 3;
                    }

                    // Si paragraphe, passe au case 1.
                    else {
                        $context = 1;
                    }

                    // Si nous sommes dans un contexte paragraphe
                case 1 :

                    if ($context == 1) {
                        if (!trim($line)) {
                            $context = 0;
                            $this->addItem('paragraph', self::text($contextContainer));
                            $contextContainer = '';
                        } else {
                            $contextContainer .= $line;
                        }
                        break;
                    }

                    // Contexte code
                case 2 :

                    $contextContainer .= $line . "\n";
                    $len = strlen(trim($line));
                    if (substr(trim($line), $len - 2, $len) == '?>') {
                        $code = highlight_string($contextContainer, true);
                        $this->addItem('php', self::stripPhpTags($code));
                        $contextContainer = '';
                        $context = 0;
                    }
                    break;

                    // Contexte remarque
                case 3 :

                    $len = strlen(trim($line));
                    if (substr(trim($line), $len - 7, $len) == '</para>') {
                        $contextContainer .= substr($line, 0, $len - 7);
                        $this->addItem('para', $contextContainer);
                        $contextContainer = '';
                        $context = 0;
                    } else {
                        $contextContainer .= trim($line) ? $line : '<br /><br />';
                    }
                    break;

            }

        }
    }
    
    /**
     * @return \Osf\DocMaker\Template\TemplateInterface
     */
    public function getTemplate()
    {
        if ($this->template === null) {
            $this->template = new Html();
        }
        return $this->template;
    }

    /**
     * @param TemplateInterface $template
     * @return $this
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->template = $template;
        return $this;
    }

	/**
     * Renderer (default = html)
     * @return string
     */
    public function render()
    {
        // Parsing du contenu
        $tabContent = explode("\n", $this->contentTxt);
        self::parse($tabContent);
        
        // Rendu
        return $this->getTemplate()->render($this->content);
    }
}
