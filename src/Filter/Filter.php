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
        'BaseName' => '\\Osf\\Filter\\Zend\\ZendFilterBaseName',
        'Blacklist' => '\\Osf\\Filter\\Zend\\ZendFilterBlacklist',
        'Boolean' => '\\Osf\\Filter\\Zend\\ZendFilterBoolean',
        'Callback' => '\\Osf\\Filter\\Zend\\ZendFilterCallback',
        'CleanPhrase' => '\\Osf\\Filter\\CleanPhrase',
        'Compress' => '\\Osf\\Filter\\Zend\\ZendFilterCompress',
        'CompressBz2' => '\\Osf\\Filter\\Zend\\ZendFilterCompressBz2',
        'CompressCompressionAlgorithmInterface' => '\\Osf\\Filter\\Zend\\ZendFilterCompressCompressionAlgorithmInterface',
        'CompressGz' => '\\Osf\\Filter\\Zend\\ZendFilterCompressGz',
        'CompressLzf' => '\\Osf\\Filter\\Zend\\ZendFilterCompressLzf',
        'CompressRar' => '\\Osf\\Filter\\Zend\\ZendFilterCompressRar',
        'CompressSnappy' => '\\Osf\\Filter\\Zend\\ZendFilterCompressSnappy',
        'CompressTar' => '\\Osf\\Filter\\Zend\\ZendFilterCompressTar',
        'CompressZip' => '\\Osf\\Filter\\Zend\\ZendFilterCompressZip',
        'ConfigProvider' => '\\Osf\\Filter\\Zend\\ZendFilterConfigProvider',
        'Currency' => '\\Osf\\Filter\\Currency',
        'DataUnitFormatter' => '\\Osf\\Filter\\Zend\\ZendFilterDataUnitFormatter',
        'DateSelect' => '\\Osf\\Filter\\Zend\\ZendFilterDateSelect',
        'DateTimeFormatter' => '\\Osf\\Filter\\Zend\\ZendFilterDateTimeFormatter',
        'DateTimeSelect' => '\\Osf\\Filter\\Zend\\ZendFilterDateTimeSelect',
        'DateWire' => '\\Osf\\Filter\\DateWire',
        'Decompress' => '\\Osf\\Filter\\Zend\\ZendFilterDecompress',
        'Decrypt' => '\\Osf\\Filter\\Zend\\ZendFilterDecrypt',
        'Digits' => '\\Osf\\Filter\\Zend\\ZendFilterDigits',
        'Dir' => '\\Osf\\Filter\\Zend\\ZendFilterDir',
        'Encrypt' => '\\Osf\\Filter\\Zend\\ZendFilterEncrypt',
        'EncryptBlockCipher' => '\\Osf\\Filter\\Zend\\ZendFilterEncryptBlockCipher',
        'EncryptEncryptionAlgorithmInterface' => '\\Osf\\Filter\\Zend\\ZendFilterEncryptEncryptionAlgorithmInterface',
        'EncryptOpenssl' => '\\Osf\\Filter\\Zend\\ZendFilterEncryptOpenssl',
        'FileDecrypt' => '\\Osf\\Filter\\Zend\\ZendFilterFileDecrypt',
        'FileEncrypt' => '\\Osf\\Filter\\Zend\\ZendFilterFileEncrypt',
        'FileLowerCase' => '\\Osf\\Filter\\Zend\\ZendFilterFileLowerCase',
        'FileRename' => '\\Osf\\Filter\\Zend\\ZendFilterFileRename',
        'FileRenameUpload' => '\\Osf\\Filter\\Zend\\ZendFilterFileRenameUpload',
        'FileUpperCase' => '\\Osf\\Filter\\Zend\\ZendFilterFileUpperCase',
        'FilterChain' => '\\Osf\\Filter\\Zend\\ZendFilterFilterChain',
        'FilterInterface' => '\\Osf\\Filter\\Zend\\ZendFilterFilterInterface',
        'FilterPluginManager' => '\\Osf\\Filter\\Zend\\ZendFilterFilterPluginManager',
        'FilterPluginManagerFactory' => '\\Osf\\Filter\\Zend\\ZendFilterFilterPluginManagerFactory',
        'HtmlEntities' => '\\Osf\\Filter\\Zend\\ZendFilterHtmlEntities',
        'Inflector' => '\\Osf\\Filter\\Zend\\ZendFilterInflector',
        'Int' => '\\Osf\\Filter\\Zend\\ZendFilterInt',
        'MaskTrim' => '\\Osf\\Filter\\MaskTrim',
        'Module' => '\\Osf\\Filter\\Zend\\ZendFilterModule',
        'MonthSelect' => '\\Osf\\Filter\\Zend\\ZendFilterMonthSelect',
        'Null' => '\\Osf\\Filter\\Zend\\ZendFilterNull',
        'Percentage' => '\\Osf\\Filter\\Percentage',
        'PregReplace' => '\\Osf\\Filter\\Zend\\ZendFilterPregReplace',
        'RealPath' => '\\Osf\\Filter\\Zend\\ZendFilterRealPath',
        'RemoveSpaces' => '\\Osf\\Filter\\RemoveSpaces',
        'StaticFilter' => '\\Osf\\Filter\\Zend\\ZendFilterStaticFilter',
        'StringToLower' => '\\Osf\\Filter\\Zend\\ZendFilterStringToLower',
        'StringToUpper' => '\\Osf\\Filter\\Zend\\ZendFilterStringToUpper',
        'StringTrim' => '\\Osf\\Filter\\Zend\\ZendFilterStringTrim',
        'StripNewlines' => '\\Osf\\Filter\\Zend\\ZendFilterStripNewlines',
        'StripTags' => '\\Osf\\Filter\\Zend\\ZendFilterStripTags',
        'Telephone' => '\\Osf\\Filter\\Telephone',
        'ToInt' => '\\Osf\\Filter\\Zend\\ZendFilterToInt',
        'ToNull' => '\\Osf\\Filter\\Zend\\ZendFilterToNull',
        'UcPhrase' => '\\Osf\\Filter\\UcPhrase',
        'UpperCaseWords' => '\\Osf\\Filter\\Zend\\ZendFilterUpperCaseWords',
        'UriNormalize' => '\\Osf\\Filter\\Zend\\ZendFilterUriNormalize',
        'Whitelist' => '\\Osf\\Filter\\Zend\\ZendFilterWhitelist',
        'WordCamelCaseToDash' => '\\Osf\\Filter\\Zend\\ZendFilterWordCamelCaseToDash',
        'WordCamelCaseToSeparator' => '\\Osf\\Filter\\Zend\\ZendFilterWordCamelCaseToSeparator',
        'WordCamelCaseToUnderscore' => '\\Osf\\Filter\\Zend\\ZendFilterWordCamelCaseToUnderscore',
        'WordDashToCamelCase' => '\\Osf\\Filter\\Zend\\ZendFilterWordDashToCamelCase',
        'WordDashToSeparator' => '\\Osf\\Filter\\Zend\\ZendFilterWordDashToSeparator',
        'WordDashToUnderscore' => '\\Osf\\Filter\\Zend\\ZendFilterWordDashToUnderscore',
        'WordSeparatorToCamelCase' => '\\Osf\\Filter\\Zend\\ZendFilterWordSeparatorToCamelCase',
        'WordSeparatorToDash' => '\\Osf\\Filter\\Zend\\ZendFilterWordSeparatorToDash',
        'WordSeparatorToSeparator' => '\\Osf\\Filter\\Zend\\ZendFilterWordSeparatorToSeparator',
        'WordUnderscoreToCamelCase' => '\\Osf\\Filter\\Zend\\ZendFilterWordUnderscoreToCamelCase',
        'WordUnderscoreToDash' => '\\Osf\\Filter\\Zend\\ZendFilterWordUnderscoreToDash',
        'WordUnderscoreToSeparator' => '\\Osf\\Filter\\Zend\\ZendFilterWordUnderscoreToSeparator',
        'WordUnderscoreToStudlyCase' => '\\Osf\\Filter\\Zend\\ZendFilterWordUnderscoreToStudlyCase',
    ];

    /**
     * @return \Osf\Filter\Zend\ZendFilterBaseName
     */
    public static function newBaseName(...$args)
    {
        return self::get('BaseName', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterBaseName
     */
    public static function getBaseName(...$args)
    {
        return self::get('BaseName', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterBlacklist
     */
    public static function newBlacklist(...$args)
    {
        return self::get('Blacklist', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterBlacklist
     */
    public static function getBlacklist(...$args)
    {
        return self::get('Blacklist', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterBoolean
     */
    public static function newBoolean(...$args)
    {
        return self::get('Boolean', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterBoolean
     */
    public static function getBoolean(...$args)
    {
        return self::get('Boolean', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCallback
     */
    public static function newCallback(...$args)
    {
        return self::get('Callback', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCallback
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
     * @return \Osf\Filter\Zend\ZendFilterCompress
     */
    public static function newCompress(...$args)
    {
        return self::get('Compress', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompress
     */
    public static function getCompress(...$args)
    {
        return self::get('Compress', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressBz2
     */
    public static function newCompressBz2(...$args)
    {
        return self::get('CompressBz2', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressBz2
     */
    public static function getCompressBz2(...$args)
    {
        return self::get('CompressBz2', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressCompressionAlgorithmInterface
     */
    public static function newCompressCompressionAlgorithmInterface(...$args)
    {
        return self::get('CompressCompressionAlgorithmInterface', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressCompressionAlgorithmInterface
     */
    public static function getCompressCompressionAlgorithmInterface(...$args)
    {
        return self::get('CompressCompressionAlgorithmInterface', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressGz
     */
    public static function newCompressGz(...$args)
    {
        return self::get('CompressGz', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressGz
     */
    public static function getCompressGz(...$args)
    {
        return self::get('CompressGz', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressLzf
     */
    public static function newCompressLzf(...$args)
    {
        return self::get('CompressLzf', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressLzf
     */
    public static function getCompressLzf(...$args)
    {
        return self::get('CompressLzf', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressRar
     */
    public static function newCompressRar(...$args)
    {
        return self::get('CompressRar', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressRar
     */
    public static function getCompressRar(...$args)
    {
        return self::get('CompressRar', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressSnappy
     */
    public static function newCompressSnappy(...$args)
    {
        return self::get('CompressSnappy', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressSnappy
     */
    public static function getCompressSnappy(...$args)
    {
        return self::get('CompressSnappy', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressTar
     */
    public static function newCompressTar(...$args)
    {
        return self::get('CompressTar', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressTar
     */
    public static function getCompressTar(...$args)
    {
        return self::get('CompressTar', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressZip
     */
    public static function newCompressZip(...$args)
    {
        return self::get('CompressZip', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterCompressZip
     */
    public static function getCompressZip(...$args)
    {
        return self::get('CompressZip', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterConfigProvider
     */
    public static function newConfigProvider(...$args)
    {
        return self::get('ConfigProvider', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterConfigProvider
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
     * @return \Osf\Filter\Zend\ZendFilterDataUnitFormatter
     */
    public static function newDataUnitFormatter(...$args)
    {
        return self::get('DataUnitFormatter', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDataUnitFormatter
     */
    public static function getDataUnitFormatter(...$args)
    {
        return self::get('DataUnitFormatter', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDateSelect
     */
    public static function newDateSelect(...$args)
    {
        return self::get('DateSelect', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDateSelect
     */
    public static function getDateSelect(...$args)
    {
        return self::get('DateSelect', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDateTimeFormatter
     */
    public static function newDateTimeFormatter(...$args)
    {
        return self::get('DateTimeFormatter', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDateTimeFormatter
     */
    public static function getDateTimeFormatter(...$args)
    {
        return self::get('DateTimeFormatter', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDateTimeSelect
     */
    public static function newDateTimeSelect(...$args)
    {
        return self::get('DateTimeSelect', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDateTimeSelect
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
     * @return \Osf\Filter\Zend\ZendFilterDecompress
     */
    public static function newDecompress(...$args)
    {
        return self::get('Decompress', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDecompress
     */
    public static function getDecompress(...$args)
    {
        return self::get('Decompress', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDecrypt
     */
    public static function newDecrypt(...$args)
    {
        return self::get('Decrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDecrypt
     */
    public static function getDecrypt(...$args)
    {
        return self::get('Decrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDigits
     */
    public static function newDigits(...$args)
    {
        return self::get('Digits', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDigits
     */
    public static function getDigits(...$args)
    {
        return self::get('Digits', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDir
     */
    public static function newDir(...$args)
    {
        return self::get('Dir', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterDir
     */
    public static function getDir(...$args)
    {
        return self::get('Dir', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncrypt
     */
    public static function newEncrypt(...$args)
    {
        return self::get('Encrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncrypt
     */
    public static function getEncrypt(...$args)
    {
        return self::get('Encrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncryptBlockCipher
     */
    public static function newEncryptBlockCipher(...$args)
    {
        return self::get('EncryptBlockCipher', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncryptBlockCipher
     */
    public static function getEncryptBlockCipher(...$args)
    {
        return self::get('EncryptBlockCipher', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncryptEncryptionAlgorithmInterface
     */
    public static function newEncryptEncryptionAlgorithmInterface(...$args)
    {
        return self::get('EncryptEncryptionAlgorithmInterface', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncryptEncryptionAlgorithmInterface
     */
    public static function getEncryptEncryptionAlgorithmInterface(...$args)
    {
        return self::get('EncryptEncryptionAlgorithmInterface', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncryptOpenssl
     */
    public static function newEncryptOpenssl(...$args)
    {
        return self::get('EncryptOpenssl', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterEncryptOpenssl
     */
    public static function getEncryptOpenssl(...$args)
    {
        return self::get('EncryptOpenssl', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileDecrypt
     */
    public static function newFileDecrypt(...$args)
    {
        return self::get('FileDecrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileDecrypt
     */
    public static function getFileDecrypt(...$args)
    {
        return self::get('FileDecrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileEncrypt
     */
    public static function newFileEncrypt(...$args)
    {
        return self::get('FileEncrypt', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileEncrypt
     */
    public static function getFileEncrypt(...$args)
    {
        return self::get('FileEncrypt', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileLowerCase
     */
    public static function newFileLowerCase(...$args)
    {
        return self::get('FileLowerCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileLowerCase
     */
    public static function getFileLowerCase(...$args)
    {
        return self::get('FileLowerCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileRename
     */
    public static function newFileRename(...$args)
    {
        return self::get('FileRename', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileRename
     */
    public static function getFileRename(...$args)
    {
        return self::get('FileRename', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileRenameUpload
     */
    public static function newFileRenameUpload(...$args)
    {
        return self::get('FileRenameUpload', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileRenameUpload
     */
    public static function getFileRenameUpload(...$args)
    {
        return self::get('FileRenameUpload', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileUpperCase
     */
    public static function newFileUpperCase(...$args)
    {
        return self::get('FileUpperCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFileUpperCase
     */
    public static function getFileUpperCase(...$args)
    {
        return self::get('FileUpperCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterChain
     */
    public static function newFilterChain(...$args)
    {
        return self::get('FilterChain', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterChain
     */
    public static function getFilterChain(...$args)
    {
        return self::get('FilterChain', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterInterface
     */
    public static function newFilterInterface(...$args)
    {
        return self::get('FilterInterface', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterInterface
     */
    public static function getFilterInterface(...$args)
    {
        return self::get('FilterInterface', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterPluginManager
     */
    public static function newFilterPluginManager(...$args)
    {
        return self::get('FilterPluginManager', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterPluginManager
     */
    public static function getFilterPluginManager(...$args)
    {
        return self::get('FilterPluginManager', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterPluginManagerFactory
     */
    public static function newFilterPluginManagerFactory(...$args)
    {
        return self::get('FilterPluginManagerFactory', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterFilterPluginManagerFactory
     */
    public static function getFilterPluginManagerFactory(...$args)
    {
        return self::get('FilterPluginManagerFactory', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterHtmlEntities
     */
    public static function newHtmlEntities(...$args)
    {
        return self::get('HtmlEntities', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterHtmlEntities
     */
    public static function getHtmlEntities(...$args)
    {
        return self::get('HtmlEntities', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterInflector
     */
    public static function newInflector(...$args)
    {
        return self::get('Inflector', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterInflector
     */
    public static function getInflector(...$args)
    {
        return self::get('Inflector', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterInt
     */
    public static function newInt(...$args)
    {
        return self::get('Int', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterInt
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
     * @return \Osf\Filter\Zend\ZendFilterModule
     */
    public static function newModule(...$args)
    {
        return self::get('Module', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterModule
     */
    public static function getModule(...$args)
    {
        return self::get('Module', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterMonthSelect
     */
    public static function newMonthSelect(...$args)
    {
        return self::get('MonthSelect', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterMonthSelect
     */
    public static function getMonthSelect(...$args)
    {
        return self::get('MonthSelect', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterNull
     */
    public static function newNull(...$args)
    {
        return self::get('Null', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterNull
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
     * @return \Osf\Filter\Zend\ZendFilterPregReplace
     */
    public static function newPregReplace(...$args)
    {
        return self::get('PregReplace', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterPregReplace
     */
    public static function getPregReplace(...$args)
    {
        return self::get('PregReplace', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterRealPath
     */
    public static function newRealPath(...$args)
    {
        return self::get('RealPath', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterRealPath
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
     * @return \Osf\Filter\Zend\ZendFilterStaticFilter
     */
    public static function newStaticFilter(...$args)
    {
        return self::get('StaticFilter', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStaticFilter
     */
    public static function getStaticFilter(...$args)
    {
        return self::get('StaticFilter', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStringToLower
     */
    public static function newStringToLower(...$args)
    {
        return self::get('StringToLower', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStringToLower
     */
    public static function getStringToLower(...$args)
    {
        return self::get('StringToLower', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStringToUpper
     */
    public static function newStringToUpper(...$args)
    {
        return self::get('StringToUpper', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStringToUpper
     */
    public static function getStringToUpper(...$args)
    {
        return self::get('StringToUpper', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStringTrim
     */
    public static function newStringTrim(...$args)
    {
        return self::get('StringTrim', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStringTrim
     */
    public static function getStringTrim(...$args)
    {
        return self::get('StringTrim', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStripNewlines
     */
    public static function newStripNewlines(...$args)
    {
        return self::get('StripNewlines', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStripNewlines
     */
    public static function getStripNewlines(...$args)
    {
        return self::get('StripNewlines', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStripTags
     */
    public static function newStripTags(...$args)
    {
        return self::get('StripTags', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterStripTags
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
     * @return \Osf\Filter\Zend\ZendFilterToInt
     */
    public static function newToInt(...$args)
    {
        return self::get('ToInt', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterToInt
     */
    public static function getToInt(...$args)
    {
        return self::get('ToInt', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterToNull
     */
    public static function newToNull(...$args)
    {
        return self::get('ToNull', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterToNull
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
     * @return \Osf\Filter\Zend\ZendFilterUpperCaseWords
     */
    public static function newUpperCaseWords(...$args)
    {
        return self::get('UpperCaseWords', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterUpperCaseWords
     */
    public static function getUpperCaseWords(...$args)
    {
        return self::get('UpperCaseWords', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterUriNormalize
     */
    public static function newUriNormalize(...$args)
    {
        return self::get('UriNormalize', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterUriNormalize
     */
    public static function getUriNormalize(...$args)
    {
        return self::get('UriNormalize', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWhitelist
     */
    public static function newWhitelist(...$args)
    {
        return self::get('Whitelist', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWhitelist
     */
    public static function getWhitelist(...$args)
    {
        return self::get('Whitelist', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordCamelCaseToDash
     */
    public static function newWordCamelCaseToDash(...$args)
    {
        return self::get('WordCamelCaseToDash', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordCamelCaseToDash
     */
    public static function getWordCamelCaseToDash(...$args)
    {
        return self::get('WordCamelCaseToDash', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordCamelCaseToSeparator
     */
    public static function newWordCamelCaseToSeparator(...$args)
    {
        return self::get('WordCamelCaseToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordCamelCaseToSeparator
     */
    public static function getWordCamelCaseToSeparator(...$args)
    {
        return self::get('WordCamelCaseToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordCamelCaseToUnderscore
     */
    public static function newWordCamelCaseToUnderscore(...$args)
    {
        return self::get('WordCamelCaseToUnderscore', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordCamelCaseToUnderscore
     */
    public static function getWordCamelCaseToUnderscore(...$args)
    {
        return self::get('WordCamelCaseToUnderscore', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordDashToCamelCase
     */
    public static function newWordDashToCamelCase(...$args)
    {
        return self::get('WordDashToCamelCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordDashToCamelCase
     */
    public static function getWordDashToCamelCase(...$args)
    {
        return self::get('WordDashToCamelCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordDashToSeparator
     */
    public static function newWordDashToSeparator(...$args)
    {
        return self::get('WordDashToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordDashToSeparator
     */
    public static function getWordDashToSeparator(...$args)
    {
        return self::get('WordDashToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordDashToUnderscore
     */
    public static function newWordDashToUnderscore(...$args)
    {
        return self::get('WordDashToUnderscore', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordDashToUnderscore
     */
    public static function getWordDashToUnderscore(...$args)
    {
        return self::get('WordDashToUnderscore', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordSeparatorToCamelCase
     */
    public static function newWordSeparatorToCamelCase(...$args)
    {
        return self::get('WordSeparatorToCamelCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordSeparatorToCamelCase
     */
    public static function getWordSeparatorToCamelCase(...$args)
    {
        return self::get('WordSeparatorToCamelCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordSeparatorToDash
     */
    public static function newWordSeparatorToDash(...$args)
    {
        return self::get('WordSeparatorToDash', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordSeparatorToDash
     */
    public static function getWordSeparatorToDash(...$args)
    {
        return self::get('WordSeparatorToDash', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordSeparatorToSeparator
     */
    public static function newWordSeparatorToSeparator(...$args)
    {
        return self::get('WordSeparatorToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordSeparatorToSeparator
     */
    public static function getWordSeparatorToSeparator(...$args)
    {
        return self::get('WordSeparatorToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToCamelCase
     */
    public static function newWordUnderscoreToCamelCase(...$args)
    {
        return self::get('WordUnderscoreToCamelCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToCamelCase
     */
    public static function getWordUnderscoreToCamelCase(...$args)
    {
        return self::get('WordUnderscoreToCamelCase', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToDash
     */
    public static function newWordUnderscoreToDash(...$args)
    {
        return self::get('WordUnderscoreToDash', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToDash
     */
    public static function getWordUnderscoreToDash(...$args)
    {
        return self::get('WordUnderscoreToDash', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToSeparator
     */
    public static function newWordUnderscoreToSeparator(...$args)
    {
        return self::get('WordUnderscoreToSeparator', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToSeparator
     */
    public static function getWordUnderscoreToSeparator(...$args)
    {
        return self::get('WordUnderscoreToSeparator', $args, true);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToStudlyCase
     */
    public static function newWordUnderscoreToStudlyCase(...$args)
    {
        return self::get('WordUnderscoreToStudlyCase', $args, false);
    }

    /**
     * @return \Osf\Filter\Zend\ZendFilterWordUnderscoreToStudlyCase
     */
    public static function getWordUnderscoreToStudlyCase(...$args)
    {
        return self::get('WordUnderscoreToStudlyCase', $args, true);
    }

}