<?php
namespace Osf\Controller\Router;

use Osf\Test\Runner as OsfTest;

/**
 * Default router unit tests
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 11 sept. 2013
 * @package osf
 * @subpackage test
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();

        $router = new \Osf\Controller\Router();
        $router->setBaseUrl('/');
        $request = \Osf\Container\OsfContainer::getRequest();
        
        $router->route('/controller/action/id/5?toto=titi&tutu=cou');
        $params = $request->getParams();
        self::assertEqual(count($params), 3);
        self::assertEqual($request->getController(), 'controller');
        self::assertEqual($request->getAction(), 'action');
        $uri = $router->buildUri($params);
        self::assertUri($uri, '/controller/action/id/5/toto/titi/tutu/cou');
        
        $router->route('/');
        self::assertEqual($request->getController(), \Osf\Controller\Router::getDefaultControllerName());
        self::assert($request->getAction(), \Osf\Controller\Router::getDefaultActionName());

        $uri = $router->buildUri([], null, 'test');
        self::assertUri($uri, '/common/test');

        $uri = $router->buildUri([], 'album');
        self::assertUri($uri, '/album');

        $router->route('/album/detail');
        $uri = $router->buildUri(['id' => 2]);
        self::assertUri($uri, '/album/detail/id/2');
        
        $router->route('/controller/action/id/5?toto=titi&tutu=cou');
        $params = $request->getParams();
        self::assertEqual(count($params), 3);
        self::assertEqual($request->getController(), 'controller');
        self::assertEqual($request->getAction(), 'action');
        
        $prefixes = ['/test', '/1/2/3'];
        
        foreach ($prefixes as $prefix) {
            $uri = $router->setBaseUrl($prefix)->buildUri($params);
            self::assertUri($uri, $prefix . '/controller/action/id/5/toto/titi/tutu/cou');

            $router->route('/');
            self::assertEqual($request->getController(), \Osf\Controller\Router::getDefaultControllerName());
            self::assertEqual($request->getAction(), \Osf\Controller\Router::getDefaultActionName());

            $uri = $router->buildUri([], null, 'test');
            self::assertUri($uri, $prefix . '/common/test');

            $uri = $router->buildUri([], 'album');
            self::assertUri($uri, $prefix . '/album');

            $router->route('/album/detail');
            $uri = $router->buildUri(['id' => 2]);
            self::assertUri($uri, $prefix . '/album/detail/id/2');
            
            $uri = $router->buildUri(['controller' => 'dev', 'action' => 'list', 'sort' => 'id'], null, null, false);
            self::assertUri($uri, $prefix . '/dev/list/sort/id');
            
            $uri = $router->buildUri(['sort' => 'id'], null, null, false);
            self::assertUri($uri, $prefix . '/common/index/sort/id');
            
            $uri = $router->buildUri(['toto' => 'titi']);
            self::assertUri($uri, $prefix . '/album/detail/toto/titi');
            
            $uri = $router->buildUri([], null, null, false);
            self::assertUri($uri, $prefix . '/');
            
            $router->route('/controller/action/id/5?toto=titi&tutu=cou');
        }
        
        return self::getResult();
    }
    
    protected static function assertUri($uri, $expected)
    {
        self::assert($uri == $expected, 'Bad builded uri, builded [' . $uri . '], expected [' . $expected . ']');
    }
}