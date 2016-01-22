<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 10:07
 */

namespace Spf\Libraries\Cache;


use Spf\Core\Debugger;


class Redis
{
    /**
     * @var \Redis
     */
    private $_redis = null;

    /**
     * @var null|Debugger
     */
    private $_debugger = null;

    public function __construct($config)
    {
        $this->_redis = new \Redis();
        if (isset($config['pconnect']) && $config['pconnect']) {
            $this->_redis->pconnect($config['host'], $config['port'], $config['timeout']);
        } else {
            $this->_redis->connect($config['host'], $config['port'], $config['timeout']);
        }
        $this->_debugger = Debugger::getInstance();
    }

    public function __call($name, $args)
    {
        $mark = 'invoke redis function:' . $name . ',args:' . var_export($args, true);
        $this->_debugger->benchmarkBegin($mark);
        $result = call_user_func_array(array($this->_redis, $name), $args);
        $this->_debugger->benchmarkEnd($mark);
        return $result;
    }
}