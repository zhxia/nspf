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
     * @param $class_name
     * @return bool
     */
    public static function autoload($class_name)
    {
        global $G_LOAD_PATH;
        $class_name = strtolower($class_name);
        $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        foreach ($G_LOAD_PATH as $path) {
            $file = $path . $class_file;
            if (file_exists($file)) {
                require_once "$file";
                return true;
            }
        }
        trigger_error('file:"' . $file . '" not found!', E_USER_WARNING);
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

}