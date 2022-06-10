<?php
namespace Osf\Config;

use Osf\Config\OsfConfig as Config;
use Osf\Stream\Yaml;
use Osf\Test\Runner as OsfTest;

/**
 * Config unit test
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        $config = new Config();
        self::assertEqual($config->getValues(), []);
        $config->appendConfig(Yaml::parseFile(__DIR__ . '/test.yml'));
        $form = $config->getForm();
        self::assertEqual(implode(',', array_keys($form->getSubForms())), 'product,user,cosmetics');
        self::assertEqual($config->getValues()['product']['currency'], 'EUR');
        self::assertEqual($config->getValues()['user']['isbn'], null);
        
        return self::getResult();
    }
}
