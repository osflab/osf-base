<?php
namespace Osf\Bean;

use Osf\Stream\Html;
use Osf\Container\OsfContainer as C;

/**
 * Common bean helpers
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage bean
 */
class BeanHelper
{
    /**
     * Escape and translate (markdown light)
     * @param string|null $content
     * @return string
     */
    public static function escapeAndMarkdownLight(?string $content): string
    {
        return $content ? C::getMarkdown()->textLight(Html::escape($content)) : (string) $content;
    }
    
    /**
     * Escape and translate (markdown)
     * @param string|null $content
     * @return string
     */
    public static function escapeAndMarkdown(?string $content): string
    {
        return $content ? C::getMarkdown()->text(Html::escape($content)) : (string) $content;
    }
    
    /**
     * Common filter for markdown content for getters
     * @param string|null $content
     * @param bool $compute
     * @param bool $fullMarkdown
     * @return string|null
     */
    public static function filterMarkdownContent(?string $content, bool $compute, bool $fullMarkdown = false): ?string
    {
        return $compute 
                ? ($fullMarkdown
                    ? self::escapeAndMarkdown($content)
                    : self::escapeAndMarkdownLight($content)
                ) 
                : $content;
    }
    
    /**
     * Common filter for general content for getters
     * @param string|null $content
     * @param bool $escape
     * @return string|null
     */
    public static function filterContent(?string $content, bool $escape, bool $nl2br = false): ?string
    {
        return $escape ? Html::escape($content, $nl2br) : $content;
    }
}
