<?php
namespace Osf\Exception;

use Osf\View\Helper\Addon\Title;
use Osf\View\Helper\Bootstrap\Addon\Status;
use Osf\View\Helper\Bootstrap\Addon\StatusInterface;

/**
 * This exception cancel the action and just generate an alert.
 * 
 * To be used with Osf/Application
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 14 sept. 2013
 * @package osf
 * @subpackage exception
 */
class AlertException extends OsfException implements StatusInterface
{
    use Title;
    use Status;
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        $this->status(self::STATUS_WARNING);
        parent::__construct($message, $code, $previous);
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
}
