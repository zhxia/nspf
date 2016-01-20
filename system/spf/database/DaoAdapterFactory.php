<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 10:54
 */

namespace Spf\Database;


use Spf\Core\Loader;

/**
 * Class DaoAdapterFactory
 * @package Spf\Database
 */
class DaoAdapterFactory
{
    /**
     * @var IDaoAdapter[]
     */
    private static $_daoList = null;
    private static $_instance = null;

    /**
     * @return null|DaoAdapterFactory
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param bool|false $configKey
     * @return bool|IDaoAdapter
     */
    public function getDao($configKey)
    {
        if (!isset(self::$_daoList[$configKey])) {
            $dbConfig = Loader::getInstance()->getConfig($configKey, 'database');
            if (!isset($dbConfig) || empty($dbConfig['adapter_class'])) {
                trigger_error('No "adapter_class" config item found in "database.php"', E_USER_ERROR);
                return false;
            }
            self::$_daoList[$configKey] = new $dbConfig['adapter_class']($dbConfig);
        }
        return self::$_daoList[$configKey];
    }

}