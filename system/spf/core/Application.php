<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:35
 */

namespace Spf\Core;

/**
 * Class Application
 * @package Spf\Core
 */
class Application
{
    private static $_instance = null;
    /**
     * @var Dispatcher
     */
    private $_dispatcher;

    private $_shutdownFunctions = array();

    /**
     * @var null|Debugger
     */
    private $_debugger;

    private function __construct()
    {
        $this->_dispatcher = new Dispatcher();
        register_shutdown_function(array($this, 'shutdown'));
    }

    public function shutdown()
    {
        if ($this->_shutdownFunctions) {
            foreach ($this->_shutdownFunctions as $func) {
                call_user_func($func);
            }
        }
    }

    /**
     * @param $plugin
     * @return $this
     */
    public function registerPlugin($plugin)
    {
        $this->_dispatcher->registerPlugin($plugin);
        return $this;
    }

    /**
     * @param bool|false $flag
     * @return $this
     */
    public function setDebugEnabled($flag = false)
    {
        if (!$this->_debugger) { //只有在debug开启时才实例化debugger，并且不能置于构造方法中，否则形成死循环
            $this->_debugger = Debugger::getInstance();
        }
        $this->_debugger->setEnabled($flag);
        return $this;
    }

    public function registerShutdownFunctions($function)
    {
        $this->_shutdownFunctions[] = $function;
    }

    /**
     * @return null|Application
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->_dispatcher;
    }

    public function run()
    {
        $this->_dispatcher->dispatch();
    }
}