<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:14
 */

namespace Spf\Core\Logger;


class SysLogger implements ILogger
{
    private $allowPriority;

    public function __construct($priority)
    {
        if (empty($priority)) {
            $priority = LOG_WARNING;
        }
        $this->allowPriority = $priority;
    }

    public function debug()
    {
        $args = func_get_args();
        $args = array_merge(array(LOG_DEBUG), $args);
        return call_user_func_array(array($this, 'log'), $args);
    }

    public function info()
    {
        $args = func_get_args();
        $args = array_merge(array(LOG_INFO), $args);
        return call_user_func_array(array($this, 'log'), $args);
    }

    function notice()
    {
        $args = func_get_args();
        $args = array_merge(array(LOG_NOTICE), $args);
        return call_user_func_array(array($this, 'log'), $args);
    }

    public function warn()
    {
        $args = func_get_args();
        $args = array_merge(array(LOG_WARNING), $args);
        return call_user_func_array(array($this, 'log'), $args);
    }

    public function error()
    {
        $args = func_get_args();
        $args = array_merge(array(LOG_ERR), $args);
        return call_user_func_array(array($this, 'log'), $args);
    }

    public function fatal()
    {
        $args = func_get_args();
        $args = array_merge(array(LOG_CRIT), $args);
        return call_user_func_array(array($this, 'log'), $args);
    }

    public function log()
    {
        $argsNum = func_num_args();
        if ($argsNum < 2) {
            return false;
        }
        $args = func_get_args();
        $priority = $args[0];
        $message = $args[1];
        if ($priority > $this->allowPriority) {
            return false;
        }
        $priorityName = $this->getLevelName($priority);
        $strLog = '';
        if ($priorityName) {
            $strLog = "[{$priorityName}]";
        }
        $strLog .= "{$message}";
        for ($i = 2; $i < $argsNum; $i++) {
            $strLog .= $args[$i];
        }
        openlog('SPF', LOG_PID, LOG_USER);
        syslog($priority, $strLog);
        closelog();
    }

    /**
     * @param $priority
     * @return string
     */
    protected function getLevelName($priority)
    {
        switch ($priority) {
            case LOG_DEBUG:
                $name = 'DEBUG';
                break;
            case LOG_INFO:
                $name = 'INFO';
                break;
            case LOG_NOTICE:
                $name = 'NOTICE';
                break;
            case LOG_WARNING:
                $name = 'WARN';
                break;
            case LOG_ERR:
                $name = 'ERROR';
                break;
            case LOG_CRIT:
                $name = 'FATAL';
                break;
            default:
                $name = '';
        }
        return $name;
    }

}