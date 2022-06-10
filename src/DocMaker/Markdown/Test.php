<?php
namespace Osf\DocMaker\Markdown;

use Osf\DocMaker\Markdown;
use Osf\Test\Runner as OsfTest;

/**
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        $markdown = new Markdown();
        self::assertEqual($markdown->text('# bonjour'), '<h1>bonjour</h1>');
        
        return self::getResult();
    }
}
