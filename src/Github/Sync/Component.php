<?php

namespace Osf\Github\Sync;

/**
 * Component manager
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage github
 */
class Component
{
    const OSF_VERSION = '3.0.2';
    
    const CATEGORY_REQUIRE = 'require';
    const CATEGORY_REQUIRE_DEV = 'require-dev';
    const CATEGORY_PROVIDE = 'provide';
    const CATEGORY_SUGGEST = 'suggest';
    
    const EXTERNALS = [
        'cache/redis-adapter' => '^1.0',
        'cache/simple-cache-bridge' => '^1.0',
        'erusev/parsedown' => '^1.6.4',
        'ext-haru' => '*',
        'ext-imagick' => '*',
        'ext-mbstring' => '*',
        'ext-openssl' => '*',
        'ext-redis' => '*',
        'ext-yaml' => '*',
        'mobiledetect/mobiledetectlib' => '^2.8.30',
        'patchwork/utf8' => '^1.3.1',
        'phpoffice/phpspreadsheet' => '^1.1.0',
        'psr/simple-cache' => '^1.0.0',
        'soundasleep/html2text' => '^0.5.0',
        'symfony/yaml' => '^4.0',
        'tecnickcom/tcpdf' => '^6.2.16',
        'twig/twig' => '^2.4.4',
        'zendframework/zend-authentication' => '~2.5.3', 
        'zendframework/zend-db' => '~2.9.2',
        'zendframework/zend-cache' => '~2.7.2',
        'zendframework/zend-code' => '~3.3.0',
        'zendframework/zend-filter' => '~2.7.2',
        'zendframework/zend-form' => '~2.11',
        'zendframework/zend-hydrator' => '~2.3.1',
        'zendframework/zend-i18n' => '~2.7.4',
        'zendframework/zend-i18n-resources' => '~2.5.2',
        'zendframework/zend-paginator' => '~2.8.1',
        'zendframework/zend-permissions-acl' => '~2.6.0',
        'zendframework/zend-validator' => '~2.10.2',
        'zendframework/zend-view' => '~2.10.0',
        'zendframework/ZendPdf' => '~2.0.2',
    ];
    
    protected $component = [];
    
    public function setDescription(string $desc)
    {
        $this->component['description'] = $desc;
        return $this;
    }
    
    /**
     * composer.json addons
     * @return array
     */
    public function getComposerArray(): array
    {
        return $this->component;
    }
    
    /**
     * Add a component to composer.json
     * @param string $name
     * @param string $version
     * @param string $category
     * @return $this
     */
    public function addComponent(string $name, ?string $version = null, string $category = self::CATEGORY_REQUIRE)
    {
        if ($version === null) {
            if (!isset(self::EXTERNALS[$name])) {
                throw new ArchException('Unknown component [' . $name . '], no version');
            }
            $version = self::EXTERNALS[$name];
        }
        $this->component[$category][$name] = $version;
        return $this;
    }
    
    /**
     * Add an OSF component
     * @param string $category
     * @param string $name
     * @return $this
     */
    public function addComponentOsf(string $name, ?string $version = null, string $category = self::CATEGORY_REQUIRE)
    {
        return $this->addComponent('osflab/' . $name, $version ?? '~' . self::OSF_VERSION, $category);
    }
    
    /**
     * Additional composer configuration
     * @param array $config
     * @return $this
     */
    public function appendComposerConfig(array $config)
    {
        $this->component = array_replace_recursive($this->component, $config);
        return $this;
    }
}
