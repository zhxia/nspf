<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 1/17/16
 * Time: 4:32 PM
 */

function spfErrorHandler($errno, $errstr, $errfile, $errline)
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
    $br = '<br/>';
    if (php_sapi_name() == 'cli') {
        $br = PHP_EOL;
    }
    $message = sprintf("{$br}%s : %s in %s on line %d {$br}", $errors, $errstr, $errfile, $errline);
    $error_level = ini_get('error_reporting');
    if ($error_level != 0) {
        echo $message;
    }
    if ($errno == 'Notice') {
        \Spf\Core\Logger\LoggerFactory::getLogger()->notice($message);
    } elseif ($errno == 'Warning') {
        \Spf\Core\Logger\LoggerFactory::getLogger()->warn($message);
    } elseif ($errno == 'Error') {
        \Spf\Core\Logger\LoggerFactory::getLogger()->error($message);
    } else {
        \Spf\Core\Logger\LoggerFactory::getLogger()->info($message);
    }
    return TRUE;
}

function spfExceptionHandler(Exception $exception)
{
    $message = sprintf('SPF Exception:errno:%s,message:%s,trace:%s', $exception->getCode(), $exception->getMessage(), $exception->getTraceAsString());
    $error_level = ini_get('error_reporting');
    if ($error_level != 0) {
        echo $message;
    }
}