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
    private static $_daoList = array();
    private static $_instance = null;
    private $_dataAccessClass;

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
     * @param bool|false $master
     * @return bool|IDaoAdapter
     */
    public function getDao($master = false)
    {
        $suffix = $master ? 'master' : 'slave';
        if (!$this->_dataAccessClass) {
            $dbConfig = Loader::getInstance()->getConfig($suffix, 'database');
            if (!isset($dbConfig) || empty($dbConfig['adapter_class'])) {
                trigger_error('No "adapter_class" config item found in "database.php"', E_USER_ERROR);
                return false;
            }
            $this->_dataAccessClass = $dbConfig['adapter_class'];
        }
        $key = $this->_dataAccessClass . '_' . $suffix;
        if (!isset(self::$_daoList[$key])) {
            self::$_daoList[$key] = new $this->_dataAccessClass($dbConfig);
        }
        return self::$_daoList[$key];
    }

}