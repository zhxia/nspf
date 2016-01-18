<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/18
 * Time: 11:10
 */

namespace Spf\Core;


use Spf\Core\Logger\LoggerFactory;

class Error
{
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $errors = 'Notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $errors = 'Warning';
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $errors = 'Error';
                break;
            default:
                $errors = 'Unknown';
                break;
        }
        $message = sprintf("%s : %s in %s on line %d", $errors, $errstr, $errfile, $errline);
        $error_level = ini_get('error_reporting');
        if ($error_level != 0) {
            echo $message;
        }
        $logger = LoggerFactory::getLogger();
        if ($errors == 'Notice') {
            $logger->notice($message);
        } elseif ($errors == 'Warning') {
            $logger->warn($message);
        } elseif ($errors == 'Error') {
            $logger->error($message);
        } else {
            $logger->info($message);
        }
    }

    public static function exceptionHandler(Exception $exception)
    {
        $message = sprintf('SPF Exception:errno:%s,message:%s,trace:%s', $exception->getCode(), $exception->getMessage(), $exception->getTraceAsString());
        $error_level = ini_get('error_reporting');
        if ($error_level != 0) {
            echo $message;
        } else {
            LoggerFactory::getLogger()->error($message);
        }
    }
}