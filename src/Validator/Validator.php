<?php
namespace Osf\Validator;

/**
 * Validators general builder
 *
 * This class is generated, do not edit.
 *
 * @version 1.0
 * @author Guillaume Ponçon - OpenStates Framework PHP Generator
 * @since OSF 3.0.0
 * @package osf
 * @subpackage generated
 */
abstract class Validator extends \Osf\Generator\AbstractBuilder
{

    protected static $classes = [
        'Alnum' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nAlnum',
        'Alpha' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nAlpha',
        'Barcode' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcode',
        'BarcodeAdapterInterface' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeAdapterInterface',
        'BarcodeCodabar' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCodabar',
        'BarcodeCode128' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode128',
        'BarcodeCode25' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode25',
        'BarcodeCode25interleaved' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode25interleaved',
        'BarcodeCode39' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode39',
        'BarcodeCode39ext' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode39ext',
        'BarcodeCode93' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode93',
        'BarcodeCode93ext' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeCode93ext',
        'BarcodeEan12' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan12',
        'BarcodeEan13' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan13',
        'BarcodeEan14' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan14',
        'BarcodeEan18' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan18',
        'BarcodeEan2' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan2',
        'BarcodeEan5' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan5',
        'BarcodeEan8' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeEan8',
        'BarcodeGtin12' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeGtin12',
        'BarcodeGtin13' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeGtin13',
        'BarcodeGtin14' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeGtin14',
        'BarcodeIdentcode' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeIdentcode',
        'BarcodeIntelligentmail' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeIntelligentmail',
        'BarcodeIssn' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeIssn',
        'BarcodeItf14' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeItf14',
        'BarcodeLeitcode' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeLeitcode',
        'BarcodePlanet' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodePlanet',
        'BarcodePostnet' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodePostnet',
        'BarcodeRoyalmail' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeRoyalmail',
        'BarcodeSscc' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeSscc',
        'BarcodeUpca' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeUpca',
        'BarcodeUpce' => '\\Osf\\Validator\\Zend\\ZendValidatorBarcodeUpce',
        'Between' => '\\Osf\\Validator\\Zend\\ZendValidatorBetween',
        'Bic' => '\\Osf\\Validator\\Bic',
        'Bitwise' => '\\Osf\\Validator\\Zend\\ZendValidatorBitwise',
        'Callback' => '\\Osf\\Validator\\Zend\\ZendValidatorCallback',
        'ConfigProvider' => '\\Osf\\Validator\\Zend\\ZendValidatorConfigProvider',
        'CreditCard' => '\\Osf\\Validator\\Zend\\ZendValidatorCreditCard',
        'Csrf' => '\\Osf\\Validator\\Zend\\ZendValidatorCsrf',
        'Currency' => '\\Osf\\Validator\\Currency',
        'Date' => '\\Osf\\Validator\\Zend\\ZendValidatorDate',
        'DateStep' => '\\Osf\\Validator\\Zend\\ZendValidatorDateStep',
        'DateTime' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nDateTime',
        'Digits' => '\\Osf\\Validator\\Zend\\ZendValidatorDigits',
        'EmailAddress' => '\\Osf\\Validator\\EmailAddress',
        'Explode' => '\\Osf\\Validator\\Zend\\ZendValidatorExplode',
        'FileCount' => '\\Osf\\Validator\\Zend\\ZendValidatorFileCount',
        'FileCrc32' => '\\Osf\\Validator\\Zend\\ZendValidatorFileCrc32',
        'FileExcludeExtension' => '\\Osf\\Validator\\Zend\\ZendValidatorFileExcludeExtension',
        'FileExcludeMimeType' => '\\Osf\\Validator\\Zend\\ZendValidatorFileExcludeMimeType',
        'FileExists' => '\\Osf\\Validator\\Zend\\ZendValidatorFileExists',
        'FileExtension' => '\\Osf\\Validator\\Zend\\ZendValidatorFileExtension',
        'FileFilesSize' => '\\Osf\\Validator\\Zend\\ZendValidatorFileFilesSize',
        'FileHash' => '\\Osf\\Validator\\Zend\\ZendValidatorFileHash',
        'FileImageSize' => '\\Osf\\Validator\\Zend\\ZendValidatorFileImageSize',
        'FileIsCompressed' => '\\Osf\\Validator\\Zend\\ZendValidatorFileIsCompressed',
        'FileIsImage' => '\\Osf\\Validator\\Zend\\ZendValidatorFileIsImage',
        'FileMd5' => '\\Osf\\Validator\\Zend\\ZendValidatorFileMd5',
        'FileMimeType' => '\\Osf\\Validator\\Zend\\ZendValidatorFileMimeType',
        'FileNotExists' => '\\Osf\\Validator\\Zend\\ZendValidatorFileNotExists',
        'FileSha1' => '\\Osf\\Validator\\Zend\\ZendValidatorFileSha1',
        'FileSize' => '\\Osf\\Validator\\Zend\\ZendValidatorFileSize',
        'FileUpload' => '\\Osf\\Validator\\Zend\\ZendValidatorFileUpload',
        'FileUploadFile' => '\\Osf\\Validator\\Zend\\ZendValidatorFileUploadFile',
        'FileWordCount' => '\\Osf\\Validator\\Zend\\ZendValidatorFileWordCount',
        'Float' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nFloat',
        'GpsPoint' => '\\Osf\\Validator\\Zend\\ZendValidatorGpsPoint',
        'GreaterThan' => '\\Osf\\Validator\\Zend\\ZendValidatorGreaterThan',
        'Hex' => '\\Osf\\Validator\\Zend\\ZendValidatorHex',
        'Hostname' => '\\Osf\\Validator\\Zend\\ZendValidatorHostname',
        'HostnameBiz' => '\\Osf\\Validator\\Zend\\ZendValidatorHostnameBiz',
        'HostnameCn' => '\\Osf\\Validator\\Zend\\ZendValidatorHostnameCn',
        'HostnameCom' => '\\Osf\\Validator\\Zend\\ZendValidatorHostnameCom',
        'HostnameJp' => '\\Osf\\Validator\\Zend\\ZendValidatorHostnameJp',
        'Iban' => '\\Osf\\Validator\\Zend\\ZendValidatorIban',
        'IbanFr' => '\\Osf\\Validator\\IbanFr',
        'Identical' => '\\Osf\\Validator\\Zend\\ZendValidatorIdentical',
        'InArray' => '\\Osf\\Validator\\Zend\\ZendValidatorInArray',
        'Int' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nInt',
        'Ip' => '\\Osf\\Validator\\Zend\\ZendValidatorIp',
        'IsCountable' => '\\Osf\\Validator\\Zend\\ZendValidatorIsCountable',
        'IsFloat' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nIsFloat',
        'IsInstanceOf' => '\\Osf\\Validator\\Zend\\ZendValidatorIsInstanceOf',
        'IsInt' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nIsInt',
        'Isbn' => '\\Osf\\Validator\\Zend\\ZendValidatorIsbn',
        'LessThan' => '\\Osf\\Validator\\Zend\\ZendValidatorLessThan',
        'Module' => '\\Osf\\Validator\\Zend\\ZendValidatorModule',
        'NotEmpty' => '\\Osf\\Validator\\Zend\\ZendValidatorNotEmpty',
        'Password' => '\\Osf\\Validator\\Password',
        'Percentage' => '\\Osf\\Validator\\Percentage',
        'PhoneNumber' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nPhoneNumber',
        'PostCode' => '\\Osf\\Validator\\Zend\\ZendValidatorI18nPostCode',
        'PostalAddressBody' => '\\Osf\\Validator\\PostalAddressBody',
        'Regex' => '\\Osf\\Validator\\Zend\\ZendValidatorRegex',
        'Rna' => '\\Osf\\Validator\\Rna',
        'Siret' => '\\Osf\\Validator\\Siret',
        'StaticValidator' => '\\Osf\\Validator\\Zend\\ZendValidatorStaticValidator',
        'Step' => '\\Osf\\Validator\\Zend\\ZendValidatorStep',
        'StringLength' => '\\Osf\\Validator\\Zend\\ZendValidatorStringLength',
        'Telephone' => '\\Osf\\Validator\\Telephone',
        'Timezone' => '\\Osf\\Validator\\Zend\\ZendValidatorTimezone',
        'TvaIntra' => '\\Osf\\Validator\\TvaIntra',
        'Uri' => '\\Osf\\Validator\\Zend\\ZendValidatorUri',
        'Uuid' => '\\Osf\\Validator\\Zend\\ZendValidatorUuid',
        'ValidatorChain' => '\\Osf\\Validator\\Zend\\ZendValidatorValidatorChain',
        'ValidatorInterface' => '\\Osf\\Validator\\Zend\\ZendValidatorValidatorInterface',
        'ValidatorPluginManager' => '\\Osf\\Validator\\Zend\\ZendValidatorValidatorPluginManager',
        'ValidatorPluginManagerAwareInterface' => '\\Osf\\Validator\\Zend\\ZendValidatorValidatorPluginManagerAwareInterface',
        'ValidatorPluginManagerFactory' => '\\Osf\\Validator\\Zend\\ZendValidatorValidatorPluginManagerFactory',
        'ValidatorProviderInterface' => '\\Osf\\Validator\\Zend\\ZendValidatorValidatorProviderInterface',
    ];

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nAlnum
     */
    public static function newAlnum(...$args)
    {
        return self::get('Alnum', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nAlnum
     */
    public static function getAlnum(...$args)
    {
        return self::get('Alnum', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nAlpha
     */
    public static function newAlpha(...$args)
    {
        return self::get('Alpha', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nAlpha
     */
    public static function getAlpha(...$args)
    {
        return self::get('Alpha', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcode
     */
    public static function newBarcode(...$args)
    {
        return self::get('Barcode', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcode
     */
    public static function getBarcode(...$args)
    {
        return self::get('Barcode', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeAdapterInterface
     */
    public static function newBarcodeAdapterInterface(...$args)
    {
        return self::get('BarcodeAdapterInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeAdapterInterface
     */
    public static function getBarcodeAdapterInterface(...$args)
    {
        return self::get('BarcodeAdapterInterface', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCodabar
     */
    public static function newBarcodeCodabar(...$args)
    {
        return self::get('BarcodeCodabar', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCodabar
     */
    public static function getBarcodeCodabar(...$args)
    {
        return self::get('BarcodeCodabar', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode128
     */
    public static function newBarcodeCode128(...$args)
    {
        return self::get('BarcodeCode128', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode128
     */
    public static function getBarcodeCode128(...$args)
    {
        return self::get('BarcodeCode128', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode25
     */
    public static function newBarcodeCode25(...$args)
    {
        return self::get('BarcodeCode25', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode25
     */
    public static function getBarcodeCode25(...$args)
    {
        return self::get('BarcodeCode25', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode25interleaved
     */
    public static function newBarcodeCode25interleaved(...$args)
    {
        return self::get('BarcodeCode25interleaved', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode25interleaved
     */
    public static function getBarcodeCode25interleaved(...$args)
    {
        return self::get('BarcodeCode25interleaved', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode39
     */
    public static function newBarcodeCode39(...$args)
    {
        return self::get('BarcodeCode39', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode39
     */
    public static function getBarcodeCode39(...$args)
    {
        return self::get('BarcodeCode39', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode39ext
     */
    public static function newBarcodeCode39ext(...$args)
    {
        return self::get('BarcodeCode39ext', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode39ext
     */
    public static function getBarcodeCode39ext(...$args)
    {
        return self::get('BarcodeCode39ext', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode93
     */
    public static function newBarcodeCode93(...$args)
    {
        return self::get('BarcodeCode93', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode93
     */
    public static function getBarcodeCode93(...$args)
    {
        return self::get('BarcodeCode93', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode93ext
     */
    public static function newBarcodeCode93ext(...$args)
    {
        return self::get('BarcodeCode93ext', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeCode93ext
     */
    public static function getBarcodeCode93ext(...$args)
    {
        return self::get('BarcodeCode93ext', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan12
     */
    public static function newBarcodeEan12(...$args)
    {
        return self::get('BarcodeEan12', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan12
     */
    public static function getBarcodeEan12(...$args)
    {
        return self::get('BarcodeEan12', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan13
     */
    public static function newBarcodeEan13(...$args)
    {
        return self::get('BarcodeEan13', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan13
     */
    public static function getBarcodeEan13(...$args)
    {
        return self::get('BarcodeEan13', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan14
     */
    public static function newBarcodeEan14(...$args)
    {
        return self::get('BarcodeEan14', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan14
     */
    public static function getBarcodeEan14(...$args)
    {
        return self::get('BarcodeEan14', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan18
     */
    public static function newBarcodeEan18(...$args)
    {
        return self::get('BarcodeEan18', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan18
     */
    public static function getBarcodeEan18(...$args)
    {
        return self::get('BarcodeEan18', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan2
     */
    public static function newBarcodeEan2(...$args)
    {
        return self::get('BarcodeEan2', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan2
     */
    public static function getBarcodeEan2(...$args)
    {
        return self::get('BarcodeEan2', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan5
     */
    public static function newBarcodeEan5(...$args)
    {
        return self::get('BarcodeEan5', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan5
     */
    public static function getBarcodeEan5(...$args)
    {
        return self::get('BarcodeEan5', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan8
     */
    public static function newBarcodeEan8(...$args)
    {
        return self::get('BarcodeEan8', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeEan8
     */
    public static function getBarcodeEan8(...$args)
    {
        return self::get('BarcodeEan8', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeGtin12
     */
    public static function newBarcodeGtin12(...$args)
    {
        return self::get('BarcodeGtin12', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeGtin12
     */
    public static function getBarcodeGtin12(...$args)
    {
        return self::get('BarcodeGtin12', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeGtin13
     */
    public static function newBarcodeGtin13(...$args)
    {
        return self::get('BarcodeGtin13', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeGtin13
     */
    public static function getBarcodeGtin13(...$args)
    {
        return self::get('BarcodeGtin13', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeGtin14
     */
    public static function newBarcodeGtin14(...$args)
    {
        return self::get('BarcodeGtin14', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeGtin14
     */
    public static function getBarcodeGtin14(...$args)
    {
        return self::get('BarcodeGtin14', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeIdentcode
     */
    public static function newBarcodeIdentcode(...$args)
    {
        return self::get('BarcodeIdentcode', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeIdentcode
     */
    public static function getBarcodeIdentcode(...$args)
    {
        return self::get('BarcodeIdentcode', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeIntelligentmail
     */
    public static function newBarcodeIntelligentmail(...$args)
    {
        return self::get('BarcodeIntelligentmail', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeIntelligentmail
     */
    public static function getBarcodeIntelligentmail(...$args)
    {
        return self::get('BarcodeIntelligentmail', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeIssn
     */
    public static function newBarcodeIssn(...$args)
    {
        return self::get('BarcodeIssn', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeIssn
     */
    public static function getBarcodeIssn(...$args)
    {
        return self::get('BarcodeIssn', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeItf14
     */
    public static function newBarcodeItf14(...$args)
    {
        return self::get('BarcodeItf14', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeItf14
     */
    public static function getBarcodeItf14(...$args)
    {
        return self::get('BarcodeItf14', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeLeitcode
     */
    public static function newBarcodeLeitcode(...$args)
    {
        return self::get('BarcodeLeitcode', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeLeitcode
     */
    public static function getBarcodeLeitcode(...$args)
    {
        return self::get('BarcodeLeitcode', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodePlanet
     */
    public static function newBarcodePlanet(...$args)
    {
        return self::get('BarcodePlanet', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodePlanet
     */
    public static function getBarcodePlanet(...$args)
    {
        return self::get('BarcodePlanet', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodePostnet
     */
    public static function newBarcodePostnet(...$args)
    {
        return self::get('BarcodePostnet', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodePostnet
     */
    public static function getBarcodePostnet(...$args)
    {
        return self::get('BarcodePostnet', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeRoyalmail
     */
    public static function newBarcodeRoyalmail(...$args)
    {
        return self::get('BarcodeRoyalmail', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeRoyalmail
     */
    public static function getBarcodeRoyalmail(...$args)
    {
        return self::get('BarcodeRoyalmail', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeSscc
     */
    public static function newBarcodeSscc(...$args)
    {
        return self::get('BarcodeSscc', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeSscc
     */
    public static function getBarcodeSscc(...$args)
    {
        return self::get('BarcodeSscc', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeUpca
     */
    public static function newBarcodeUpca(...$args)
    {
        return self::get('BarcodeUpca', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeUpca
     */
    public static function getBarcodeUpca(...$args)
    {
        return self::get('BarcodeUpca', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeUpce
     */
    public static function newBarcodeUpce(...$args)
    {
        return self::get('BarcodeUpce', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBarcodeUpce
     */
    public static function getBarcodeUpce(...$args)
    {
        return self::get('BarcodeUpce', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBetween
     */
    public static function newBetween(...$args)
    {
        return self::get('Between', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBetween
     */
    public static function getBetween(...$args)
    {
        return self::get('Between', $args, true);
    }

    /**
     * @return \Osf\Validator\Bic
     */
    public static function newBic(...$args)
    {
        return self::get('Bic', $args, false);
    }

    /**
     * @return \Osf\Validator\Bic
     */
    public static function getBic(...$args)
    {
        return self::get('Bic', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBitwise
     */
    public static function newBitwise(...$args)
    {
        return self::get('Bitwise', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorBitwise
     */
    public static function getBitwise(...$args)
    {
        return self::get('Bitwise', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorCallback
     */
    public static function newCallback(...$args)
    {
        return self::get('Callback', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorCallback
     */
    public static function getCallback(...$args)
    {
        return self::get('Callback', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorConfigProvider
     */
    public static function newConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorConfigProvider
     */
    public static function getConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorCreditCard
     */
    public static function newCreditCard(...$args)
    {
        return self::get('CreditCard', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorCreditCard
     */
    public static function getCreditCard(...$args)
    {
        return self::get('CreditCard', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorCsrf
     */
    public static function newCsrf(...$args)
    {
        return self::get('Csrf', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorCsrf
     */
    public static function getCsrf(...$args)
    {
        return self::get('Csrf', $args, true);
    }

    /**
     * @return \Osf\Validator\Currency
     */
    public static function newCurrency(...$args)
    {
        return self::get('Currency', $args, false);
    }

    /**
     * @return \Osf\Validator\Currency
     */
    public static function getCurrency(...$args)
    {
        return self::get('Currency', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorDate
     */
    public static function newDate(...$args)
    {
        return self::get('Date', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorDate
     */
    public static function getDate(...$args)
    {
        return self::get('Date', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorDateStep
     */
    public static function newDateStep(...$args)
    {
        return self::get('DateStep', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorDateStep
     */
    public static function getDateStep(...$args)
    {
        return self::get('DateStep', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nDateTime
     */
    public static function newDateTime(...$args)
    {
        return self::get('DateTime', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nDateTime
     */
    public static function getDateTime(...$args)
    {
        return self::get('DateTime', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorDigits
     */
    public static function newDigits(...$args)
    {
        return self::get('Digits', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorDigits
     */
    public static function getDigits(...$args)
    {
        return self::get('Digits', $args, true);
    }

    /**
     * @return \Osf\Validator\EmailAddress
     */
    public static function newEmailAddress(...$args)
    {
        return self::get('EmailAddress', $args, false);
    }

    /**
     * @return \Osf\Validator\EmailAddress
     */
    public static function getEmailAddress(...$args)
    {
        return self::get('EmailAddress', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorExplode
     */
    public static function newExplode(...$args)
    {
        return self::get('Explode', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorExplode
     */
    public static function getExplode(...$args)
    {
        return self::get('Explode', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileCount
     */
    public static function newFileCount(...$args)
    {
        return self::get('FileCount', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileCount
     */
    public static function getFileCount(...$args)
    {
        return self::get('FileCount', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileCrc32
     */
    public static function newFileCrc32(...$args)
    {
        return self::get('FileCrc32', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileCrc32
     */
    public static function getFileCrc32(...$args)
    {
        return self::get('FileCrc32', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExcludeExtension
     */
    public static function newFileExcludeExtension(...$args)
    {
        return self::get('FileExcludeExtension', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExcludeExtension
     */
    public static function getFileExcludeExtension(...$args)
    {
        return self::get('FileExcludeExtension', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExcludeMimeType
     */
    public static function newFileExcludeMimeType(...$args)
    {
        return self::get('FileExcludeMimeType', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExcludeMimeType
     */
    public static function getFileExcludeMimeType(...$args)
    {
        return self::get('FileExcludeMimeType', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExists
     */
    public static function newFileExists(...$args)
    {
        return self::get('FileExists', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExists
     */
    public static function getFileExists(...$args)
    {
        return self::get('FileExists', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExtension
     */
    public static function newFileExtension(...$args)
    {
        return self::get('FileExtension', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileExtension
     */
    public static function getFileExtension(...$args)
    {
        return self::get('FileExtension', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileFilesSize
     */
    public static function newFileFilesSize(...$args)
    {
        return self::get('FileFilesSize', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileFilesSize
     */
    public static function getFileFilesSize(...$args)
    {
        return self::get('FileFilesSize', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileHash
     */
    public static function newFileHash(...$args)
    {
        return self::get('FileHash', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileHash
     */
    public static function getFileHash(...$args)
    {
        return self::get('FileHash', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileImageSize
     */
    public static function newFileImageSize(...$args)
    {
        return self::get('FileImageSize', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileImageSize
     */
    public static function getFileImageSize(...$args)
    {
        return self::get('FileImageSize', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileIsCompressed
     */
    public static function newFileIsCompressed(...$args)
    {
        return self::get('FileIsCompressed', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileIsCompressed
     */
    public static function getFileIsCompressed(...$args)
    {
        return self::get('FileIsCompressed', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileIsImage
     */
    public static function newFileIsImage(...$args)
    {
        return self::get('FileIsImage', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileIsImage
     */
    public static function getFileIsImage(...$args)
    {
        return self::get('FileIsImage', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileMd5
     */
    public static function newFileMd5(...$args)
    {
        return self::get('FileMd5', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileMd5
     */
    public static function getFileMd5(...$args)
    {
        return self::get('FileMd5', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileMimeType
     */
    public static function newFileMimeType(...$args)
    {
        return self::get('FileMimeType', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileMimeType
     */
    public static function getFileMimeType(...$args)
    {
        return self::get('FileMimeType', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileNotExists
     */
    public static function newFileNotExists(...$args)
    {
        return self::get('FileNotExists', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileNotExists
     */
    public static function getFileNotExists(...$args)
    {
        return self::get('FileNotExists', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileSha1
     */
    public static function newFileSha1(...$args)
    {
        return self::get('FileSha1', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileSha1
     */
    public static function getFileSha1(...$args)
    {
        return self::get('FileSha1', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileSize
     */
    public static function newFileSize(...$args)
    {
        return self::get('FileSize', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileSize
     */
    public static function getFileSize(...$args)
    {
        return self::get('FileSize', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileUpload
     */
    public static function newFileUpload(...$args)
    {
        return self::get('FileUpload', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileUpload
     */
    public static function getFileUpload(...$args)
    {
        return self::get('FileUpload', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileUploadFile
     */
    public static function newFileUploadFile(...$args)
    {
        return self::get('FileUploadFile', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileUploadFile
     */
    public static function getFileUploadFile(...$args)
    {
        return self::get('FileUploadFile', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileWordCount
     */
    public static function newFileWordCount(...$args)
    {
        return self::get('FileWordCount', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorFileWordCount
     */
    public static function getFileWordCount(...$args)
    {
        return self::get('FileWordCount', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nFloat
     */
    public static function newFloat(...$args)
    {
        return self::get('Float', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nFloat
     */
    public static function getFloat(...$args)
    {
        return self::get('Float', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorGpsPoint
     */
    public static function newGpsPoint(...$args)
    {
        return self::get('GpsPoint', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorGpsPoint
     */
    public static function getGpsPoint(...$args)
    {
        return self::get('GpsPoint', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorGreaterThan
     */
    public static function newGreaterThan(...$args)
    {
        return self::get('GreaterThan', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorGreaterThan
     */
    public static function getGreaterThan(...$args)
    {
        return self::get('GreaterThan', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHex
     */
    public static function newHex(...$args)
    {
        return self::get('Hex', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHex
     */
    public static function getHex(...$args)
    {
        return self::get('Hex', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostname
     */
    public static function newHostname(...$args)
    {
        return self::get('Hostname', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostname
     */
    public static function getHostname(...$args)
    {
        return self::get('Hostname', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameBiz
     */
    public static function newHostnameBiz(...$args)
    {
        return self::get('HostnameBiz', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameBiz
     */
    public static function getHostnameBiz(...$args)
    {
        return self::get('HostnameBiz', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameCn
     */
    public static function newHostnameCn(...$args)
    {
        return self::get('HostnameCn', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameCn
     */
    public static function getHostnameCn(...$args)
    {
        return self::get('HostnameCn', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameCom
     */
    public static function newHostnameCom(...$args)
    {
        return self::get('HostnameCom', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameCom
     */
    public static function getHostnameCom(...$args)
    {
        return self::get('HostnameCom', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameJp
     */
    public static function newHostnameJp(...$args)
    {
        return self::get('HostnameJp', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorHostnameJp
     */
    public static function getHostnameJp(...$args)
    {
        return self::get('HostnameJp', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIban
     */
    public static function newIban(...$args)
    {
        return self::get('Iban', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIban
     */
    public static function getIban(...$args)
    {
        return self::get('Iban', $args, true);
    }

    /**
     * @return \Osf\Validator\IbanFr
     */
    public static function newIbanFr(...$args)
    {
        return self::get('IbanFr', $args, false);
    }

    /**
     * @return \Osf\Validator\IbanFr
     */
    public static function getIbanFr(...$args)
    {
        return self::get('IbanFr', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIdentical
     */
    public static function newIdentical(...$args)
    {
        return self::get('Identical', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIdentical
     */
    public static function getIdentical(...$args)
    {
        return self::get('Identical', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorInArray
     */
    public static function newInArray(...$args)
    {
        return self::get('InArray', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorInArray
     */
    public static function getInArray(...$args)
    {
        return self::get('InArray', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nInt
     */
    public static function newInt(...$args)
    {
        return self::get('Int', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nInt
     */
    public static function getInt(...$args)
    {
        return self::get('Int', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIp
     */
    public static function newIp(...$args)
    {
        return self::get('Ip', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIp
     */
    public static function getIp(...$args)
    {
        return self::get('Ip', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIsCountable
     */
    public static function newIsCountable(...$args)
    {
        return self::get('IsCountable', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIsCountable
     */
    public static function getIsCountable(...$args)
    {
        return self::get('IsCountable', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nIsFloat
     */
    public static function newIsFloat(...$args)
    {
        return self::get('IsFloat', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nIsFloat
     */
    public static function getIsFloat(...$args)
    {
        return self::get('IsFloat', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIsInstanceOf
     */
    public static function newIsInstanceOf(...$args)
    {
        return self::get('IsInstanceOf', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIsInstanceOf
     */
    public static function getIsInstanceOf(...$args)
    {
        return self::get('IsInstanceOf', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nIsInt
     */
    public static function newIsInt(...$args)
    {
        return self::get('IsInt', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nIsInt
     */
    public static function getIsInt(...$args)
    {
        return self::get('IsInt', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIsbn
     */
    public static function newIsbn(...$args)
    {
        return self::get('Isbn', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorIsbn
     */
    public static function getIsbn(...$args)
    {
        return self::get('Isbn', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorLessThan
     */
    public static function newLessThan(...$args)
    {
        return self::get('LessThan', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorLessThan
     */
    public static function getLessThan(...$args)
    {
        return self::get('LessThan', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorModule
     */
    public static function newModule(...$args)
    {
        return self::get('Module', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorModule
     */
    public static function getModule(...$args)
    {
        return self::get('Module', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorNotEmpty
     */
    public static function newNotEmpty(...$args)
    {
        return self::get('NotEmpty', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorNotEmpty
     */
    public static function getNotEmpty(...$args)
    {
        return self::get('NotEmpty', $args, true);
    }

    /**
     * @return \Osf\Validator\Password
     */
    public static function newPassword(...$args)
    {
        return self::get('Password', $args, false);
    }

    /**
     * @return \Osf\Validator\Password
     */
    public static function getPassword(...$args)
    {
        return self::get('Password', $args, true);
    }

    /**
     * @return \Osf\Validator\Percentage
     */
    public static function newPercentage(...$args)
    {
        return self::get('Percentage', $args, false);
    }

    /**
     * @return \Osf\Validator\Percentage
     */
    public static function getPercentage(...$args)
    {
        return self::get('Percentage', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nPhoneNumber
     */
    public static function newPhoneNumber(...$args)
    {
        return self::get('PhoneNumber', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nPhoneNumber
     */
    public static function getPhoneNumber(...$args)
    {
        return self::get('PhoneNumber', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nPostCode
     */
    public static function newPostCode(...$args)
    {
        return self::get('PostCode', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorI18nPostCode
     */
    public static function getPostCode(...$args)
    {
        return self::get('PostCode', $args, true);
    }

    /**
     * @return \Osf\Validator\PostalAddressBody
     */
    public static function newPostalAddressBody(...$args)
    {
        return self::get('PostalAddressBody', $args, false);
    }

    /**
     * @return \Osf\Validator\PostalAddressBody
     */
    public static function getPostalAddressBody(...$args)
    {
        return self::get('PostalAddressBody', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorRegex
     */
    public static function newRegex(...$args)
    {
        return self::get('Regex', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorRegex
     */
    public static function getRegex(...$args)
    {
        return self::get('Regex', $args, true);
    }

    /**
     * @return \Osf\Validator\Rna
     */
    public static function newRna(...$args)
    {
        return self::get('Rna', $args, false);
    }

    /**
     * @return \Osf\Validator\Rna
     */
    public static function getRna(...$args)
    {
        return self::get('Rna', $args, true);
    }

    /**
     * @return \Osf\Validator\Siret
     */
    public static function newSiret(...$args)
    {
        return self::get('Siret', $args, false);
    }

    /**
     * @return \Osf\Validator\Siret
     */
    public static function getSiret(...$args)
    {
        return self::get('Siret', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorStaticValidator
     */
    public static function newStaticValidator(...$args)
    {
        return self::get('StaticValidator', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorStaticValidator
     */
    public static function getStaticValidator(...$args)
    {
        return self::get('StaticValidator', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorStep
     */
    public static function newStep(...$args)
    {
        return self::get('Step', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorStep
     */
    public static function getStep(...$args)
    {
        return self::get('Step', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorStringLength
     */
    public static function newStringLength(...$args)
    {
        return self::get('StringLength', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorStringLength
     */
    public static function getStringLength(...$args)
    {
        return self::get('StringLength', $args, true);
    }

    /**
     * @return \Osf\Validator\Telephone
     */
    public static function newTelephone(...$args)
    {
        return self::get('Telephone', $args, false);
    }

    /**
     * @return \Osf\Validator\Telephone
     */
    public static function getTelephone(...$args)
    {
        return self::get('Telephone', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorTimezone
     */
    public static function newTimezone(...$args)
    {
        return self::get('Timezone', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorTimezone
     */
    public static function getTimezone(...$args)
    {
        return self::get('Timezone', $args, true);
    }

    /**
     * @return \Osf\Validator\TvaIntra
     */
    public static function newTvaIntra(...$args)
    {
        return self::get('TvaIntra', $args, false);
    }

    /**
     * @return \Osf\Validator\TvaIntra
     */
    public static function getTvaIntra(...$args)
    {
        return self::get('TvaIntra', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorUri
     */
    public static function newUri(...$args)
    {
        return self::get('Uri', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorUri
     */
    public static function getUri(...$args)
    {
        return self::get('Uri', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorUuid
     */
    public static function newUuid(...$args)
    {
        return self::get('Uuid', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorUuid
     */
    public static function getUuid(...$args)
    {
        return self::get('Uuid', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorChain
     */
    public static function newValidatorChain(...$args)
    {
        return self::get('ValidatorChain', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorChain
     */
    public static function getValidatorChain(...$args)
    {
        return self::get('ValidatorChain', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorInterface
     */
    public static function newValidatorInterface(...$args)
    {
        return self::get('ValidatorInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorInterface
     */
    public static function getValidatorInterface(...$args)
    {
        return self::get('ValidatorInterface', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorPluginManager
     */
    public static function newValidatorPluginManager(...$args)
    {
        return self::get('ValidatorPluginManager', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorPluginManager
     */
    public static function getValidatorPluginManager(...$args)
    {
        return self::get('ValidatorPluginManager', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorPluginManagerAwareInterface
     */
    public static function newValidatorPluginManagerAwareInterface(...$args)
    {
        return self::get('ValidatorPluginManagerAwareInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorPluginManagerAwareInterface
     */
    public static function getValidatorPluginManagerAwareInterface(...$args)
    {
        return self::get('ValidatorPluginManagerAwareInterface', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorPluginManagerFactory
     */
    public static function newValidatorPluginManagerFactory(...$args)
    {
        return self::get('ValidatorPluginManagerFactory', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorPluginManagerFactory
     */
    public static function getValidatorPluginManagerFactory(...$args)
    {
        return self::get('ValidatorPluginManagerFactory', $args, true);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorProviderInterface
     */
    public static function newValidatorProviderInterface(...$args)
    {
        return self::get('ValidatorProviderInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Zend\ZendValidatorValidatorProviderInterface
     */
    public static function getValidatorProviderInterface(...$args)
    {
        return self::get('ValidatorProviderInterface', $args, true);
    }

}