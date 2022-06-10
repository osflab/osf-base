<?php
namespace Osf\Controller\Cli;

/**
 * Deferred action super class
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage controller
 */
abstract class AbstractDeferredAction implements DeferredActionInterface
{
    protected $messages = [];
    protected $errors   = [];
    
    /**
     * @return $this
     */
    protected function message(string $message)
    {
        $this->messages[] = $message;
        return $this;
    }

    /**
     * @return $this
     */
    protected function error(string $error)
    {
        $this->errors[] = $error;
        return $this;
    }
    
    public function getMessages():array
    {
        return $this->messages;
    }

    public function getErrors():array
    {
        return $this->errors;
    }
    
    /**
     * Log les problèmes liés aux actions différées
     * @param \Exception $e
     * @param string $category
     * @param string $dump
     * @return $this
     */
    protected function registerException(\Exception $e, string $category, string $dump = null)
    {
        $this->error($e->getMessage());
        $file = sys_get_temp_dir() . '/sma-deferred-' . $category . '-errors.log';
        $data = print_r($e, true) . "\n\n" . ($dump ? $dump . "\n\n" : '');
        file_put_contents($file, $data, FILE_APPEND);
        return $this;
    }
}