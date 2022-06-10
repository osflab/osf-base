<?php
namespace Osf\Console;

/**
 * Console base helpers
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage controller
 */
class ConsoleHelper
{
    const COLOR_BEGIN_RED    = '[1;31m';
    const COLOR_BEGIN_GREEN  = '[1;32m';
    const COLOR_BEGIN_YELLOW = '[1;33m';
    const COLOR_BEGIN_BLUE   = '[1;34m';
    const COLOR_END          = '[0;0m';
    
    /**
     * Bootstrap of the controller
     */
    public static function run()
    {
        // Vérification des arguments
        if ($_SERVER['argc'] < 2) {
            self::helpAction();
            exit();
        }

        // Appel de la fonctionnalité demandée
        $method = $_SERVER['argv'][1] . 'Action';
        if (method_exists(static::getCurrentClass(), $method)) {
            $params = $_SERVER['argv'];
            array_shift($params);
            array_shift($params);
            call_user_func_array(array(static::getCurrentClass(), $method), $params);
        } else {
            self::displayError("command unknown", true);
        }
    }

    /**
     * Display an error.
     * @param string $errorMessage the error message to display
     * @param boolean $displayHelp display the help message (helpAction)
     * @param boolean $exit exit after displaying
     */
    protected static function displayError($errorMessage, $displayHelp = false, $exit = true)
    {
        echo "\n  " . self::red() . 'Error: ' . self::resetColor() . $errorMessage . "\n";
        if ($displayHelp) {
            self::helpAction();
        } else {
            echo "\n";
        }
        if ($exit) {
            exit(1);
        }
    }

    /**
     * Display this message
     */
    protected static function helpAction()
    {
        $class = new \ReflectionClass(static::getCurrentClass());
        $methods = $class->getMethods();
        $commands = array();
        $comments = array();
        $commandLen = 0;
        foreach ($methods as $method) {
            $methodName = (string) $method->getName();
            if (substr($methodName, -6, 6) == 'Action') {
                $command = substr($methodName, 0, strlen($methodName) - 6);
                $comment = preg_replace('#^[^a-zA-Z0-9_.()-]*(.*?)[^a-zA-Z0-9_.()-]*$#', '\1', $method->getDocComment());
                $commandLen = max(strlen($command), $commandLen);
                $commands[] = $command;
                $comments[] = $comment;
            }
        }
        $commandLen++;
        echo "\n  Usage: " . self::green() . basename($_SERVER['argv'][0]) . self::yellow() . ' <command>' . self::resetColor() . " [options]\n\n";
        foreach ($commands as $key => $command) {
            printf(self::yellow() . " %' " . $commandLen . 's' . self::resetColor() . ": %s\n", $command, $comments[$key]);
        }
        echo "\n";
    }

    /**
     * Display a message
     * @param string $message
     */
    protected static function display($message = null)
    {
        if ($message != null) {
            echo '-> ' . $message;
        }
        echo "\n";
    }
    
    /**
     * New action message, need to be ended by a "endActionXxx" method
     * @param string $message
     * @param int $lineLen
     * @return string
     */
    public static function beginActionMessage(string $message, int $lineLen = 80): string
    {
        return sprintf("- " . self::blue() . "%'.-" . ($lineLen - 11) . "s" . self::resetColor(), $message . ' ');
    }

    /**
     * The action fails
     * @return string
     */
    public static function endActionFail(): string
    {
        return ' [' . self::red() . 'FAILED' . self::resetColor() . "]\n";
    }

    /**
     * Success
     * @return string
     */
    public static function endActionOK(): string
    {
        return ' [' . self::green() . '  OK  ' . self::resetColor() . "]\n";
    }

    /**
     * Skipped
     * @return string
     */
    public static function endActionSkip(): string
    {
        return ' [' . self::yellow() . ' SKIP ' . self::resetColor() . "]\n";
    }
    
    /**
     * Change current color
     * @param string $colorPrefix
     * @return string
     */
    public static function color(string $colorPrefix): string
    {
        return chr(033) . $colorPrefix;
    }
    
    /**
     * Change to red color
     * @return string
     */
    public static function red(): string
    {
        return self::color(self::COLOR_BEGIN_RED);
    }
    
    /**
     * Change to green color
     * @return string
     */
    public static function green(): string
    {
        return self::color(self::COLOR_BEGIN_GREEN);
    }

    /**
     * Change to yellow color
     * @return string
     */
    public static function yellow(): string
    {
        return self::color(self::COLOR_BEGIN_YELLOW);
    }

    /**
     * Change to blue color
     * @return string
     */
    public static function blue(): string
    {
        return self::color(self::COLOR_BEGIN_BLUE);
    }
    
    /**
     * Reset current color
     * @return string
     */
    public static function resetColor(): string
    {
        return self::color(self::COLOR_END);
    }
    
    /**
     * Is it a command line context ?
     * @return bool
     */
    public static function isCli(): bool
    {
        return isset($_SERVER['argc']) && $_SERVER['argc'];
    }
}
