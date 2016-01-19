<?php
if (!defined('E_DEPRECATED')) {
    defined('E_DEPRECATED', 0);
}
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(0);
$base_uri = DIRECTORY_SEPARATOR == '/' ? $_SERVER['SCRIPT_NAME'] : str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URI', $base_uri == '/' ? '' : $base_uri);
define('APP_NAME', 'application');
define('APP_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('SYS_PATH', APP_PATH . '..' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('PAGE_EXT', 'phtml');
$G_LOAD_PATH = array(
    APP_PATH,
    SYS_PATH,
);
$G_CONF_PATH = array(
    APP_PATH . 'config' . DIRECTORY_SEPARATOR,
);
require_once SYS_PATH . 'spf/core/Loader.php';
spl_autoload_register(array('spf\core\Loader', 'autoload'));
\spf\core\Application::getInstance()
    ->setDebugEnabled(true)
    ->registerPlugin(new \Plugins\LoginPlugin())
    ->run();