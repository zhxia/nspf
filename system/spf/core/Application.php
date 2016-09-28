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
                if (function_exists($func)) {
                    call_user_func($func);
                }
            }
        }
    }

    /**
     * @param $plugin
     * @return $this
     */
    public function registerPlugin(Plugin $plugin)
    {
        $this->_dispatcher->addPlugin($plugin);
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
        return $this;
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


    protected function initialize()
    {
        $arrConf = Loader::getInstance()->getConfig();
        if ($arrConf) {
            //初始化插件列表
            if (isset($arrConf['plugins'])) {
                foreach ($arrConf['plugins'] as $p) {
                    $plugin = new $p();
                    $this->_dispatcher->addPlugin($plugin);
                }
            }
            //初始化请求类
            if (isset($arrConf['request_class']) && $arrConf['request_class']) {
                $request = new $arrConf['request_class'];
                $this->_dispatcher->setRequest($request);
            }
            //初始化响应类
            if (isset($arrConf['response_class']) && $arrConf['response_class']) {
                $response = new $arrConf['response_class'];
                $this->_dispatcher->setResponse($response);
            }
            //初始化试图类
            if (isset($arrConf['view_class']) && $arrConf['view_class']) {
                $view = new $arrConf['view_class'];
                $this->_dispatcher->setView($view);
            }
            if (isset($arrConf['enable_debug']) && $arrConf['enable_debug']) {
                $this->setDebugEnabled(true);
            }
        }
    }

    public function run()
    {
        try {
            $this->initialize();
            $this->_dispatcher->dispatch();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}