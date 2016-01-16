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
 * Class DataAccessFactory
 * @package Spf\Database
 */
class DataAccessFactory
{
    /**
     * @var IDataAccess[]
     */
    private static $_daoList = array();
    private static $_instance = null;
    private $_dataAccessClass;

    /**
     * @return null|DataAccessFactory
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
     * @return bool|IDataAccess
     */
    public function getDao($master = false)
    {
        if (!$this->_dataAccessClass) {
            $this->_dataAccessClass = Loader::getInstance()->getConfig('dao_class', 'database');
            if (empty($this->_dataAccessClass)) {
                trigger_error('No "dao_class" config item found in "database.php"', E_USER_ERROR);
                return false;
            }
        }
        $key = $this->_dataAccessClass . '_' . ($master ? '1' : 0);
        if (!isset(self::$_daoList[$key])) {
            if ($master) {
                $config = Loader::getInstance()->getConfig('master', 'database');
            } else {
                $config = Loader::getInstance()->getConfig('slave', 'database');
            }
            self::$_daoList[$key] = new $this->_dataAccessClass($config);
        }
        return self::$_daoList[$key];
    }

}