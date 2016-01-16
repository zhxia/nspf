<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:13
 */

namespace Spf\Core\Logger;


use Spf\Core\Loader;

class LoggerFactory
{
    private static $_loggers = array();

    /**
     * @param string $className
     * @return ILogger
     */
    public static function getLogger($className = '')
    {
        if (empty($className)) {
            $className = Loader::getInstance()->getConfig('log_class_name');
        }
        if (!isset(self::$_loggers[$className])) {
            $log_priority = Loader::getInstance()->getConfig('log_priority');
            self::$_loggers[$className] = new $className($log_priority);
        }
        return self::$_loggers[$className];
    }
}