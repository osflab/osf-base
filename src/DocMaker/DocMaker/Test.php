<?php
namespace Osf\DocMaker\DocMaker;

use Osf\DocMaker\DocMaker;
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
        
        $doc = new DocMaker();
        self::assertEqual(
                $doc->setContent("Doc Title\n\n! Welcome\n\n- point one\n- point two")->render(), 
                '<h1>Doc Title</h1><a name="s1"></a><h1>Welcome</h1><ul><li>point one<li>point two');
        
        return self::getResult();
    }
}
