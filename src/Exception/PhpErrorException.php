<?php

/*
 * This file is part of the OpenStates Framework (osf) package.
 * (c) Guillaume Ponçon <guillaume.poncon@openstates.com>
 * For the full copyright and license information, please read the LICENSE file distributed with the project.
 */

namespace Osf\Exception;

use Osf\Exception\PhpError\{
    ErrorException,
    WarningException,
    ParseException,
    NoticeException,
    CoreErrorException,
    CoreWarningException,
    CompileErrorException,
    UserErrorException,
    UserWarningException,
    UserNoticeException,
    StrictException,
    RecoverableErrorException,
    DeprecatedException,
    UserDeprecatedException
};

/**
 * Exception from error handler
 * 
 * This handler is usefull for an application based on OpenStates Framework
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class PhpErrorException extends \Exception
{
    protected static $lastError;
    protected static $logErrors;
    protected static $triggerApplication;
    
    public static function startHandler(
            bool $logErrors = true, 
            bool $triggerApplication = true, 
            ?int $handledErrors = null)
    {
        $handledErrors = $handledErrors ?? E_ERROR | E_USER_ERROR | E_USER_WARNING;
        
        self::$logErrors = $logErrors;
        self::$triggerApplication = $triggerApplication;
        return set_error_handler(function ($severity, $msg, $file, $line, array $context)
        {
            if (0 === error_reporting()) {
                return false;
            }
            
            $message = $msg . ' at ' . $file . '(' . $line . ')';
            switch($severity)
            {
                case E_ERROR:               $e = new ErrorException            ($message); break;
                case E_WARNING:             $e = new WarningException          ($message); break;
                case E_PARSE:               $e = new ParseException            ($message); break;
                case E_NOTICE:              $e = new NoticeException           ($message); break;
                case E_CORE_ERROR:          $e = new CoreErrorException        ($message); break;
                case E_CORE_WARNING:        $e = new CoreWarningException      ($message); break;
                case E_COMPILE_ERROR:       $e = new CompileErrorException     ($message); break;
                case E_COMPILE_WARNING:     $e = new CoreWarningException      ($message); break;
                case E_USER_ERROR:          $e = new UserErrorException        ($message); break;
                case E_USER_WARNING:        $e = new UserWarningException      ($message); break;
                case E_USER_NOTICE:         $e = new UserNoticeException       ($message); break;
                case E_STRICT:              $e = new StrictException           ($message); break;
                case E_RECOVERABLE_ERROR:   $e = new RecoverableErrorException ($message); break;
                case E_DEPRECATED:          $e = new DeprecatedException       ($message); break;
                case E_USER_DEPRECATED:     $e = new UserDeprecatedException   ($message); break;
            }
            
            self::$lastError = $e;
            self::$logErrors && self::logError($e);
            self::$triggerApplication && self::triggerApplication($e);
        }, $handledErrors);
    }
    
    /**
     * Get the latest generated error
     * @return \Osf\Exception\PhpErrorException|null
     */
    public static function getLastError(): ?PhpErrorException
    {
        return self::$lastError;
    }
    
    /**
     * Error -> Osf\Log\LogProxy
     * @param self $e
     * @return void
     */
    protected static function logError(self $e): void
    {
        if (class_exists('\Osf\Log\LogProxy')) {
            $msg = get_class($e) . ' : ' . $e->getMessage();
            try {
                \Osf\Log\LogProxy::log($msg, $e->getLogLevel(), 'PHPERR', $e->getTraceAsString());
            } catch (\Exception $ex) {
                file_put_contents(sys_get_temp_dir() . '/undisplayed-sma-errors.log', date('Ymd-His-') . $ex->getMessage(), FILE_APPEND);
            }
        }
    }
    
    /**
     * Transmit error to the application context
     * @param self $e
     * @return void
     */
    protected static function triggerApplication(self $e): void
    {
        if (class_exists('\Osf\Application\OsfApplication')) {
            if (\Osf\Application\OsfApplication::isDevelopment()) {
                \Osf\Exception\Error::displayException($e);
            } else {
                if (\Osf\Application\OsfApplication::isStaging()) {
                    $err = sprintf(__("%s. Details in log file."), $e->getMessage());
                    echo \Osf\Container\OsfContainer::getViewHelper()->alert(__("Error detected"), $err)->statusWarning();
                }
            }
        }
    }
    
    /**
     * Default log level
     * @return string
     */
    public function getLogLevel(): string
    {
        return \Osf\Log\LogProxy::LEVEL_ERROR;
    }
}
