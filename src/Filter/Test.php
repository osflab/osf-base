<?php
namespace Osf\Filter;

use Osf\Filter\Telephone;
use Osf\Test\Runner as OsfTest;

/**
 * Filters unit tests
 * 
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
        
        $tel = new Telephone();
       
        self::assertEqual($tel->filter('0123456789'), '0123456789');
        self::assertEqual($tel->filter('012345678'), '012345678');
        self::assertEqual($tel->filter('01234567'), '01234567');
        self::assertEqual($tel->filter('0123456'), '0123456');
        
        self::assertEqual($tel->filter('0 1 2 34 56 7 8 9'), '0123456789');
        self::assertEqual($tel->filter('   0123  45678'), '012345678');
        self::assertEqual($tel->filter('012345  67'), '01234567');
        self::assertEqual($tel->filter('+ 01234  56ab'), '+0123456ab');
        
        self::assertEqual($tel->filter('+33123456789'), '+33123456789');
        self::assertEqual($tel->filter('+33(1)23456789'), '+33(1)23456789');
        self::assertEqual($tel->filter('+33123456789'), '+33123456789');
        self::assertEqual($tel->filter('+ 33  (1 ) 234  5 6 7 89'), '+33(1)23456789');
        
        self::assertEqual((new CleanPhrase())->filter(' Welcome  on board ! '), 'Welcome on board !');
        self::assertEqual((new Currency())->filter('12,3'), '12.3');
        self::assertEqual((new Percentage())->filter(12.3), '12.3');
        self::assertEqual((new Percentage())->filter('12.3'), '12.3');
        self::assertEqual((new DateWire())->filter('2018-05-23'), '23/05/2018');
        self::assertEqual((new RemoveSpaces())->filter(' welcome  on board !! '), 'welcomeonboard!!');
        self::assertEqual((new UcPhrase())->filter(' jean - pierre   papin ! '), 'Jean-Pierre Papin !');
        
        if (class_exists('\Osf\Generator\AbstractBuilder')) {
            self::assertEqual(Filter::getBaseName()->filter(__FILE__), 'Test.php');
            self::assertEqual(Filter::getHtmlEntities()->filter('<b>hello</b>'), '&lt;b&gt;hello&lt;/b&gt;');
        }
        
        return self::getResult();
    }
}