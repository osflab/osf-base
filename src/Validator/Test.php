<?php
namespace Osf\Validator;

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

        $bic = new Bic();
        self::assert($bic->isValid('78978978'));
        self::assert($bic->isValid('78978978890'));
        self::assert($bic->isValid('7897897889A'));
        self::assert(!$bic->isValid('7897897889'));
        self::assert(!$bic->isValid('7897897889a'));
        
        $currency = new Currency();
        self::assert($currency->isValid('12'));
        self::assert($currency->isValid('12.25'));
        self::assert($currency->isValid('12789798.23'));
        self::assert(!$currency->isValid('-12.20'));
        self::assertEqual(array_keys($currency->getMessages())[0], $currency::NEGATIVE);
        self::assert(!$currency->isValid('23.389'));
        self::assertEqual(array_keys($currency->getMessages())[1], $currency::INVALID);
        
        $iban = new IbanFr();
        self::assert($iban->isValid('FR1420041010050500013M02606'));
        
        $password = new Password();
        self::assert($password->isValid('JKLMJKL78'));
        self::assert($password->isValid('73894JKL'));
        self::assert(!$password->isValid('pass'));
        self::assert(!$password->isValid('67878907890'));
        self::assert(!$password->isValid('56HJ'));
        
        $percentage = new Percentage();
        self::assert($percentage->isValid('1'));
        self::assert($percentage->isValid('100'));
        self::assert(!$percentage->isValid('101'));
        self::assert(!$percentage->isValid('-1'));
        self::assert(!$percentage->isValid('12.2'));
        self::assert(!$percentage->isValid('A'));
        self::assert(!$percentage->isValid('12,3'));
        
        $postal = new PostalAddressBody();
        self::assert($postal->isValid("12 rue des anges\n45000 Joliville"));
        self::assert($postal->isValid("jkl\njkl\nhjkmlnjsdkfl"));
        self::assert(!$postal->isValid("jkl\njkl\nhjkml\njsdkfl"));
        self::assert(!$postal->isValid(""));
        
        $rna = new Rna();
        self::assert($rna->isValid('W789789789'));
        self::assert($rna->isValid('W000000000'));
        self::assert(!$rna->isValid('X789789789'));
        self::assert(!$rna->isValid('7897897899'));
        self::assert(!$rna->isValid(''));

        $siret = new Siret();
        self::assert($siret->isValid('50198652500027'));
        self::assert(!$siret->isValid('50198652500026'));
        
        $tel = new Telephone();
        self::assert($tel->isValid('0123456789'));
        self::assert($tel->isValid('5687'));
        self::assert(!$tel->isValid('2'));
        self::assert(!$tel->isValid('F89EYZ89'));
        
        $tva = new TvaIntra();
        self::assert($tva->isValid('FR789678968'));
        self::assert($tva->isValid('US789678968'));
        self::assert(!$tva->isValid('38940839084902'));
        
        return self::getResult();
    }
}