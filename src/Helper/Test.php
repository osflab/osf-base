<?php
namespace Osf\Helper;

use Osf\Test\Runner as OsfTest;

/**
 * Array test
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
        
        $tab = Tab::newArray();
        self::assertEqual($tab->getArray(), []);
        $tab->addItemIfNotEmpty('a', null);
        $tab->addItemIfNotEmpty('b', '');
        $tab->addItemIfNotEmpty('c', 1);
        self::assertEqual($tab->getArray(), ['c' => 1]);

        self::assertEqual(Mysql::toDecimal(89), '89');
        self::assertEqual(Mysql::toDecimal(89.), '89');
        self::assertEqual(Mysql::toDecimal(89.78), '89.78');
        self::assertEqual(Mysql::toDecimal(.78), '0.78');
        self::assertEqual(Mysql::toDecimal(-.78), '-0.78');
        self::assertEqual(Mysql::toDecimal('78978907,345'), '78978907.345');
        self::assertEqual(Mysql::toDecimal(',345'), '0.345');
        
        self::assertEqual(Price::htToTtc(100, 0.2), (float) 120);
        self::assertEqual(Price::htToTtc(1000, 0.2), (float) 1200);
        self::assertEqual(Price::TtcToHt(120, 0.2), (float) 100);
        self::assertEqual(Price::htToTtc(100, 0.055), (float) 105.5);
        self::assertEqual(Price::TtcToHt(105.5, 0.055), (float) 100);
        self::assertEqual((string) Price::htToTtc(99.99, 0.055), (string) 105.48945);
        self::assertEqual((string) Price::TtcToHt(105.48945, 0.055), (string) 99.99);
        self::assertEqual((string) Price::htToTtc(99.99, 5.5, true), (string) 105.48945);
        self::assertEqual((string) Price::TtcToHt(105.48945, 5.5, true), (string) 99.99);
        
        self::assertEqual(Price::priceWithDiscount(100, 20, true), (float) 80);
        self::assertEqual(Price::priceWithDiscount(100, 0.2), (float) 80);
        
        return self::getResult();
    }
}
