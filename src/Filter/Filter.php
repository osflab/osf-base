<?php
namespace Osf\Filter;

/**
 * Filters general builder
 *
 * This class is generated, do not edit.
 *
 * @version 1.0
 * @author Guillaume Ponçon - OpenStates Framework PHP Generator
 * @since OSF 3.0.0
 * @package osf
 * @subpackage generated
 */
abstract class Filter extends \Osf\Generator\AbstractBuilder
{

    protected static $classes = [
        'BaseName' => '\\Osf\\Filter\\Laminas\\LaminasFilterBaseName',
        'Blacklist' => '\\Osf\\Filter\\Laminas\\LaminasFilterBlacklist',
        'Boolean' => '\\Osf\\Filter\\Laminas\\LaminasFilterBoolean',
        'Callback' => '\\Osf\\Filter\\Laminas\\LaminasFilterCallback',
        'CleanPhrase' => '\\Osf\\Filter\\CleanPhrase',
        'Compress' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompress',
        'CompressBz2' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressBz2',
        'CompressCompressionAlgorithmInterface' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressCompressionAlgorithmInterface',
        'CompressGz' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressGz',
        'CompressLzf' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressLzf',
        'CompressRar' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressRar',
        'CompressSnappy' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressSnappy',
        'CompressTar' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressTar',
        'CompressZip' => '\\Osf\\Filter\\Laminas\\LaminasFilterCompressZip',
        'ConfigProvider' => '\\Osf\\Filter\\Laminas\\LaminasFilterConfigProvider',
        'Currency' => '\\Osf\\Filter\\Currency',
        'DataUnitFormatter' => '\\Osf\\Filter\\Laminas\\LaminasFilterDataUnitFormatter',
        'DateSelect' => '\\Osf\\Filter\\Laminas\\LaminasFilterDateSelect',
        'DateTimeFormatter' => '\\Osf\\Filter\\Laminas\\LaminasFilterDateTimeFormatter',
        'DateTimeSelect' => '\\Osf\\Filter\\Laminas\\LaminasFilterDateTimeSelect',
        'DateWire' => '\\Osf\\Filter\\DateWire',
        'Decompress' => '\\Osf\\Filter\\Laminas\\LaminasFilterDecompress',
        'Decrypt' => '\\Osf\\Filter\\Laminas\\LaminasFilterDecrypt',
        'Digits' => '\\Osf\\Filter\\Laminas\\LaminasFilterDigits',
        'Dir' => '\\Osf\\Filter\\Laminas\\LaminasFilterDir',
        'Encrypt' => '\\Osf\\Filter\\Laminas\\LaminasFilterEncrypt',
        'EncryptBlockCipher' => '\\Osf\\Filter\\Laminas\\LaminasFilterEncryptBlockCipher',
        'EncryptEncryptionAlgorithmInterface' => '\\Osf\\Filter\\Laminas\\LaminasFilterEncryptEncryptionAlgorithmInterface',
        'EncryptOpenssl' => '\\Osf\\Filter\\Laminas\\LaminasFilterEncryptOpenssl',
        'FileDecrypt' => '\\Osf\\Filter\\Laminas\\LaminasFilterFileDecrypt',
        'FileEncrypt' => '\\Osf\\Filter\\Laminas\\LaminasFilterFileEncrypt',
        'FileLowerCase' => '\\Osf\\Filter\\Laminas\\LaminasFilterFileLowerCase',
        'FileRename' => '\\Osf\\Filter\\Laminas\\LaminasFilterFileRename',
        'FileRenameUpload' => '\\Osf\\Filter\\Laminas\\LaminasFilterFileRenameUpload',
        'FileUpperCase' => '\\Osf\\Filter\\Laminas\\LaminasFilterFileUpperCase',
        'FilterChain' => '\\Osf\\Filter\\Laminas\\LaminasFilterFilterChain',
        'FilterInterface' => '\\Osf\\Filter\\Laminas\\LaminasFilterFilterInterface',
        'FilterPluginManager' => '\\Osf\\Filter\\Laminas\\LaminasFilterFilterPluginManager',
        'FilterPluginManagerFactory' => '\\Osf\\Filter\\Laminas\\LaminasFilterFilterPluginManagerFactory',
        'HtmlEntities' => '\\Osf\\Filter\\Laminas\\LaminasFilterHtmlEntities',
        'Inflector' => '\\Osf\\Filter\\Laminas\\LaminasFilterInflector',
        'Int' => '\\Osf\\Filter\\Laminas\\LaminasFilterInt',
        'MaskTrim' => '\\Osf\\Filter\\MaskTrim',
        'Module' => '\\Osf\\Filter\\Laminas\\LaminasFilterModule',
        'MonthSelect' => '\\Osf\\Filter\\Laminas\\LaminasFilterMonthSelect',
        'Null' => '\\Osf\\Filter\\Laminas\\LaminasFilterNull',
        'Percentage' => '\\Osf\\Filter\\Percentage',
        'PregReplace' => '\\Osf\\Filter\\Laminas\\LaminasFilterPregReplace',
        'RealPath' => '\\Osf\\Filter\\Laminas\\LaminasFilterRealPath',
        'RemoveSpaces' => '\\Osf\\Filter\\RemoveSpaces',
        'StaticFilter' => '\\Osf\\Filter\\Laminas\\LaminasFilterStaticFilter',
        'StringToLower' => '\\Osf\\Filter\\Laminas\\LaminasFilterStringToLower',
        'StringToUpper' => '\\Osf\\Filter\\Laminas\\LaminasFilterStringToUpper',
        'StringTrim' => '\\Osf\\Filter\\Laminas\\LaminasFilterStringTrim',
        'StripNewlines' => '\\Osf\\Filter\\Laminas\\LaminasFilterStripNewlines',
        'StripTags' => '\\Osf\\Filter\\Laminas\\LaminasFilterStripTags',
        'Telephone' => '\\Osf\\Filter\\Telephone',
        'ToInt' => '\\Osf\\Filter\\Laminas\\LaminasFilterToInt',
        'ToNull' => '\\Osf\\Filter\\Laminas\\LaminasFilterToNull',
        'UcPhrase' => '\\Osf\\Filter\\UcPhrase',
        'UpperCaseWords' => '\\Osf\\Filter\\Laminas\\LaminasFilterUpperCaseWords',
        'UriNormalize' => '\\Osf\\Filter\\Laminas\\LaminasFilterUriNormalize',
        'Whitelist' => '\\Osf\\Filter\\Laminas\\LaminasFilterWhitelist',
        'WordCamelCaseToDash' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordCamelCaseToDash',
        'WordCamelCaseToSeparator' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordCamelCaseToSeparator',
        'WordCamelCaseToUnderscore' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordCamelCaseToUnderscore',
        'WordDashToCamelCase' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordDashToCamelCase',
        'WordDashToSeparator' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordDashToSeparator',
        'WordDashToUnderscore' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordDashToUnderscore',
        'WordSeparatorToCamelCase' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordSeparatorToCamelCase',
        'WordSeparatorToDash' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordSeparatorToDash',
        'WordSeparatorToSeparator' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordSeparatorToSeparator',
        'WordUnderscoreToCamelCase' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordUnderscoreToCamelCase',
        'WordUnderscoreToDash' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordUnderscoreToDash',
        'WordUnderscoreToSeparator' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordUnderscoreToSeparator',
        'WordUnderscoreToStudlyCase' => '\\Osf\\Filter\\Laminas\\LaminasFilterWordUnderscoreToStudlyCase',
    ];

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterBaseName
     */
    public static function newBaseName(...$args)
    {
        return self::get('BaseName', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterBaseName
     */
    public static function getBaseName(...$args)
    {
        return self::get('BaseName', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterBlacklist
     */
    public static function newBlacklist(...$args)
    {
        return self::get('Blacklist', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterBlacklist
     */
    public static function getBlacklist(...$args)
    {
        return self::get('Blacklist', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterBoolean
     */
    public static function newBoolean(...$args)
    {
        return self::get('Boolean', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterBoolean
     */
    public static function getBoolean(...$args)
    {
        return self::get('Boolean', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCallback
     */
    public static function newCallback(...$args)
    {
        return self::get('Callback', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCallback
     */
    public static function getCallback(...$args)
    {
        return self::get('Callback', $args, true);
    }

    /**
     * @return \Osf\Filter\CleanPhrase
     */
    public static function newCleanPhrase(...$args)
    {
        return self::get('CleanPhrase', $args, false);
    }

    /**
     * @return \Osf\Filter\CleanPhrase
     */
    public static function getCleanPhrase(...$args)
    {
        return self::get('CleanPhrase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompress
     */
    public static function newCompress(...$args)
    {
        return self::get('Compress', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompress
     */
    public static function getCompress(...$args)
    {
        return self::get('Compress', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressBz2
     */
    public static function newCompressBz2(...$args)
    {
        return self::get('CompressBz2', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressBz2
     */
    public static function getCompressBz2(...$args)
    {
        return self::get('CompressBz2', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressCompressionAlgorithmInterface
     */
    public static function newCompressCompressionAlgorithmInterface(...$args)
    {
        return self::get('CompressCompressionAlgorithmInterface', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressCompressionAlgorithmInterface
     */
    public static function getCompressCompressionAlgorithmInterface(...$args)
    {
        return self::get('CompressCompressionAlgorithmInterface', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressGz
     */
    public static function newCompressGz(...$args)
    {
        return self::get('CompressGz', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressGz
     */
    public static function getCompressGz(...$args)
    {
        return self::get('CompressGz', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressLzf
     */
    public static function newCompressLzf(...$args)
    {
        return self::get('CompressLzf', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressLzf
     */
    public static function getCompressLzf(...$args)
    {
        return self::get('CompressLzf', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressRar
     */
    public static function newCompressRar(...$args)
    {
        return self::get('CompressRar', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressRar
     */
    public static function getCompressRar(...$args)
    {
        return self::get('CompressRar', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressSnappy
     */
    public static function newCompressSnappy(...$args)
    {
        return self::get('CompressSnappy', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressSnappy
     */
    public static function getCompressSnappy(...$args)
    {
        return self::get('CompressSnappy', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressTar
     */
    public static function newCompressTar(...$args)
    {
        return self::get('CompressTar', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressTar
     */
    public static function getCompressTar(...$args)
    {
        return self::get('CompressTar', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressZip
     */
    public static function newCompressZip(...$args)
    {
        return self::get('CompressZip', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterCompressZip
     */
    public static function getCompressZip(...$args)
    {
        return self::get('CompressZip', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterConfigProvider
     */
    public static function newConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterConfigProvider
     */
    public static function getConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, true);
    }

    /**
     * @return \Osf\Filter\Currency
     */
    public static function newCurrency(...$args)
    {
        return self::get('Currency', $args, false);
    }

    /**
     * @return \Osf\Filter\Currency
     */
    public static function getCurrency(...$args)
    {
        return self::get('Currency', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDataUnitFormatter
     */
    public static function newDataUnitFormatter(...$args)
    {
        return self::get('DataUnitFormatter', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDataUnitFormatter
     */
    public static function getDataUnitFormatter(...$args)
    {
        return self::get('DataUnitFormatter', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDateSelect
     */
    public static function newDateSelect(...$args)
    {
        return self::get('DateSelect', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDateSelect
     */
    public static function getDateSelect(...$args)
    {
        return self::get('DateSelect', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDateTimeFormatter
     */
    public static function newDateTimeFormatter(...$args)
    {
        return self::get('DateTimeFormatter', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDateTimeFormatter
     */
    public static function getDateTimeFormatter(...$args)
    {
        return self::get('DateTimeFormatter', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDateTimeSelect
     */
    public static function newDateTimeSelect(...$args)
    {
        return self::get('DateTimeSelect', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDateTimeSelect
     */
    public static function getDateTimeSelect(...$args)
    {
        return self::get('DateTimeSelect', $args, true);
    }

    /**
     * @return \Osf\Filter\DateWire
     */
    public static function newDateWire(...$args)
    {
        return self::get('DateWire', $args, false);
    }

    /**
     * @return \Osf\Filter\DateWire
     */
    public static function getDateWire(...$args)
    {
        return self::get('DateWire', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDecompress
     */
    public static function newDecompress(...$args)
    {
        return self::get('Decompress', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDecompress
     */
    public static function getDecompress(...$args)
    {
        return self::get('Decompress', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDecrypt
     */
    public static function newDecrypt(...$args)
    {
        return self::get('Decrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDecrypt
     */
    public static function getDecrypt(...$args)
    {
        return self::get('Decrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDigits
     */
    public static function newDigits(...$args)
    {
        return self::get('Digits', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDigits
     */
    public static function getDigits(...$args)
    {
        return self::get('Digits', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDir
     */
    public static function newDir(...$args)
    {
        return self::get('Dir', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterDir
     */
    public static function getDir(...$args)
    {
        return self::get('Dir', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncrypt
     */
    public static function newEncrypt(...$args)
    {
        return self::get('Encrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncrypt
     */
    public static function getEncrypt(...$args)
    {
        return self::get('Encrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncryptBlockCipher
     */
    public static function newEncryptBlockCipher(...$args)
    {
        return self::get('EncryptBlockCipher', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncryptBlockCipher
     */
    public static function getEncryptBlockCipher(...$args)
    {
        return self::get('EncryptBlockCipher', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncryptEncryptionAlgorithmInterface
     */
    public static function newEncryptEncryptionAlgorithmInterface(...$args)
    {
        return self::get('EncryptEncryptionAlgorithmInterface', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncryptEncryptionAlgorithmInterface
     */
    public static function getEncryptEncryptionAlgorithmInterface(...$args)
    {
        return self::get('EncryptEncryptionAlgorithmInterface', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncryptOpenssl
     */
    public static function newEncryptOpenssl(...$args)
    {
        return self::get('EncryptOpenssl', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterEncryptOpenssl
     */
    public static function getEncryptOpenssl(...$args)
    {
        return self::get('EncryptOpenssl', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileDecrypt
     */
    public static function newFileDecrypt(...$args)
    {
        return self::get('FileDecrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileDecrypt
     */
    public static function getFileDecrypt(...$args)
    {
        return self::get('FileDecrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileEncrypt
     */
    public static function newFileEncrypt(...$args)
    {
        return self::get('FileEncrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileEncrypt
     */
    public static function getFileEncrypt(...$args)
    {
        return self::get('FileEncrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileLowerCase
     */
    public static function newFileLowerCase(...$args)
    {
        return self::get('FileLowerCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileLowerCase
     */
    public static function getFileLowerCase(...$args)
    {
        return self::get('FileLowerCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileRename
     */
    public static function newFileRename(...$args)
    {
        return self::get('FileRename', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileRename
     */
    public static function getFileRename(...$args)
    {
        return self::get('FileRename', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileRenameUpload
     */
    public static function newFileRenameUpload(...$args)
    {
        return self::get('FileRenameUpload', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileRenameUpload
     */
    public static function getFileRenameUpload(...$args)
    {
        return self::get('FileRenameUpload', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileUpperCase
     */
    public static function newFileUpperCase(...$args)
    {
        return self::get('FileUpperCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFileUpperCase
     */
    public static function getFileUpperCase(...$args)
    {
        return self::get('FileUpperCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterChain
     */
    public static function newFilterChain(...$args)
    {
        return self::get('FilterChain', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterChain
     */
    public static function getFilterChain(...$args)
    {
        return self::get('FilterChain', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterInterface
     */
    public static function newFilterInterface(...$args)
    {
        return self::get('FilterInterface', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterInterface
     */
    public static function getFilterInterface(...$args)
    {
        return self::get('FilterInterface', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterPluginManager
     */
    public static function newFilterPluginManager(...$args)
    {
        return self::get('FilterPluginManager', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterPluginManager
     */
    public static function getFilterPluginManager(...$args)
    {
        return self::get('FilterPluginManager', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterPluginManagerFactory
     */
    public static function newFilterPluginManagerFactory(...$args)
    {
        return self::get('FilterPluginManagerFactory', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterFilterPluginManagerFactory
     */
    public static function getFilterPluginManagerFactory(...$args)
    {
        return self::get('FilterPluginManagerFactory', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterHtmlEntities
     */
    public static function newHtmlEntities(...$args)
    {
        return self::get('HtmlEntities', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterHtmlEntities
     */
    public static function getHtmlEntities(...$args)
    {
        return self::get('HtmlEntities', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterInflector
     */
    public static function newInflector(...$args)
    {
        return self::get('Inflector', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterInflector
     */
    public static function getInflector(...$args)
    {
        return self::get('Inflector', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterInt
     */
    public static function newInt(...$args)
    {
        return self::get('Int', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterInt
     */
    public static function getInt(...$args)
    {
        return self::get('Int', $args, true);
    }

    /**
     * @return \Osf\Filter\MaskTrim
     */
    public static function newMaskTrim(...$args)
    {
        return self::get('MaskTrim', $args, false);
    }

    /**
     * @return \Osf\Filter\MaskTrim
     */
    public static function getMaskTrim(...$args)
    {
        return self::get('MaskTrim', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterModule
     */
    public static function newModule(...$args)
    {
        return self::get('Module', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterModule
     */
    public static function getModule(...$args)
    {
        return self::get('Module', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterMonthSelect
     */
    public static function newMonthSelect(...$args)
    {
        return self::get('MonthSelect', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterMonthSelect
     */
    public static function getMonthSelect(...$args)
    {
        return self::get('MonthSelect', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterNull
     */
    public static function newNull(...$args)
    {
        return self::get('Null', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterNull
     */
    public static function getNull(...$args)
    {
        return self::get('Null', $args, true);
    }

    /**
     * @return \Osf\Filter\Percentage
     */
    public static function newPercentage(...$args)
    {
        return self::get('Percentage', $args, false);
    }

    /**
     * @return \Osf\Filter\Percentage
     */
    public static function getPercentage(...$args)
    {
        return self::get('Percentage', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterPregReplace
     */
    public static function newPregReplace(...$args)
    {
        return self::get('PregReplace', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterPregReplace
     */
    public static function getPregReplace(...$args)
    {
        return self::get('PregReplace', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterRealPath
     */
    public static function newRealPath(...$args)
    {
        return self::get('RealPath', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterRealPath
     */
    public static function getRealPath(...$args)
    {
        return self::get('RealPath', $args, true);
    }

    /**
     * @return \Osf\Filter\RemoveSpaces
     */
    public static function newRemoveSpaces(...$args)
    {
        return self::get('RemoveSpaces', $args, false);
    }

    /**
     * @return \Osf\Filter\RemoveSpaces
     */
    public static function getRemoveSpaces(...$args)
    {
        return self::get('RemoveSpaces', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStaticFilter
     */
    public static function newStaticFilter(...$args)
    {
        return self::get('StaticFilter', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStaticFilter
     */
    public static function getStaticFilter(...$args)
    {
        return self::get('StaticFilter', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStringToLower
     */
    public static function newStringToLower(...$args)
    {
        return self::get('StringToLower', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStringToLower
     */
    public static function getStringToLower(...$args)
    {
        return self::get('StringToLower', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStringToUpper
     */
    public static function newStringToUpper(...$args)
    {
        return self::get('StringToUpper', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStringToUpper
     */
    public static function getStringToUpper(...$args)
    {
        return self::get('StringToUpper', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStringTrim
     */
    public static function newStringTrim(...$args)
    {
        return self::get('StringTrim', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStringTrim
     */
    public static function getStringTrim(...$args)
    {
        return self::get('StringTrim', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStripNewlines
     */
    public static function newStripNewlines(...$args)
    {
        return self::get('StripNewlines', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStripNewlines
     */
    public static function getStripNewlines(...$args)
    {
        return self::get('StripNewlines', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStripTags
     */
    public static function newStripTags(...$args)
    {
        return self::get('StripTags', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterStripTags
     */
    public static function getStripTags(...$args)
    {
        return self::get('StripTags', $args, true);
    }

    /**
     * @return \Osf\Filter\Telephone
     */
    public static function newTelephone(...$args)
    {
        return self::get('Telephone', $args, false);
    }

    /**
     * @return \Osf\Filter\Telephone
     */
    public static function getTelephone(...$args)
    {
        return self::get('Telephone', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterToInt
     */
    public static function newToInt(...$args)
    {
        return self::get('ToInt', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterToInt
     */
    public static function getToInt(...$args)
    {
        return self::get('ToInt', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterToNull
     */
    public static function newToNull(...$args)
    {
        return self::get('ToNull', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterToNull
     */
    public static function getToNull(...$args)
    {
        return self::get('ToNull', $args, true);
    }

    /**
     * @return \Osf\Filter\UcPhrase
     */
    public static function newUcPhrase(...$args)
    {
        return self::get('UcPhrase', $args, false);
    }

    /**
     * @return \Osf\Filter\UcPhrase
     */
    public static function getUcPhrase(...$args)
    {
        return self::get('UcPhrase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterUpperCaseWords
     */
    public static function newUpperCaseWords(...$args)
    {
        return self::get('UpperCaseWords', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterUpperCaseWords
     */
    public static function getUpperCaseWords(...$args)
    {
        return self::get('UpperCaseWords', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterUriNormalize
     */
    public static function newUriNormalize(...$args)
    {
        return self::get('UriNormalize', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterUriNormalize
     */
    public static function getUriNormalize(...$args)
    {
        return self::get('UriNormalize', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWhitelist
     */
    public static function newWhitelist(...$args)
    {
        return self::get('Whitelist', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWhitelist
     */
    public static function getWhitelist(...$args)
    {
        return self::get('Whitelist', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordCamelCaseToDash
     */
    public static function newWordCamelCaseToDash(...$args)
    {
        return self::get('WordCamelCaseToDash', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordCamelCaseToDash
     */
    public static function getWordCamelCaseToDash(...$args)
    {
        return self::get('WordCamelCaseToDash', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordCamelCaseToSeparator
     */
    public static function newWordCamelCaseToSeparator(...$args)
    {
        return self::get('WordCamelCaseToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordCamelCaseToSeparator
     */
    public static function getWordCamelCaseToSeparator(...$args)
    {
        return self::get('WordCamelCaseToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordCamelCaseToUnderscore
     */
    public static function newWordCamelCaseToUnderscore(...$args)
    {
        return self::get('WordCamelCaseToUnderscore', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordCamelCaseToUnderscore
     */
    public static function getWordCamelCaseToUnderscore(...$args)
    {
        return self::get('WordCamelCaseToUnderscore', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordDashToCamelCase
     */
    public static function newWordDashToCamelCase(...$args)
    {
        return self::get('WordDashToCamelCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordDashToCamelCase
     */
    public static function getWordDashToCamelCase(...$args)
    {
        return self::get('WordDashToCamelCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordDashToSeparator
     */
    public static function newWordDashToSeparator(...$args)
    {
        return self::get('WordDashToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordDashToSeparator
     */
    public static function getWordDashToSeparator(...$args)
    {
        return self::get('WordDashToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordDashToUnderscore
     */
    public static function newWordDashToUnderscore(...$args)
    {
        return self::get('WordDashToUnderscore', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordDashToUnderscore
     */
    public static function getWordDashToUnderscore(...$args)
    {
        return self::get('WordDashToUnderscore', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordSeparatorToCamelCase
     */
    public static function newWordSeparatorToCamelCase(...$args)
    {
        return self::get('WordSeparatorToCamelCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordSeparatorToCamelCase
     */
    public static function getWordSeparatorToCamelCase(...$args)
    {
        return self::get('WordSeparatorToCamelCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordSeparatorToDash
     */
    public static function newWordSeparatorToDash(...$args)
    {
        return self::get('WordSeparatorToDash', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordSeparatorToDash
     */
    public static function getWordSeparatorToDash(...$args)
    {
        return self::get('WordSeparatorToDash', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordSeparatorToSeparator
     */
    public static function newWordSeparatorToSeparator(...$args)
    {
        return self::get('WordSeparatorToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordSeparatorToSeparator
     */
    public static function getWordSeparatorToSeparator(...$args)
    {
        return self::get('WordSeparatorToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToCamelCase
     */
    public static function newWordUnderscoreToCamelCase(...$args)
    {
        return self::get('WordUnderscoreToCamelCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToCamelCase
     */
    public static function getWordUnderscoreToCamelCase(...$args)
    {
        return self::get('WordUnderscoreToCamelCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToDash
     */
    public static function newWordUnderscoreToDash(...$args)
    {
        return self::get('WordUnderscoreToDash', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToDash
     */
    public static function getWordUnderscoreToDash(...$args)
    {
        return self::get('WordUnderscoreToDash', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToSeparator
     */
    public static function newWordUnderscoreToSeparator(...$args)
    {
        return self::get('WordUnderscoreToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToSeparator
     */
    public static function getWordUnderscoreToSeparator(...$args)
    {
        return self::get('WordUnderscoreToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToStudlyCase
     */
    public static function newWordUnderscoreToStudlyCase(...$args)
    {
        return self::get('WordUnderscoreToStudlyCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Laminas\LaminasFilterWordUnderscoreToStudlyCase
     */
    public static function getWordUnderscoreToStudlyCase(...$args)
    {
        return self::get('WordUnderscoreToStudlyCase', $args, true);
    }

}