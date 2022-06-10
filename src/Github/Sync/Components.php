<?php

namespace Osf\Github\Sync;

/**
 * Components configuration for github projects
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage github
 */
class Components
{
    const COMPONENT_LIST = [
        'application' => 'Application',
        'bean' => 'Bean',
        'cache' => 'Cache',
        'config' => 'Config',
        'console' => 'Console',
        'container' => 'Container',
        'controller' => 'Controller',
        'crypt' => 'Crypt',
        'db' => 'Db',
        'device' => 'Device',
        'doc-maker' => 'DocMaker',
        'exception' => 'Exception',
        'filter' => 'Filter',
        'form' => 'Form',
        'generator' => 'Generator',
        'github' => 'Github',
        'helper' => 'Helper',
        'image' => 'Image',
        'log' => 'Log',
        'navigation' => 'Navigation',
        'office' => 'Office',
        'pdf' => 'Pdf',
        'safety' => 'Safety',
        'session' => 'Session',
        'stream' => 'Stream',
        'test' => 'Test',
        'validator' => 'Validator',
        'view' => 'View',
    ];
    
    /**
     * get composer.json addon for the specified osf component
     * @param string $componentName
     * @return array
     */
    public static function getComponentInfo(string $componentName): array
    {
        if (!method_exists(__CLASS__, 'build' . $componentName)) {
            return [];
        }
        return self::{'build' . $componentName}();
    }
    
    public static function buildApplication(): array
    {
        return (new Component())
                ->setDescription('OSF high speed MVC component')
                ->addComponent('zendframework/zend-permissions-acl')
                ->addComponentOsf('exception')
                ->addComponentOsf('container')
                ->addComponentOsf('controller')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->getComposerArray();
    }
    
    public static function buildBean(): array
    {
        return (new Component())
                ->setDescription('OSF bean component')
                ->addComponentOsf('stream')
                ->addComponentOsf('exception')
                ->addComponentOsf('crypt', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('container', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('doc-maker', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildCache(): array
    {
        return (new Component())
                ->setDescription('OSF cache proxy optimized for performance')
                ->addComponent('ext-redis', '*')
                ->addComponent('psr/simple-cache')
                ->addComponent('zendframework/zend-cache', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('exception')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('container', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('application', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildConfig(): array
    {
        return (new Component())
                ->setDescription('OSF config component')
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponentOsf('form', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('db', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->getComposerArray();
    }
    
    public static function buildConsole(): array
    {
        return (new Component())
                ->setDescription('OSF simple unit test component')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
//                ->addComponentOsf('exception')
                ->getComposerArray();
    }
    
    public static function buildContainer(): array
    {
        return (new Component())
                ->setDescription('OSF dynamic object container mechanism')
                ->addComponentOsf('exception')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('stream', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('application', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('controller', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('view', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('crypt', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('navigation', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('doc-maker', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('device', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('session', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('cache', null, Component::CATEGORY_SUGGEST)
                ->addComponent('twig/twig', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-db', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-authentication', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-i18n', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-i18n-resources', null, Component::CATEGORY_SUGGEST)
                ->addComponent('ext-redis', '*', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildController(): array
    {
        return (new Component())
                ->setDescription('OSF controller component')
                ->addComponentOsf('console')
                ->addComponentOsf('application')
                ->addComponentOsf('generator')
                ->addComponentOsf('stream')
                ->addComponentOsf('container')
                ->addComponentOsf('exception')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('view', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-db', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildCrypt(): array
    {
        return (new Component())
                ->setDescription('OSF encryption, decryption and hashing component')
                ->addComponent('ext-openssl', '*')
                ->addComponentOsf('exception')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->getComposerArray();
    }
    
    public static function buildDb(): array
    {
        return (new Component())
                ->setDescription('OSF db manager')
                ->addComponent('zendframework/zend-db')
                ->addComponent('zendframework/zend-i18n')
                ->addComponent('zendframework/zend-i18n-resources')
                ->addComponent('zendframework/zend-validator')
                ->addComponent('zendframework/zend-hydrator')
                ->addComponentOsf('exception')
                ->addComponentOsf('container')
                ->addComponentOsf('stream')
                ->addComponentOsf('form', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('view', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildDevice(): array
    {
        return (new Component())
                ->setDescription('OSF simple device detection and manipulations')
                ->addComponent('mobiledetect/mobiledetectlib')
                ->addComponentOsf('container')
                ->addComponentOsf('session')
                ->addComponentOsf('exception')
                ->getComposerArray();
    }
    
    public static function buildDocMaker(): array
    {
        return (new Component())
                ->setDescription('OSF document generator')
                ->addComponent('erusev/parsedown')
                ->addComponentOsf('stream')
                ->addComponentOsf('exception')
                ->addComponentOsf('container')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('view', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildException(): array
    {
        return (new Component())
                ->setDescription('OSF standardized exceptions')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('container', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('controller', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('log', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('view', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('application', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildFilter(): array
    {
        return (new Component())
                ->setDescription('OSF filters based on ZF2/3')
                ->addComponent('zendframework/zend-filter')
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('generator', 'For the Filter quick access helper', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildForm(): array
    {
        return (new Component())
                ->setDescription('OSF form generator')
                ->addComponent('zendframework/zend-filter')
                ->addComponent('zendframework/zend-validator')
                ->addComponentOsf('filter')
                ->addComponentOsf('validator')
                ->addComponentOsf('stream')
                ->addComponentOsf('container')
                ->addComponentOsf('exception')
                ->addComponentOsf('view')
                ->addComponentOsf('db', 'For TableForm', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('session', 'Persistence options', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('application', 'OSF app integration', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildGenerator(): array
    {
        return (new Component())
                ->setDescription('OSF high-level code generator')
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponent('zendframework/zend-code', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponent('zendframework/zend-db', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-filter', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-validator', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-form', null, Component::CATEGORY_SUGGEST)
                ->addComponent('zendframework/zend-view', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('filter', 'Filters generation', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('validator', 'Validators generation', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('view', 'View helpers generation', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('container', 'App config detection', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('application', 'App config detection', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildGithub(): array
    {
        return (new Component())
                ->setDescription('OSF github components manager')
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponentOsf('console')
                ->getComposerArray();
    }
    
    public static function buildHelper(): array
    {
        return (new Component())
                ->setDescription('OSF helper library')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('stream')
                ->addComponentOsf('exception')
                ->getComposerArray();
    }
    
    public static function buildImage(): array
    {
        return (new Component())
                ->setDescription('OSF image manipulator')
                ->addComponent('ext-imagick', '*')
                ->addComponentOsf('exception')
                ->addComponentOsf('container')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('application', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('log', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildLog(): array
    {
        return (new Component())
                ->setDescription('OSF logger component')
                ->addComponentOsf('stream')
                ->addComponentOsf('exception')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->getComposerArray();
    }
    
    public static function buildNavigation(): array
    {
        return (new Component())
                ->setDescription('OSF navigation component')
                ->addComponent('mobiledetect/mobiledetectlib')
                ->addComponentOsf('application')
                ->addComponentOsf('view')
                ->addComponentOsf('container')
                ->addComponentOsf('stream')
                ->addComponentOsf('controller')
                ->addComponentOsf('exception')
                ->addComponentOsf('helper')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->getComposerArray();
    }
    
    public static function buildOffice(): array
    {
        return (new Component())
                ->setDescription('OSF office document proxies')
                ->addComponent('ext-redis', '*')
                ->addComponent('phpoffice/phpspreadsheet')
                ->addComponent('cache/redis-adapter')
                ->addComponent('cache/simple-cache-bridge')
                ->addComponentOsf('exception')
                ->addComponentOsf('container')
                ->addComponentOsf('cache')
                ->getComposerArray();
    }
    
    public static function buildPdf(): array
    {
        return (new Component())
                ->setDescription('OSF PDF high-level document generator')
                ->addComponent('tecnickcom/tcpdf')
                ->addComponent('zendframework/ZendPdf', 'If you want to use this deprecated feature', Component::CATEGORY_SUGGEST) // D
//                ->addComponent('ext-haru', '*') // D
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponentOsf('bean')
                ->addComponentOsf('helper')
                ->addComponentOsf('container')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('controller', 'OSF app integration', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('image', 'Pictures integration', Component::CATEGORY_SUGGEST)
                ->addComponentOsf('view', 'App integration', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildSafety(): array
    {
        return (new Component())
                ->setDescription('OSF safety component')
                ->addComponent('ext-redis', '*')
                ->addComponentOsf('exception')
                ->addComponentOsf('container')
                ->addComponentOsf('cache')
                ->addComponentOsf('crypt')
                ->addComponentOsf('log')
                ->getComposerArray();
    }
    
    public static function buildSession(): array
    {
        return (new Component())
                ->setDescription('OSF session manager')
//                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('exception')
                ->addComponentOsf('container', 'For the session container', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildStream(): array
    {
        return (new Component())
                ->setDescription('OSF stream management')
                ->addComponent('ext-mbstring', '*')
                ->addComponent('soundasleep/html2text')
                ->addComponent('patchwork/utf8')
                ->addComponent('symfony/yaml')
                ->addComponentOsf('exception')
                ->addComponentOsf('crypt')
                ->addComponentOsf('container')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('cache', null, Component::CATEGORY_SUGGEST)
                ->addComponent('ext-yaml', 'For better performances', Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function buildTest(): array
    {
        return (new Component())
                ->setDescription('OSF simple unit test component')
                ->addComponentOsf('console')
//                ->addComponentOsf('exception')
                ->addComponentOsf('container', null, Component::CATEGORY_SUGGEST)
                ->appendComposerConfig([
                    'bin' => ['bin/runtests'],
                    'scripts' => [
                        'post-install-cmd' => [
                            'php bin/runtests'
                        ],
                        'post-update-cmd' => [
                            'php bin/runtests'
                        ]
                    ]
                ])
                ->getComposerArray();
    }
    
    public static function buildValidator(): array
    {
        return (new Component())
                ->setDescription('OSF validators based on ZF2/3')
                ->addComponent('zendframework/zend-validator')
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponentOsf('generator')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->getComposerArray();
    }
    
    public static function buildView(): array
    {
        return (new Component())
                ->setDescription('OSF view component')
                ->addComponent('zendframework/zend-db')
                ->addComponent('zendframework/zend-paginator')
                ->addComponent('zendframework/zend-view')
                ->addComponentOsf('container')
                ->addComponentOsf('exception')
                ->addComponentOsf('stream')
                ->addComponentOsf('test', null, Component::CATEGORY_REQUIRE_DEV)
                ->addComponentOsf('controller', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('db', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('crypt', null, Component::CATEGORY_SUGGEST)
                ->addComponentOsf('form', null, Component::CATEGORY_SUGGEST)
                ->getComposerArray();
    }
    
    public static function getFrameworkAdds(): array
    {
        return [
            'twig/twig',
            'zendframework/zend-cache',
            'zendframework/zend-form'
        ];
    }
}
