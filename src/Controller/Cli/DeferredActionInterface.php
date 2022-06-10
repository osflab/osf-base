<?php
namespace Osf\Controller\Cli;

/**
 * Deferred action must implements this
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage controller
 */
interface DeferredActionInterface
{
    public function getName():string;
    public function execute(); // Return false, true or null (todo: PHP 7.1)
    public function getErrors():array;
    public function getMessages():array;
}
