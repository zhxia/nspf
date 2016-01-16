<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:03
 */

namespace Spf\Database\Pdo;


use Spf\Core\Logger\LoggerFactory;

/**
 * Class Pdo
 * @package Spf\Database\Pdo
 */
class Pdo extends \PDO
{
    private $defaultFetchMode = PDO::FETCH_ASSOC;

    /**
     * [__construct description]
     * @param [type] $dsn            [description]
     * @param string $username [description]
     * @param string $password [description]
     * @param array $driver_options [description]
     */
    public function __construct($dsn, $username = '', $password = '', array $driver_options = array())
    {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); //强制表字段的所有列名小写
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //配置错误报告为警告
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Spf\Database\Pdo\Statement', array($this)));
    }

    public function setDefaultMode($mode)
    {
        $this->defaultFetchMode = $mode;
    }

    /**
     * @param string $statement
     * @param array $driver_options
     * @return PDOStatement
     */
    public function prepare($statement, $driver_options = array())
    {
        $stmt = parent::prepare($statement, $driver_options);
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->defaultFetchMode);
        }
        return $stmt;
    }

    /**
     * @param string $statement
     * @return int
     */
    public function exec($statement)
    {
        LoggerFactory::getLogger()->info('SQL:' . $statement);
        return parent::exec($statement);
    }

    /**
     * @param string $statement
     * @param null $pdo_option
     * @param null $object
     * @return PDOStatement
     */
    public function query($statement, $pdo_option = null, $object = null)
    {
        LoggerFactory::getLogger()->info('sql:' . $statement);
        if ($pdo_option != null && $object != null) {
            $stmt = parent::query($statement, $pdo_option, $object);
        } else {
            $stmt = parent::query($statement);
        }
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }
        return $stmt;
    }
}