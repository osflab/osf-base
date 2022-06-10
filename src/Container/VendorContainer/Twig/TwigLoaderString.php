<?php
namespace Osf\Container\VendorContainer\Twig;

use Twig_LoaderInterface;
use Twig_Source;
use Twig_Error_Loader;
use Osf\Crypt\Crypt;
use Osf\Container\OsfContainer as Container;

/**
 * Twig string loader
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage container
 */
class TwigLoaderString implements Twig_LoaderInterface
{
    const CACHE_PREFIX = 'TLS::';
    
    public function register($content = null, $name = null, bool $persist = false, bool $sandboxMode = true)
    {
        if ($content) {
            $name = $name ?? Crypt::hash((string) $content);
            $timeout = $persist ? null : 5;
            Container::getCache()->set($this->getCacheKey($name), $content, $timeout);
        }
        if (!$name) {
            throw new \Exception('Name or content required');
        }
        if ($sandboxMode) {
            $baseName = 'base_' . $name;
            $sbContent = "{% sandbox %}\n"
                       . "{% include '" . $name . "' %}\n"
                       . "{% endsandbox %}\n";
            Container::getCache()->set($this->getCacheKey($baseName), $sbContent, $timeout);
            return $baseName;
        }
        return $name;
    }
    
    /**
     * Returns the source context for a given template logical name.
     * @param string $name The template logical name
     * @return Twig_Source
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSourceContext($name): Twig_Source
    {
        return new Twig_Source(Container::getCache()->get($this->getCacheKey($name)), $name);
    }
    
    /**
     * Gets the cache key to use for the cache for a given template name.
     * @param string $name The name of the template to load
     * @return string The cache key
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name): string
    {
        return self::CACHE_PREFIX . $name;
    }
    
    /**
     * Returns true if the template is still fresh.
     * @param string $name The template name
     * @param timestamp $time The last modification time of the cached template
     * @return bool true if the template is fresh, false otherwise
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time): bool
    {
        if (!$this->exists($name)) {
            throw new Twig_Error_Loader('Template not found');
        }
        return time() - $time < 24 * 3600;
    }

    /**
     * Check if we have the source code of a template, given its name.
     * @param string $name The name of the template to check if we can load
     * @return bool    If the template source code is handled by this loader or not
     */
    public function exists($name): bool
    {
        return Container::getCache()->get($this->getCacheKey($name)) === null;
    }
}
