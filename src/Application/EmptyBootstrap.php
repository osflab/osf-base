<?php
namespace Osf\Application;

/**
 * Empty bootstrap if no application bootstrap found
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 28 dec. 2016
 * @package osf
 * @subpackage application
 * @task [LAYOUT] voir à terme si afterBuildLayout est utile...
 */
final class EmptyBootstrap extends Bootstrap
{
    public function bootstrap() {}
    public function afterBuildLayout() {}
}
