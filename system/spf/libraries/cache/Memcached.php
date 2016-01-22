<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 10:07
 */

namespace Spf\Libraries\Cache;


use Spf\Core\Debugger;

class Memcached
{
    /**
     * @var \Memcached|null
     */
    private $_memcached = null;

    /**
     * @var Debugger
     */
    private $_debugger = null;

    public function __construct($config)
    {
        if ($config && is_array($config)) {
            $this->_memcached = new \Memcached();
            foreach ($config as $val) {
                $this->_memcached->addServer($val['host'], $val['port'], $val['weight']);
            }
            $this->_debugger = Debugger::getInstance();
        } else {
            trigger_error('Memcached config is empty!');
        }
    }

    /**
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        $mark = 'invoke memcached function:' . $name . ',args:' . var_export($args, true);
        $this->_debugger->benchmarkBegin($mark);
        $result = call_user_func_array(array($this->_memcached, $name), $args);
        $this->_debugger->benchmarkEnd($mark);
        return $result;
    }


}