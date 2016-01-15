<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:35
 */

namespace Spf\Core;


class Application
{
    private static $_instance = null;
    /**
     * @var Dispatcher
     */
    private $_dispatcher;

    private function __construct()
    {
        $this->_dispatcher = new Dispatcher();
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