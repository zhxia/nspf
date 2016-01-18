<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:11
 */

namespace Spf\Core;


class Loader
{

    private static $config = array();
    private static $_instance = null;
    private static $_coreSuffix = array(
        'Controller',
        'Model',
        'Plugin',
        'Interceptor'
    );

    private function __construct()
    {
    }

    /**
     * @return null|Loader
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param $className
     * @return bool
     */
    public static function autoload($className)
    {
        global $G_LOAD_PATH;
        $arrTemp = explode('\\', $className);
        $filename = array_pop($arrTemp);
        foreach (self::$_coreSuffix as $suffix) {
            if ($idx = strpos($filename, $suffix)) {
                $filename = substr($filename, 0, $idx);
                break;
            }
        }
        $className = strtolower(implode('\\', $arrTemp)) . '\\' . $filename;
        $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        foreach ($G_LOAD_PATH as $path) {
            $file = $path . $classFile;
            if (file_exists($file)) {
                require_once "$file";
                return true;
            }
        }
        trigger_error('class:"' . $className . '" not found!', E_USER_WARNING);
        return false;
    }

    /**
     * @param $name
     * @param string $filename
     * @return null
     */
    public function getConfig($name = '', $filename = 'common')
    {
        if (!isset(self::$config[$filename])) {
            global $G_CONF_PATH;
            foreach ($G_CONF_PATH as $path) {
                $fullFilename = $path . $filename . '.php';
                if (file_exists($fullFilename)) {
                    include "$fullFilename";
                    if (isset($config)) {
                        self::$config[$filename] = $config;
                    }
                } else {
                    trigger_error('file :"' . $fullFilename . '" not exists!');
                }
            }
        }
        if (isset(self::$config[$filename])) {
            if (empty($name)) {
                return self::$config[$filename];
            }
            return isset(self::$config[$filename][$name]) ? self::$config[$filename][$name] : null;
        }
    }

    /**
     * 加载指定控制器的拦截器
     * @param $class
     * @return Interceptor[]
     */
    public function loadInterceptors($class)
    {
        $Interceptors = array();
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
            foreach ($interceptorClasses as $key => $className) {
                if (strpos($className, '!') === 0) {
                    unset($interceptorClasses[$key]);
                    $className = substr($className, 1);
                    $idx = array_search($className, $interceptorClasses);
                    if ($idx !== false) {
                        unset($interceptorClasses[$idx]);
                    }
                    continue;
                }
                if (class_exists($className)) {
                    $Interceptors[] = new $className();
                }
            }
        }
        return $Interceptors;
    }

}