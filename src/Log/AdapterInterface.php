<?php
namespace Osf\Log;

use Osf\Log\LogProxy as Log;

/**
 * Log Adapter
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package osf
 * @subpackage log
 */
interface AdapterInterface 
{
    public function log(string $message, string $level = Log::LEVEL_INFO, string $category = null, $dump = null);
}
