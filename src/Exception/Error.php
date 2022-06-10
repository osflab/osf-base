<?php
namespace Osf\Exception;

use Osf\Controller\Cli;
use Osf\Exception\PhpErrorException;

/**
 * Common trigger errors
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage error
 */
class Error
{
    /**
     * Transform errors to exceptions
     * @return type
     */
    public static function startErrorHandler()
    {
        return PhpErrorException::startHandler();
    }
    
    /**
     * Non fatal error to display for testers and developers
     * @param string $msg
     * @task [ERROR] mettre dans un logger en production
     */
    public static function notice(string $msg)
    {
        trigger_error($msg, E_USER_NOTICE);
    }
    
    /**
     * Quick warning
     * @param string $msg
     * @task [ERROR] mettre dans un logger en production
     */
    public static function warning(string $msg)
    {
        trigger_error($msg, E_USER_WARNING);
    }
    
    /**
     * Display an exception if impossible to display it via EventController::error
     * @param \Exception $e
     * @task [ERROR] log d'erreur, remontée d'information propre
     */
    public static function displayException(\Exception $e)
    {
        $cli = Cli::isCli();
        if ($cli) {
            $err  = '-> Exception: ' . $e->getMessage() . "\n";
            $err .= '   ' . $e->getFile() . ' (' . $e->getLine() . ")\n";
            foreach ($e->getTrace() as $key => $item) {
                if (isset($item['file']) && $item['file']) {
                    $err .= '   ' . trim($item['file']) . ' (' . $item['line'] . '): ' . $item['function'] . "()\n";
                }
            }
            echo $err;
        } else {
            $html = '<div class="box box-danger"><div class="box-header with-border"><h3 class="box-title">Exception détectée via le error handler</h3>'
                  . '</div><div class="box-body"><div class="panel panel-danger"><div class="panel-body">'
                  . self::buildErrorLink($e->getFile(), $e->getLine()) . ': <strong>' . $e->getMessage() . '</strong></div>';
            $html .= '<table class="table table-bordered"><tr><th style="width: 10px">#</th><th>Fichier</th><th>Fonction</th></tr>';
            foreach ($e->getTrace() as $key => $item) {
                $html .= '<tr><td>' . ($key + 1) . '</td><td>' . @self::buildErrorLink($item['file'], $item['line']) 
                      .  '</td><td>' . htmlspecialchars($item['function']) . '()</td></tr>';
            }
            $html .= '</table></div></div>';
            //Container::getResponse()->appendBody($html);
            echo $html;
        }
    }

    public static function buildErrorLink($file, $line) 
    {
        $link = '<a href="#" onclick="window.open(\'http://localhost/edit.php?file=' . $file . '&line=' . (int) $line . '\',\'edit\',\'menubar=no, status=no, scrollbars=no, menubar=no\');">';
        $link .= $file . '(' . (int) $line . ')' . "</a>";
        return $link;
    }
}
