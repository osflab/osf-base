<?php
namespace Osf\Image;

use Osf\Exception\ArchException;

/**
 * Proxy to inkscape executable in order to properly convert SVG to PNG
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage image
 */
class InkscapeProxy
{
    const INKSCAPE_CMD = '/usr/bin/inkscape';
    const SERVER_FIELD = 'file';
    const LOG_FILE = 'inkscape-proxy.log';
    
    /**
     * @return string
     * @throws ArchException
     */
    protected static function getInkscapeExecutable()
    {
        if (!function_exists('passthru')) {
            throw new ArchException('Unable to launch shell commands from this server');
        }
        if (!is_executable(self::INKSCAPE_CMD)) {
            throw new ArchException('Inkscape command [' . self::INKSCAPE_CMD . '] not found');
        }
        return self::INKSCAPE_CMD;
    }
    
    /**
     * @return string
     */
    protected static function getLogFile()
    {
        return sys_get_temp_dir() . '/' . self::LOG_FILE;
    }
    
    /**
     * Proxy log
     * @param string $msg
     * @param string $dump
     */
    protected static function log(string $msg, string $dump = null)
    {
        $line  = date('Y-m-d H:i:s') . ' - ' . filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $line .= ': ' . $msg . "\n";
        if ($dump) {
            $line .= $dump . "\n\n";
        }
        file_put_contents(self::getLogFile(), $line, FILE_APPEND);
    }
    
    /**
     * Convert SVG to PNG through inkscape command
     * @param string $svgContent
     * @param int $w
     * @param int $h
     * @return string
     * @throws ArchException
     */
    public static function svgToPng(string $svgContent, int $w = 500)
    {
        // Préparation
        $inkscape = escapeshellcmd(self::getInkscapeExecutable());
        $tempPath = tempnam(sys_get_temp_dir(), 'ink-');
        $sourceFile = $tempPath . '.svg';
        $targetFile = $tempPath . '.png';
        $outputFile = $tempPath;
        //$cmd = sprintf('%s -z -b "#ffffff" -e %s -w %d %s', $inkscape, 
        $cmd = sprintf('%s -z -y 0 -e %s -w %d %s', $inkscape, 
                escapeshellarg($targetFile), $w, 
                escapeshellarg($sourceFile));
        file_put_contents($sourceFile, $svgContent);
        $status = null;
        
        // Exécution
        ob_start();
        self::log('CMD: ' . $cmd);
        passthru($cmd, $status);
        $result = ob_get_clean();
        file_put_contents($outputFile, $result);
        
        // Récupération et vérifications
        $status = (int) $status;
        if ($status !== 0 || !file_exists($targetFile)) {
            throw new ArchException('Inkscape command fails, return [' . $status . ']. See ' . $outputFile);
        }
        $targetContent = file_get_contents($targetFile);
        if (!$targetFile) {
            throw new ArchException('Target file is empty, failed. See ' . $outputFile);
        }
        
        // Nettoyages
        unlink($targetFile);
        unlink($sourceFile);
        unlink($tempPath);
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        
        // Récupération du résultat
        return $targetContent;
    }
    
    /**
     * Launch an HTTP server
     */
    public static function launchServer()
    {
        $content = filter_input(INPUT_POST, self::SERVER_FIELD);
        if (!$content) { echo 0; self::log('no content'); return; }
        try {
            $output = self::svgToPng((string) $content);
            echo $output;
            self::log('success');
        } catch (\Exception $e) {
            self::log('error ['  . $e->getMessage() . ']');
            echo 0;
        }
    }
    
    /**
     * Call the server to convert a file
     * @param string $serverUrl
     * @param string $svgContent
     * @return type
     * @throws ArchException
     */
    public static function call(string $serverUrl, string $svgContent, bool $tryToCallLocalInkscape = true)
    {
        // Tentative d'appel en local si l'exécutable inkscape existe
        if ($tryToCallLocalInkscape && is_executable(self::INKSCAPE_CMD)) {
            return self::svgToPng($svgContent);
        }
        
        // Sinon il faut curl pour l'appel distant
        if (!extension_loaded('curl')) {
            throw new ArchException('Unable to call inkscape server without CURL');
        }
        
        // Requête curl
        $data = ['file' => $svgContent];
        $params = [
            CURLOPT_URL => $serverUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true
        ];
        
        // Envoi de la requête
        $ch = curl_init();
        curl_setopt_array($ch, $params);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Récupération du source
        return $response;
    }
}
