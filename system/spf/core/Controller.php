<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:51
 */

namespace Spf\Core;


abstract class Controller
{
    /**
     * @var Dispatcher
     */
    private $_dispatcher;

    final public function __construct()
    {
        $this->_dispatcher = Application::getInstance()->getDispatcher();
        $this->init();
    }

    public function init()
    {
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        return $this->_dispatcher->getRequest();
    }

    /**
     * @return Response
     */
    protected function getResponse()
    {
        return $this->_dispatcher->getResponse();
    }

    /**
     * @return View
     */
    protected function getView()
    {
        return $this->_dispatcher->getView();
    }

    abstract public function execute();

}