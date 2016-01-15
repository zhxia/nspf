<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:10
 */

namespace Spf\Core;


class Dispatcher
{
    const VERSION = '1.0.0';
    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    /**
     * @var Router
     */
    private $_router;

    /**
     * @var View
     */
    private $_view;

    private static $plugins = array();

    function __construct()
    {
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->_router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->_router;
    }


    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @return View
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * @param View $view
     */
    public function setView($view)
    {
        $this->_view = $view;
    }

    public function registerPlugin(Plugin $plugin)
    {
        self::$plugins[] = $plugin;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return self::$plugins;
    }

    /**
     * 执行插件
     * @param $step
     */
    protected function executePlugins($step)
    {
        if (empty(self::$plugins)) {
            return;
        }
        foreach (self::$plugins as $plugin) {
            switch ($step) {
                case Plugin::STEP_ROUTER_STARTUP:
                    $plugin->routerStartup($this->_request, $this->_response);
                    break;
                case Plugin::STEP_ROUTER_SHUTDOWN:
                    $plugin->routerShutdown($this->_request, $this->_response);
                    break;
                case Plugin::STEP_DISPATCH_LOOP_STARTUP:
                    $plugin->dispatchLoopStartup($this->_request, $this->_response);
                    break;
                case Plugin::STEP_DISPATCH_LOOP_SHUTDOWN:
                    $plugin->dispatchLoopShutdown($this->_request, $this->_response);
                    break;
                case Plugin::STEP_DISPATCH_STARTUP:
                    $plugin->dispatchStartup($this->_request, $this->_response);
                    break;
                case Plugin::STEP_DISPATCH_SHUTDOWN:
                    $plugin->dispatchShutdown($this->_request, $this->_response);
                    break;
                case Plugin::STEP_PRE_RESPONSE:
                    $plugin->preResponse($this->_request, $this->_response);
                    break;
            }
        }
    }

    /**
     * @param $class
     * @return array
     */
    protected function getInterceptor($class)
    {
        $interceptors = array();
        $interceptorClasses = array();
        $globalInterceptorClasses = Loader::getInstance()->getConfig('global', 'interceptor');
        if ($globalInterceptorClasses && is_array($globalInterceptorClasses)) {
            $interceptorClasses = $globalInterceptorClasses;
        }
        $classInterceptors = Loader::getInstance()->getConfig($class, 'interceptor');
        if (empty($classInterceptors)) {
            //@todo 获取基类Controller的拦截器
            $classInterceptors = Loader::getInstance()->getConfig('default', 'interceptor');
        }
        if ($classInterceptors && is_array($classInterceptors)) {
            $interceptorClasses = array_merge($interceptorClasses, $classInterceptors);
        }
        if ($interceptorClasses) {
            $interceptorClasses = array_unique($interceptorClasses);
            foreach ($interceptorClasses as $key=>$cls) {
                if (strpos($cls,'!')===0) {
                    unset($interceptorClasses[$key]);
                    $className = substr($cls, 1);
                    $idx = array_search($className, $interceptorClasses);
                    if ($idx !== false) {
                        unset($interceptorClasses[$idx]);
                    }
                    continue;
                }
                if (class_exists($className)) {
                    $interceptors[] = new $className();
                }
            }
        }
        print_r($interceptorClasses);
        return $interceptors;
    }

    public function dispatch()
    {
        if (!$this->_request) {
            $this->_request = new Request();
        }
        if (!$this->_response) {
            $this->_response = new Response();
        }
        if (!$this->_router) {
            $this->_router = new Router();
        }
        if (!$this->_view) {
            $this->_view = new View();
        }
        //router startup
        $this->executePlugins(Plugin::STEP_ROUTER_STARTUP);
        $class = $this->_router->mapping();
        //router shutdown
        $this->executePlugins(Plugin::STEP_ROUTER_SHUTDOWN);
        $controller = new $class();
        //dispatchloop startup
        $this->executePlugins(Plugin::STEP_DISPATCH_LOOP_STARTUP);
        $interceptors = $this->getInterceptor($class);
        while (true) {
            //preDispatch
            $this->executePlugins(Plugin::STEP_DISPATCH_STARTUP);
            $result = $controller->execute();
            //postDispatch
            $this->executePlugins(Plugin::STEP_DISPATCH_SHUTDOWN);
            if ($result instanceof Controller) {
                $controller = $result;
                continue;
            }
            break;
        }
        //dispatchloop shutdown
        $this->executePlugins(Plugin::STEP_DISPATCH_LOOP_SHUTDOWN);

    }
}