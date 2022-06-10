<?php
namespace Osf\Controller;

use Osf\Controller\Response;
use Osf\Application\OsfApplication as Application;
use Osf\Container\OsfContainer as Container;

/**
 * Action controller super class
 * 
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 12 sept. 2013
 * @package osf
 * @subpackage controller
 */
abstract class Action
{
    public function __construct()
    {
        // detect and call "init" method
        if (method_exists($this, 'init')) {
            $this->init();
        }
        
        // Suppression du layout depuis l'url
        if (Container::getRequest()->getParam('no') == 'layout') {
            $this->disableLayout();
        }
    }
    
    /**
     * Disable loading and rendering vue
     * @return $this
     */
    protected function disableView()
    {
        Container::getApplication()->setDispatchStep(Application::RENDER_VIEW, false);
        return $this;
    }
    
    /**
     * Disable loading and rendering layout
     * @return $this
     */
    protected function disableLayout()
    {
        Container::getApplication()->setDispatchStep(Application::RENDER_LAYOUT, false);
        return $this;
    }
    
    /**
     * Call disableView and disableLayout
     * @return $this
     */
    protected function disableViewAndLayout()
    {
        $this->disableView();
        $this->disableLayout();
        return $this;
    }
    
    /**
     * Clear the response object (body and headers)
     * @return $this
     */
    protected function clearResponse()
    {
        Container::getResponse()->setBody('');
        Container::getResponse()->clearHeaders();
        return $this;
    }
    
    /**
     * @return \Osf\View\Helper
     */
    protected function viewHelpers()
    {
        return Container::getViewHelper();
    }
    
    /**
     * @return $this
     */
    protected function noCache()
    {
        Container::getResponse()->noCache();
        return $this;
    }
    
    /**
     * @param string $jsonContent
     * @return $this
     */
    protected function json($jsonContent)
    {
        $this->clearResponse()
            ->disableViewAndLayout()
            ->getResponse()
                ->setTypeJson()
                ->noCache()
                ->setBody((string) $jsonContent);
        return $this;
    }
    
    public function csv(string $fileName, callable $callback, array $params = [])
    {
        $this->clearResponse()
            ->disableViewAndLayout()
            ->getResponse()
                ->setTypeCsv()
                ->setDisposition('attachment', $fileName)
                ->setRawHeader('Content-Transfer-Encoding: utf-8')
                ->noCache()
                ->setCallback($callback, $params);
    }
    
    /**
     * @param string $pdfContent
     * @return $this
     */
    protected function pdf($pdfContent, bool $inline = true, ?string $filename = null)
    {
        $disposition = $inline ? 'inline' : 'attachment';
        $this->clearResponse()
            ->disableViewAndLayout()
            ->getResponse()
                ->setTypePdf()
                ->noCache()
                ->setDisposition($disposition, $filename)
                ->setBody((string) $pdfContent);
        return $this;
    }
    
    /**
     * Envoie un fichier sur la sortie standard
     * @param string $file
     * @param string $fileType
     * @param string|null $fileName
     * @param bool $unlinkAfterRead
     * @param bool $die
     * @return void
     */
    protected function readfile(string $file, string $fileType, ?string $fileName, bool $unlinkAfterRead = true, bool $die = true): void
    {
        // Headers communs
        $fileName = $fileName ?? basename($file);
        header(Response::CONTENT_TYPES[$fileType]);
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Lecture et fin
        readfile($file);
        $unlinkAfterRead && unlink($file);
        $die && die();
    }
    
    /**
     * Reinit the dispatch loop with a new request
     * @param array $params
     * @return $this
     */
    public function dispatch(array $params = [])
    {
        static $dispatchHistory = [];
        static $dispatchHistoryCount = 0;
        
        $dispatchHistory[] = $params;
        $dispatchHistoryCount++;
        if ($dispatchHistoryCount > 10) {
            Container::getApplication()->isDevelopment() && var_dump($dispatchHistory);
            trigger_error('Infinite  dispatch loop detected', E_USER_ERROR);
            die(); // Error handler do not die request
        }
        
        Container::getRequest()->reset()->setParams($params);
        Container::getResponse()->reset();
        Container::getApplication()
                ->setDispatched(false)
                ->setDispatchStep(Application::RENDER_VIEW, true)
                ->setDispatchStep(Application::RENDER_LAYOUT, true);
        return $this;
    }
    
    /**
     * Reinit the dispatch loop with a new uri
     * @param string $uri
     * @return $this
     */
    public function dispatchUri(string $uri, Router $router = null)
    {
        $this->dispatch();
        if ($router === null) {
            $router = Container::getRouter();
        }
        $router->route($uri);
        return $this;
    }
    
    // Request / Response helpers
    
    /**
     * @return \Osf\Controller\Request
     */
    public function getRequest():Request
    {
        return Container::getRequest();
    }
    
    /**
     * @return \Osf\Controller\Response
     */
    public function getResponse():Response
    {
        return Container::getResponse();
    }
    
    /**
     * @return array
     */
    public function getParams():array
    {
        return $this->getRequest()->getParams();
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function hasParam(string $key):bool
    {
        return array_key_exists($key, $this->getParams());
    }
    
    /**
     * @param string $key
     * @return type
     */
    public function getParam(string $key)
    {
        return $this->hasParam($key) ? $this->getParams()[$key] : null;
    }
    
    public function preDispatch()
    {
    }
    
    public function postDispatch()
    {
    }
}