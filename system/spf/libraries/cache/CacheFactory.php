<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/21
 * Time: 14:54
 */

namespace Spf\Libraries\Cache;

use Spf\Core\Loader;

/**
 * Class CacheFactory
 * @package Spf\Libraries\Cache
 */
class CacheFactory
{
    private static $_instance = null;
    /**
     * @var Redis[]
     */
    private static $_cacheInstances = array();

    private function __construct()
    {
    }

    /**
     * @return null|CacheFactory
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param string $name
     * @return \Redis
     */
    public function getRedis($name = 'default')
    {
        $instKey = 'redis-' . $name;
        if (!isset(self::$_cacheInstances[$instKey])) {
            $redisConfig = Loader::getInstance()->getConfig('redis', 'cache');
            if (!isset($redisConfig[$name])) {
                trigger_error('can not get config item:"redis.' . $name . '" from cache config file!');
            }
            $serverConfig = $redisConfig[$name];
            self::$_cacheInstances[$instKey] = new Redis($serverConfig);
        }
        return self::$_cacheInstances[$instKey];
    }

    /**
     * @param string $name
     * @return \Memcached
     */
    public function getMemcached($name = 'default')
    {
        $instKey = 'memcached-' . $name;
        if (!isset(self::$_cacheInstances[$instKey])) {
            $redisConfig = Loader::getInstance()->getConfig('memcached', 'cache');
            if (!isset($redisConfig[$name])) {
                trigger_error('can not get config item:"memcached.' . $name . '" from cache config file!');
            }
            $serverConfig = $redisConfig[$name];
            self::$_cacheInstances[$instKey] = new Memcached($serverConfig);
        }
        return self::$_cacheInstances[$instKey];
    }
}