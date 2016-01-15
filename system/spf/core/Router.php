<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:46
 */

namespace Spf\Core;


class Router
{
    const CONFIG_F_ROUTER = 'route';
    const CONFIG_N_MAPPINGS = 'mappings';
    const CONFIG_N_AUTO_MAPPING = 'auto_mapping';
    const CONFIG_N_HTTP404 = 'http404';

    public function mapping()
    {
        $class = $this->getControllerClass();
        return $class ? $class : false;
    }

    protected function getControllerClass()
    {
        if (BASE_URI != '' && strpos($_SERVER['REQUEST_URI'], BASE_URI) === 0) {
            $uri = substr($_SERVER['REQUEST_URI'], strlen(BASE_URI));
        } else {
            $uri = $_SERVER['REQUEST_URI'];
        }
        if (strpos($uri, '?') !== FALSE) {
            $uri = strstr($uri, '?', TRUE);
        }
        if (empty($uri)) {
            $uri = '/';
        }
        $class = $this->customMapping($uri);
        if ($class) {
            return $class;
        }
        $auto_mapping = Loader::getInstance()->getConfig(self::CONFIG_N_AUTO_MAPPING, self::CONFIG_F_ROUTER);
        if ($auto_mapping) {
            $class = $this->autoMapping($uri);
            if ($class) {
                return $class;
            }
        }
        //没有匹配的控制器，执行404控制器
        $class_name = Loader::getInstance()->getConfig(self::CONFIG_N_HTTP404, self::CONFIG_F_ROUTER);
        if ($class_name) {
            return $class_name;
        }
        return false;
    }

    /**
     * 自定义路由
     * @param $uri
     * @return bool|int|string
     */
    private function customMapping($uri)
    {
        $mappings = Loader::getInstance()->getConfig(self::CONFIG_N_MAPPINGS, self::CONFIG_F_ROUTER);
        //将url与controller进行映射
        $matches = array();
        foreach ($mappings as $class_name => $mapping) {
            foreach ($mapping as $pattern) {
                $pattern = str_replace('/', '\/', $pattern);
                if (preg_match("/{$pattern}/i", $uri, $matches)) {
                    Application::getInstance()->getDispatcher()->getRequest()->setRouterMatches($matches);
                    return $class_name;
                }
            }
        }
        return false;
    }

    /**
     * 自动路由
     * @param $uri
     * @return bool|string
     */
    private function autoMapping($uri)
    {
        $uri = trim($uri, '/');
        if ($uri) {
            $pieces = explode('/', $uri);
            $pieces = array_map('ucfirst', $pieces);
            return implode('_', $pieces);
        }
        return false;
    }
}