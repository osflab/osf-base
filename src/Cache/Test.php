<?php
namespace Osf\Cache;

use Osf\Test\Runner as OsfTest;

/**
 * Cache component unit test
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 2017
 * @package package
 * @subpackage subpackage
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();
        
        $key = 'TEST::a_key';
        $cache = new OsfCache('osftest');
        
        $redisPassFile = $_SERVER['HOME'] . '/.redispass';
        $passExists = file_exists($redisPassFile);
        if ($passExists) {
            $cache->getRedis()->auth(trim(file_get_contents($redisPassFile)));
        }
        try {
            $available = true;
            $cache->clean('FICTIVEKEY');
        } catch (\RedisException $e) {
            self::assert(false, 
                    $passExists ? 'Password in ' . $redisPassFile . ' seems incorrect' 
                    : 'This test need your redis auth pass, please put it in ' . $redisPassFile);
            self::assert(false, $e->getMessage());
            $available = false;
        }
        
        if ($available) {
            self::assert($cache->getRedis() instanceof \Redis);
            self::assert(is_int($cache->cleanAll()));
            self::assert(is_int($cache->clean($key)));
            self::assertEqual($cache->get($key, false), false);
            self::assert($cache->set($key, null));
            self::assertEqual($cache->get($key), null);
            self::assert($cache->set($key, 'welcome'));
            self::assertEqual($cache->get($key), 'welcome');
            self::assertEqual($cache->cleanAll(), 1);
            self::assertEqual($cache->get($key), null);
            self::assertEqual($cache->get($key, ['default']), ['default']);
            self::assertEqual($cache->set($key, ['a' => 'b', 'c' => 'd'], new \DateInterval('PT2S')), true);
            self::assertEqual($cache->get($key, false), ['a' => 'b', 'c' => 'd']);
            self::assertEqual($cache->has($key), true);
            self::assertEqual($cache->delete($key), true);
            self::assertEqual($cache->get($key, false), false);

            $values = ['A1' => ['a' => 4], 'A2' => new \stdClass(), 'B' => true];
            self::assertEqual($cache->setMultiple($values, 2), true);
            self::assertEqual($cache->getMultiple(array_keys($values)), $values, false);
            self::assertEqual($cache->get('A2'), new \stdClass(), false);
            self::assertEqual($cache->get('A1'), ['a' => 4]);

            $values['B1'] = false;
            self::assertEqual($cache->getMultiple(array_keys($values)), $values, false);
            self::assertEqual($cache->delete('UNK'), false);
            self::assertEqual($cache->delete('A1'), true);
            self::assertEqual($cache->get('A1'), null);
            self::assertEqual($cache->clear(), true);
            self::assertEqual($cache->get('B'), null);
        }
        
        return self::getResult();
    }
}
