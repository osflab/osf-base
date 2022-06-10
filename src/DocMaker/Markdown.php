<?php
namespace Osf\DocMaker;

use Parsedown;
use Osf\Stream\Text as T;

/**
 * Markdown from Parsedown
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage docmaker
 */
class Markdown extends Parsedown
{
    /**
     * Read a markdown file and build content array
     * @param string $file
     * @param string $separator
     * @return string
     */
    public function file(string $file, string $separator = '# ', $allowEmptyTitle = false)
    {
        return $this->buildMetaContent(file($file), $separator, $allowEmptyTitle);
    }
    
    /**
     * Build md content array from text feed
     * @param string $txt
     * @param string $separator
     * @return string
     */
    public function txt(string $txt, string $separator = '# ')
    {
        $lines = explode("\n", $txt);
        return $this->buildMetaContent($lines, $separator);
    }
    
    /**
     * Direct call of Parsedown::text() with pre-filtering
     * @param string $text
     * @param $filterContent trim + substitution des constantes
     * @return string
     */
    public function text($text, bool $filterContent = true) {
        if (!$text) {
            return (string) $text;
        }
        $content = $filterContent ? $this->filterContent((string) $text) : (string) $text;
        $text = preg_replace("/ ([:?!])/", '&nbsp;$1', $content);
        return parent::text($text);
    }
    
    /**
     * Markdown translate to HTML, line by line
     * @param array $fileLines
     * @param string $separator
     * @return array
     */
    protected function buildMetaContent($fileLines, $separator, $allowEmptyTitle = false)
    {
        $this->DefinitionData = [];
        $separatorLen = strlen($separator);
        
        // file to html directly
        if (!$separatorLen) {
            $tab['title'] = $this->filterContent(array_shift($fileLines));
            $tab['content'] = $this->filterContent($this->lines($fileLines), "\n");
            return $tab;
        }
        
        // Prepare array of html for Accordion
        $tab = [];
        $item = [];
        $lines = [];
        foreach ($fileLines as $key => $line) {
            if ($key === 0) { 
                $tab['title'] = $this->filterContent($line);
                continue;
            }
            if (strpos($line, $separator) === 0) {
                if ($item || $allowEmptyTitle) {
                    $item['body'] = $this->filterContent($this->lines($lines));
                    $tab['items'][] = $item;
                    $item  = [];
                    $lines = [];
                }
                $item['title'] = $this->filterContent(substr($line, $separatorLen));
                continue;
            }
            $lines[] = rtrim($line, "\n");
        }
        if ($item || $allowEmptyTitle) {
            $item['body'] = $this->filterContent($this->lines($lines));
            $tab['items'][] = $item;
        }
        return $tab;
    }
    
    /**
     * Markdown light transformation : only bold and italic
     * @param string $text
     * @return string
     */
    public function textLight($text)
    {
        return nl2br(preg_replace([
            '/\*\*(.+?)\*\*/',
            '/__(.+?)__/',
            '/_(.+?)_/',
        ],[
            '<strong>$1</strong>',
            '<strong>$1</strong>',
            '<i>$1</i>',
        ], stripslashes($this->filterContent((string) $text))));
    }
    
    /**
     * Pre-filters and replaces
     * @return string
     */
    protected function filterContent(string $content)
    {
        return trim(T::substituteConstants($content));
    }
    
    /**
     * Link build overload in order to add the blank target
     * @param unknown $Excerpt
     * @return array
     */
    protected function inlineLink($Excerpt)
    {
        $value = parent::inlineLink($Excerpt);
        if (isset($value['element']['attributes']['href']) && 
            substr($value['element']['attributes']['href'], -6, 6) === '|blank') {
            $value['element']['attributes']['href'] = substr($value['element']['attributes']['href'], 0, -6);
            $value['element']['attributes']['target'] = '_blank';
        }
        return $value;
    }
}
