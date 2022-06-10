<?php
namespace Osf\View\Helper\Bootstrap;


use Osf\View\Helper\Bootstrap\AbstractViewHelper as AVH;
use Osf\View\Generated\StaticGeneratedViewHelper as H;
use Osf\Exception\ArchException;
use Osf\Container\OsfContainer as Container;
use Osf\View\Helper\Addon\Content;

/**
 * Markdown displayer
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage view
 */
class Markdown extends AVH
{
    use Content;
    use Addon\Bufferize;
    
    protected $mdFile;
    protected $separator;
    protected $openedItem = 0;
    
    /**
     * Display markdown document
     * @param string $separator Accordion séparator
     * @return \Osf\View\Helper\Bootstrap\Markdown
     */
    public function __invoke(string $separator = null)
    {
        $this->mdFile = null;
        $this->separator = $separator === null ? '# ' : $separator;
        $this->openedItem = 0;
        $this->initValues(get_defined_vars());
        return $this;
    }
    
    public function file(string $file)
    {
        $this->mdFile = $file;
        return $this;
    }
    
    /**
     * Accordion openedItem
     * -1 = all closed, 0...n : N° of opened element
     * @param int $id
     * @return $this
     */
    public function setOpenedItem(int $id)
    {
        $this->openedItem = $id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function render()
    {
        $content = $this->getMetaContent();
        $accordion = H::accordion()->setOpenedItem($this->openedItem);
        foreach ($content['items'] as $item) {
            if (!trim($item['body'])) { continue; }
            $data = explode('|', (isset($item['title']) ? $item['title'] : __("Introduction")));
            $status = isset($data[1]) ? $data[1] : null;
            $icon   = isset($data[2]) ? $data[2] : 'hand-o-right';
            $accordion->addPanel(H::panel($data[0], $item['body'], null, $status)->icon($icon));
        }
        return (string) $accordion;
    }
    
    /**
     * Render text content
     * @param string $text
     * @return string
     */
    public function renderText($text, bool $escape = true): string
    {
        if ($escape) {
            $text = $this->esc($text);
        }
        return (string) Container::getMarkdown()->text((string) $text);
    }
    
    /**
     * If file and text, append file and text
     * @return type
     * @throws ArchException
     */
    protected function getMetaContent()
    {
        if ($this->mdFile && !file_exists($this->mdFile)) {
            throw new ArchException('Wrong markdown file [' . $this->mdFile . ']');
        }
        if ($this->mdFile && !$this->getContent()) {
            return Container::getMarkdown()->file($this->mdFile, $this->separator);
        } else {
            $txt = '';
            if ($this->mdFile) {
                $txt .= file_get_contents($this->mdFile);
            }
            if ($this->getContent()) {
                $txt .= "\n" . $this->getContent();
            }
            return Container::getMarkdown()->txt($txt, $this->separator);
        }
    }
}