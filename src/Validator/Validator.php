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
        'Alnum' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nAlnum',
        'Alpha' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nAlpha',
        'Barcode' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcode',
        'BarcodeAdapterInterface' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeAdapterInterface',
        'BarcodeCodabar' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCodabar',
        'BarcodeCode128' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode128',
        'BarcodeCode25' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode25',
        'BarcodeCode25interleaved' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode25interleaved',
        'BarcodeCode39' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode39',
        'BarcodeCode39ext' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode39ext',
        'BarcodeCode93' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode93',
        'BarcodeCode93ext' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeCode93ext',
        'BarcodeEan12' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan12',
        'BarcodeEan13' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan13',
        'BarcodeEan14' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan14',
        'BarcodeEan18' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan18',
        'BarcodeEan2' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan2',
        'BarcodeEan5' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan5',
        'BarcodeEan8' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeEan8',
        'BarcodeGtin12' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeGtin12',
        'BarcodeGtin13' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeGtin13',
        'BarcodeGtin14' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeGtin14',
        'BarcodeIdentcode' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeIdentcode',
        'BarcodeIntelligentmail' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeIntelligentmail',
        'BarcodeIssn' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeIssn',
        'BarcodeItf14' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeItf14',
        'BarcodeLeitcode' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeLeitcode',
        'BarcodePlanet' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodePlanet',
        'BarcodePostnet' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodePostnet',
        'BarcodeRoyalmail' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeRoyalmail',
        'BarcodeSscc' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeSscc',
        'BarcodeUpca' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeUpca',
        'BarcodeUpce' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBarcodeUpce',
        'Between' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBetween',
        'Bic' => '\\Osf\\Validator\\Bic',
        'Bitwise' => '\\Osf\\Validator\\Laminas\\LaminasValidatorBitwise',
        'Callback' => '\\Osf\\Validator\\Laminas\\LaminasValidatorCallback',
        'ConfigProvider' => '\\Osf\\Validator\\Laminas\\LaminasValidatorConfigProvider',
        'CreditCard' => '\\Osf\\Validator\\Laminas\\LaminasValidatorCreditCard',
        'Csrf' => '\\Osf\\Validator\\Laminas\\LaminasValidatorCsrf',
        'Currency' => '\\Osf\\Validator\\Currency',
        'Date' => '\\Osf\\Validator\\Laminas\\LaminasValidatorDate',
        'DateStep' => '\\Osf\\Validator\\Laminas\\LaminasValidatorDateStep',
        'DateTime' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nDateTime',
        'Digits' => '\\Osf\\Validator\\Laminas\\LaminasValidatorDigits',
        'EmailAddress' => '\\Osf\\Validator\\EmailAddress',
        'Explode' => '\\Osf\\Validator\\Laminas\\LaminasValidatorExplode',
        'FileCount' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileCount',
        'FileCrc32' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileCrc32',
        'FileExcludeExtension' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileExcludeExtension',
        'FileExcludeMimeType' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileExcludeMimeType',
        'FileExists' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileExists',
        'FileExtension' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileExtension',
        'FileFilesSize' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileFilesSize',
        'FileHash' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileHash',
        'FileImageSize' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileImageSize',
        'FileIsCompressed' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileIsCompressed',
        'FileIsImage' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileIsImage',
        'FileMd5' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileMd5',
        'FileMimeType' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileMimeType',
        'FileNotExists' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileNotExists',
        'FileSha1' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileSha1',
        'FileSize' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileSize',
        'FileUpload' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileUpload',
        'FileUploadFile' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileUploadFile',
        'FileWordCount' => '\\Osf\\Validator\\Laminas\\LaminasValidatorFileWordCount',
        'Float' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nFloat',
        'GpsPoint' => '\\Osf\\Validator\\Laminas\\LaminasValidatorGpsPoint',
        'GreaterThan' => '\\Osf\\Validator\\Laminas\\LaminasValidatorGreaterThan',
        'Hex' => '\\Osf\\Validator\\Laminas\\LaminasValidatorHex',
        'Hostname' => '\\Osf\\Validator\\Laminas\\LaminasValidatorHostname',
        'HostnameBiz' => '\\Osf\\Validator\\Laminas\\LaminasValidatorHostnameBiz',
        'HostnameCn' => '\\Osf\\Validator\\Laminas\\LaminasValidatorHostnameCn',
        'HostnameCom' => '\\Osf\\Validator\\Laminas\\LaminasValidatorHostnameCom',
        'HostnameJp' => '\\Osf\\Validator\\Laminas\\LaminasValidatorHostnameJp',
        'Iban' => '\\Osf\\Validator\\Laminas\\LaminasValidatorIban',
        'IbanFr' => '\\Osf\\Validator\\IbanFr',
        'Identical' => '\\Osf\\Validator\\Laminas\\LaminasValidatorIdentical',
        'InArray' => '\\Osf\\Validator\\Laminas\\LaminasValidatorInArray',
        'Int' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nInt',
        'Ip' => '\\Osf\\Validator\\Laminas\\LaminasValidatorIp',
        'IsCountable' => '\\Osf\\Validator\\Laminas\\LaminasValidatorIsCountable',
        'IsFloat' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nIsFloat',
        'IsInstanceOf' => '\\Osf\\Validator\\Laminas\\LaminasValidatorIsInstanceOf',
        'IsInt' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nIsInt',
        'Isbn' => '\\Osf\\Validator\\Laminas\\LaminasValidatorIsbn',
        'LessThan' => '\\Osf\\Validator\\Laminas\\LaminasValidatorLessThan',
        'Module' => '\\Osf\\Validator\\Laminas\\LaminasValidatorModule',
        'NotEmpty' => '\\Osf\\Validator\\Laminas\\LaminasValidatorNotEmpty',
        'Password' => '\\Osf\\Validator\\Password',
        'Percentage' => '\\Osf\\Validator\\Percentage',
        'PhoneNumber' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nPhoneNumber',
        'PostCode' => '\\Osf\\Validator\\Laminas\\LaminasValidatorI18nPostCode',
        'PostalAddressBody' => '\\Osf\\Validator\\PostalAddressBody',
        'Regex' => '\\Osf\\Validator\\Laminas\\LaminasValidatorRegex',
        'Rna' => '\\Osf\\Validator\\Rna',
        'Siret' => '\\Osf\\Validator\\Siret',
        'StaticValidator' => '\\Osf\\Validator\\Laminas\\LaminasValidatorStaticValidator',
        'Step' => '\\Osf\\Validator\\Laminas\\LaminasValidatorStep',
        'StringLength' => '\\Osf\\Validator\\Laminas\\LaminasValidatorStringLength',
        'Telephone' => '\\Osf\\Validator\\Telephone',
        'Timezone' => '\\Osf\\Validator\\Laminas\\LaminasValidatorTimezone',
        'TvaIntra' => '\\Osf\\Validator\\TvaIntra',
        'Uri' => '\\Osf\\Validator\\Laminas\\LaminasValidatorUri',
        'Uuid' => '\\Osf\\Validator\\Laminas\\LaminasValidatorUuid',
        'ValidatorChain' => '\\Osf\\Validator\\Laminas\\LaminasValidatorValidatorChain',
        'ValidatorInterface' => '\\Osf\\Validator\\Laminas\\LaminasValidatorValidatorInterface',
        'ValidatorPluginManager' => '\\Osf\\Validator\\Laminas\\LaminasValidatorValidatorPluginManager',
        'ValidatorPluginManagerAwareInterface' => '\\Osf\\Validator\\Laminas\\LaminasValidatorValidatorPluginManagerAwareInterface',
        'ValidatorPluginManagerFactory' => '\\Osf\\Validator\\Laminas\\LaminasValidatorValidatorPluginManagerFactory',
        'ValidatorProviderInterface' => '\\Osf\\Validator\\Laminas\\LaminasValidatorValidatorProviderInterface',
    ];

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nAlnum
     */
    public static function newAlnum(...$args)
    {
        return self::get('Alnum', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nAlnum
     */
    public static function getAlnum(...$args)
    {
        return self::get('Alnum', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nAlpha
     */
    public static function newAlpha(...$args)
    {
        return self::get('Alpha', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nAlpha
     */
    public static function getAlpha(...$args)
    {
        return self::get('Alpha', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcode
     */
    public static function newBarcode(...$args)
    {
        return self::get('Barcode', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcode
     */
    public static function getBarcode(...$args)
    {
        return self::get('Barcode', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeAdapterInterface
     */
    public static function newBarcodeAdapterInterface(...$args)
    {
        return self::get('BarcodeAdapterInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeAdapterInterface
     */
    public static function getBarcodeAdapterInterface(...$args)
    {
        return self::get('BarcodeAdapterInterface', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCodabar
     */
    public static function newBarcodeCodabar(...$args)
    {
        return self::get('BarcodeCodabar', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCodabar
     */
    public static function getBarcodeCodabar(...$args)
    {
        return self::get('BarcodeCodabar', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode128
     */
    public static function newBarcodeCode128(...$args)
    {
        return self::get('BarcodeCode128', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode128
     */
    public static function getBarcodeCode128(...$args)
    {
        return self::get('BarcodeCode128', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode25
     */
    public static function newBarcodeCode25(...$args)
    {
        return self::get('BarcodeCode25', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode25
     */
    public static function getBarcodeCode25(...$args)
    {
        return self::get('BarcodeCode25', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode25interleaved
     */
    public static function newBarcodeCode25interleaved(...$args)
    {
        return self::get('BarcodeCode25interleaved', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode25interleaved
     */
    public static function getBarcodeCode25interleaved(...$args)
    {
        return self::get('BarcodeCode25interleaved', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode39
     */
    public static function newBarcodeCode39(...$args)
    {
        return self::get('BarcodeCode39', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode39
     */
    public static function getBarcodeCode39(...$args)
    {
        return self::get('BarcodeCode39', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode39ext
     */
    public static function newBarcodeCode39ext(...$args)
    {
        return self::get('BarcodeCode39ext', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode39ext
     */
    public static function getBarcodeCode39ext(...$args)
    {
        return self::get('BarcodeCode39ext', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode93
     */
    public static function newBarcodeCode93(...$args)
    {
        return self::get('BarcodeCode93', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode93
     */
    public static function getBarcodeCode93(...$args)
    {
        return self::get('BarcodeCode93', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode93ext
     */
    public static function newBarcodeCode93ext(...$args)
    {
        return self::get('BarcodeCode93ext', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeCode93ext
     */
    public static function getBarcodeCode93ext(...$args)
    {
        return self::get('BarcodeCode93ext', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan12
     */
    public static function newBarcodeEan12(...$args)
    {
        return self::get('BarcodeEan12', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan12
     */
    public static function getBarcodeEan12(...$args)
    {
        return self::get('BarcodeEan12', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan13
     */
    public static function newBarcodeEan13(...$args)
    {
        return self::get('BarcodeEan13', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan13
     */
    public static function getBarcodeEan13(...$args)
    {
        return self::get('BarcodeEan13', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan14
     */
    public static function newBarcodeEan14(...$args)
    {
        return self::get('BarcodeEan14', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan14
     */
    public static function getBarcodeEan14(...$args)
    {
        return self::get('BarcodeEan14', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan18
     */
    public static function newBarcodeEan18(...$args)
    {
        return self::get('BarcodeEan18', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan18
     */
    public static function getBarcodeEan18(...$args)
    {
        return self::get('BarcodeEan18', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan2
     */
    public static function newBarcodeEan2(...$args)
    {
        return self::get('BarcodeEan2', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan2
     */
    public static function getBarcodeEan2(...$args)
    {
        return self::get('BarcodeEan2', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan5
     */
    public static function newBarcodeEan5(...$args)
    {
        return self::get('BarcodeEan5', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan5
     */
    public static function getBarcodeEan5(...$args)
    {
        return self::get('BarcodeEan5', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan8
     */
    public static function newBarcodeEan8(...$args)
    {
        return self::get('BarcodeEan8', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeEan8
     */
    public static function getBarcodeEan8(...$args)
    {
        return self::get('BarcodeEan8', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeGtin12
     */
    public static function newBarcodeGtin12(...$args)
    {
        return self::get('BarcodeGtin12', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeGtin12
     */
    public static function getBarcodeGtin12(...$args)
    {
        return self::get('BarcodeGtin12', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeGtin13
     */
    public static function newBarcodeGtin13(...$args)
    {
        return self::get('BarcodeGtin13', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeGtin13
     */
    public static function getBarcodeGtin13(...$args)
    {
        return self::get('BarcodeGtin13', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeGtin14
     */
    public static function newBarcodeGtin14(...$args)
    {
        return self::get('BarcodeGtin14', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeGtin14
     */
    public static function getBarcodeGtin14(...$args)
    {
        return self::get('BarcodeGtin14', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeIdentcode
     */
    public static function newBarcodeIdentcode(...$args)
    {
        return self::get('BarcodeIdentcode', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeIdentcode
     */
    public static function getBarcodeIdentcode(...$args)
    {
        return self::get('BarcodeIdentcode', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeIntelligentmail
     */
    public static function newBarcodeIntelligentmail(...$args)
    {
        return self::get('BarcodeIntelligentmail', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeIntelligentmail
     */
    public static function getBarcodeIntelligentmail(...$args)
    {
        return self::get('BarcodeIntelligentmail', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeIssn
     */
    public static function newBarcodeIssn(...$args)
    {
        return self::get('BarcodeIssn', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeIssn
     */
    public static function getBarcodeIssn(...$args)
    {
        return self::get('BarcodeIssn', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeItf14
     */
    public static function newBarcodeItf14(...$args)
    {
        return self::get('BarcodeItf14', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeItf14
     */
    public static function getBarcodeItf14(...$args)
    {
        return self::get('BarcodeItf14', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeLeitcode
     */
    public static function newBarcodeLeitcode(...$args)
    {
        return self::get('BarcodeLeitcode', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeLeitcode
     */
    public static function getBarcodeLeitcode(...$args)
    {
        return self::get('BarcodeLeitcode', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodePlanet
     */
    public static function newBarcodePlanet(...$args)
    {
        return self::get('BarcodePlanet', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodePlanet
     */
    public static function getBarcodePlanet(...$args)
    {
        return self::get('BarcodePlanet', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodePostnet
     */
    public static function newBarcodePostnet(...$args)
    {
        return self::get('BarcodePostnet', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodePostnet
     */
    public static function getBarcodePostnet(...$args)
    {
        return self::get('BarcodePostnet', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeRoyalmail
     */
    public static function newBarcodeRoyalmail(...$args)
    {
        return self::get('BarcodeRoyalmail', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeRoyalmail
     */
    public static function getBarcodeRoyalmail(...$args)
    {
        return self::get('BarcodeRoyalmail', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeSscc
     */
    public static function newBarcodeSscc(...$args)
    {
        return self::get('BarcodeSscc', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeSscc
     */
    public static function getBarcodeSscc(...$args)
    {
        return self::get('BarcodeSscc', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeUpca
     */
    public static function newBarcodeUpca(...$args)
    {
        return self::get('BarcodeUpca', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeUpca
     */
    public static function getBarcodeUpca(...$args)
    {
        return self::get('BarcodeUpca', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeUpce
     */
    public static function newBarcodeUpce(...$args)
    {
        return self::get('BarcodeUpce', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBarcodeUpce
     */
    public static function getBarcodeUpce(...$args)
    {
        return self::get('BarcodeUpce', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBetween
     */
    public static function newBetween(...$args)
    {
        return self::get('Between', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBetween
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
     * @return \Osf\Validator\Laminas\LaminasValidatorBitwise
     */
    public static function newBitwise(...$args)
    {
        return self::get('Bitwise', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorBitwise
     */
    public static function getBitwise(...$args)
    {
        return self::get('Bitwise', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorCallback
     */
    public static function newCallback(...$args)
    {
        return self::get('Callback', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorCallback
     */
    public static function getCallback(...$args)
    {
        return self::get('Callback', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorConfigProvider
     */
    public static function newConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorConfigProvider
     */
    public static function getConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorCreditCard
     */
    public static function newCreditCard(...$args)
    {
        return self::get('CreditCard', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorCreditCard
     */
    public static function getCreditCard(...$args)
    {
        return self::get('CreditCard', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorCsrf
     */
    public static function newCsrf(...$args)
    {
        return self::get('Csrf', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorCsrf
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
     * @return \Osf\Validator\Laminas\LaminasValidatorDate
     */
    public static function newDate(...$args)
    {
        return self::get('Date', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorDate
     */
    public static function getDate(...$args)
    {
        return self::get('Date', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorDateStep
     */
    public static function newDateStep(...$args)
    {
        return self::get('DateStep', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorDateStep
     */
    public static function getDateStep(...$args)
    {
        return self::get('DateStep', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nDateTime
     */
    public static function newDateTime(...$args)
    {
        return self::get('DateTime', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nDateTime
     */
    public static function getDateTime(...$args)
    {
        return self::get('DateTime', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorDigits
     */
    public static function newDigits(...$args)
    {
        return self::get('Digits', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorDigits
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
     * @return \Osf\Validator\Laminas\LaminasValidatorExplode
     */
    public static function newExplode(...$args)
    {
        return self::get('Explode', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorExplode
     */
    public static function getExplode(...$args)
    {
        return self::get('Explode', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileCount
     */
    public static function newFileCount(...$args)
    {
        return self::get('FileCount', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileCount
     */
    public static function getFileCount(...$args)
    {
        return self::get('FileCount', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileCrc32
     */
    public static function newFileCrc32(...$args)
    {
        return self::get('FileCrc32', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileCrc32
     */
    public static function getFileCrc32(...$args)
    {
        return self::get('FileCrc32', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExcludeExtension
     */
    public static function newFileExcludeExtension(...$args)
    {
        return self::get('FileExcludeExtension', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExcludeExtension
     */
    public static function getFileExcludeExtension(...$args)
    {
        return self::get('FileExcludeExtension', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExcludeMimeType
     */
    public static function newFileExcludeMimeType(...$args)
    {
        return self::get('FileExcludeMimeType', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExcludeMimeType
     */
    public static function getFileExcludeMimeType(...$args)
    {
        return self::get('FileExcludeMimeType', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExists
     */
    public static function newFileExists(...$args)
    {
        return self::get('FileExists', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExists
     */
    public static function getFileExists(...$args)
    {
        return self::get('FileExists', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExtension
     */
    public static function newFileExtension(...$args)
    {
        return self::get('FileExtension', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileExtension
     */
    public static function getFileExtension(...$args)
    {
        return self::get('FileExtension', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileFilesSize
     */
    public static function newFileFilesSize(...$args)
    {
        return self::get('FileFilesSize', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileFilesSize
     */
    public static function getFileFilesSize(...$args)
    {
        return self::get('FileFilesSize', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileHash
     */
    public static function newFileHash(...$args)
    {
        return self::get('FileHash', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileHash
     */
    public static function getFileHash(...$args)
    {
        return self::get('FileHash', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileImageSize
     */
    public static function newFileImageSize(...$args)
    {
        return self::get('FileImageSize', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileImageSize
     */
    public static function getFileImageSize(...$args)
    {
        return self::get('FileImageSize', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileIsCompressed
     */
    public static function newFileIsCompressed(...$args)
    {
        return self::get('FileIsCompressed', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileIsCompressed
     */
    public static function getFileIsCompressed(...$args)
    {
        return self::get('FileIsCompressed', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileIsImage
     */
    public static function newFileIsImage(...$args)
    {
        return self::get('FileIsImage', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileIsImage
     */
    public static function getFileIsImage(...$args)
    {
        return self::get('FileIsImage', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileMd5
     */
    public static function newFileMd5(...$args)
    {
        return self::get('FileMd5', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileMd5
     */
    public static function getFileMd5(...$args)
    {
        return self::get('FileMd5', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileMimeType
     */
    public static function newFileMimeType(...$args)
    {
        return self::get('FileMimeType', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileMimeType
     */
    public static function getFileMimeType(...$args)
    {
        return self::get('FileMimeType', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileNotExists
     */
    public static function newFileNotExists(...$args)
    {
        return self::get('FileNotExists', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileNotExists
     */
    public static function getFileNotExists(...$args)
    {
        return self::get('FileNotExists', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileSha1
     */
    public static function newFileSha1(...$args)
    {
        return self::get('FileSha1', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileSha1
     */
    public static function getFileSha1(...$args)
    {
        return self::get('FileSha1', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileSize
     */
    public static function newFileSize(...$args)
    {
        return self::get('FileSize', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileSize
     */
    public static function getFileSize(...$args)
    {
        return self::get('FileSize', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileUpload
     */
    public static function newFileUpload(...$args)
    {
        return self::get('FileUpload', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileUpload
     */
    public static function getFileUpload(...$args)
    {
        return self::get('FileUpload', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileUploadFile
     */
    public static function newFileUploadFile(...$args)
    {
        return self::get('FileUploadFile', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileUploadFile
     */
    public static function getFileUploadFile(...$args)
    {
        return self::get('FileUploadFile', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileWordCount
     */
    public static function newFileWordCount(...$args)
    {
        return self::get('FileWordCount', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorFileWordCount
     */
    public static function getFileWordCount(...$args)
    {
        return self::get('FileWordCount', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nFloat
     */
    public static function newFloat(...$args)
    {
        return self::get('Float', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nFloat
     */
    public static function getFloat(...$args)
    {
        return self::get('Float', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorGpsPoint
     */
    public static function newGpsPoint(...$args)
    {
        return self::get('GpsPoint', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorGpsPoint
     */
    public static function getGpsPoint(...$args)
    {
        return self::get('GpsPoint', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorGreaterThan
     */
    public static function newGreaterThan(...$args)
    {
        return self::get('GreaterThan', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorGreaterThan
     */
    public static function getGreaterThan(...$args)
    {
        return self::get('GreaterThan', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHex
     */
    public static function newHex(...$args)
    {
        return self::get('Hex', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHex
     */
    public static function getHex(...$args)
    {
        return self::get('Hex', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostname
     */
    public static function newHostname(...$args)
    {
        return self::get('Hostname', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostname
     */
    public static function getHostname(...$args)
    {
        return self::get('Hostname', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameBiz
     */
    public static function newHostnameBiz(...$args)
    {
        return self::get('HostnameBiz', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameBiz
     */
    public static function getHostnameBiz(...$args)
    {
        return self::get('HostnameBiz', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameCn
     */
    public static function newHostnameCn(...$args)
    {
        return self::get('HostnameCn', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameCn
     */
    public static function getHostnameCn(...$args)
    {
        return self::get('HostnameCn', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameCom
     */
    public static function newHostnameCom(...$args)
    {
        return self::get('HostnameCom', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameCom
     */
    public static function getHostnameCom(...$args)
    {
        return self::get('HostnameCom', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameJp
     */
    public static function newHostnameJp(...$args)
    {
        return self::get('HostnameJp', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorHostnameJp
     */
    public static function getHostnameJp(...$args)
    {
        return self::get('HostnameJp', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIban
     */
    public static function newIban(...$args)
    {
        return self::get('Iban', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIban
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
     * @return \Osf\Validator\Laminas\LaminasValidatorIdentical
     */
    public static function newIdentical(...$args)
    {
        return self::get('Identical', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIdentical
     */
    public static function getIdentical(...$args)
    {
        return self::get('Identical', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorInArray
     */
    public static function newInArray(...$args)
    {
        return self::get('InArray', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorInArray
     */
    public static function getInArray(...$args)
    {
        return self::get('InArray', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nInt
     */
    public static function newInt(...$args)
    {
        return self::get('Int', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nInt
     */
    public static function getInt(...$args)
    {
        return self::get('Int', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIp
     */
    public static function newIp(...$args)
    {
        return self::get('Ip', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIp
     */
    public static function getIp(...$args)
    {
        return self::get('Ip', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIsCountable
     */
    public static function newIsCountable(...$args)
    {
        return self::get('IsCountable', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIsCountable
     */
    public static function getIsCountable(...$args)
    {
        return self::get('IsCountable', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nIsFloat
     */
    public static function newIsFloat(...$args)
    {
        return self::get('IsFloat', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nIsFloat
     */
    public static function getIsFloat(...$args)
    {
        return self::get('IsFloat', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIsInstanceOf
     */
    public static function newIsInstanceOf(...$args)
    {
        return self::get('IsInstanceOf', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIsInstanceOf
     */
    public static function getIsInstanceOf(...$args)
    {
        return self::get('IsInstanceOf', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nIsInt
     */
    public static function newIsInt(...$args)
    {
        return self::get('IsInt', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nIsInt
     */
    public static function getIsInt(...$args)
    {
        return self::get('IsInt', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIsbn
     */
    public static function newIsbn(...$args)
    {
        return self::get('Isbn', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorIsbn
     */
    public static function getIsbn(...$args)
    {
        return self::get('Isbn', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorLessThan
     */
    public static function newLessThan(...$args)
    {
        return self::get('LessThan', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorLessThan
     */
    public static function getLessThan(...$args)
    {
        return self::get('LessThan', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorModule
     */
    public static function newModule(...$args)
    {
        return self::get('Module', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorModule
     */
    public static function getModule(...$args)
    {
        return self::get('Module', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorNotEmpty
     */
    public static function newNotEmpty(...$args)
    {
        return self::get('NotEmpty', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorNotEmpty
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
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nPhoneNumber
     */
    public static function newPhoneNumber(...$args)
    {
        return self::get('PhoneNumber', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nPhoneNumber
     */
    public static function getPhoneNumber(...$args)
    {
        return self::get('PhoneNumber', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nPostCode
     */
    public static function newPostCode(...$args)
    {
        return self::get('PostCode', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorI18nPostCode
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
     * @return \Osf\Validator\Laminas\LaminasValidatorRegex
     */
    public static function newRegex(...$args)
    {
        return self::get('Regex', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorRegex
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
     * @return \Osf\Validator\Laminas\LaminasValidatorStaticValidator
     */
    public static function newStaticValidator(...$args)
    {
        return self::get('StaticValidator', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorStaticValidator
     */
    public static function getStaticValidator(...$args)
    {
        return self::get('StaticValidator', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorStep
     */
    public static function newStep(...$args)
    {
        return self::get('Step', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorStep
     */
    public static function getStep(...$args)
    {
        return self::get('Step', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorStringLength
     */
    public static function newStringLength(...$args)
    {
        return self::get('StringLength', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorStringLength
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
     * @return \Osf\Validator\Laminas\LaminasValidatorTimezone
     */
    public static function newTimezone(...$args)
    {
        return self::get('Timezone', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorTimezone
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
     * @return \Osf\Validator\Laminas\LaminasValidatorUri
     */
    public static function newUri(...$args)
    {
        return self::get('Uri', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorUri
     */
    public static function getUri(...$args)
    {
        return self::get('Uri', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorUuid
     */
    public static function newUuid(...$args)
    {
        return self::get('Uuid', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorUuid
     */
    public static function getUuid(...$args)
    {
        return self::get('Uuid', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorChain
     */
    public static function newValidatorChain(...$args)
    {
        return self::get('ValidatorChain', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorChain
     */
    public static function getValidatorChain(...$args)
    {
        return self::get('ValidatorChain', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorInterface
     */
    public static function newValidatorInterface(...$args)
    {
        return self::get('ValidatorInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorInterface
     */
    public static function getValidatorInterface(...$args)
    {
        return self::get('ValidatorInterface', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorPluginManager
     */
    public static function newValidatorPluginManager(...$args)
    {
        return self::get('ValidatorPluginManager', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorPluginManager
     */
    public static function getValidatorPluginManager(...$args)
    {
        return self::get('ValidatorPluginManager', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorPluginManagerAwareInterface
     */
    public static function newValidatorPluginManagerAwareInterface(...$args)
    {
        return self::get('ValidatorPluginManagerAwareInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorPluginManagerAwareInterface
     */
    public static function getValidatorPluginManagerAwareInterface(...$args)
    {
        return self::get('ValidatorPluginManagerAwareInterface', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorPluginManagerFactory
     */
    public static function newValidatorPluginManagerFactory(...$args)
    {
        return self::get('ValidatorPluginManagerFactory', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorPluginManagerFactory
     */
    public static function getValidatorPluginManagerFactory(...$args)
    {
        return self::get('ValidatorPluginManagerFactory', $args, true);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorProviderInterface
     */
    public static function newValidatorProviderInterface(...$args)
    {
        return self::get('ValidatorProviderInterface', $args, false);
    }

    /**
     * @return \Osf\Validator\Laminas\LaminasValidatorValidatorProviderInterface
     */
    public static function getValidatorProviderInterface(...$args)
    {
        return self::get('ValidatorProviderInterface', $args, true);
    }

}