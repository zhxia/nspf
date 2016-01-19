<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:05
 */

namespace Spf\Database\Pdo;


use Spf\Core\Debugger;
use Spf\Database\IDaoAdapter;
use Spf\Database\SqlBuilder;

/**
 * Class DaoAdapter
 * @package Spf\Database\Pdo
 */
class DaoAdapter implements IDaoAdapter
{
    private $_transactionStarted = false;

    /**
     * @var null|Pdo
     */
    private $_dao = null;

    public function __construct($config)
    {

        $username = $config['user'];
        $password = $config['password'];
        $driver_options = array();
        if (isset($config['driver_options'])) {
            $driver_options = $config['driver_options'];
        }
        $dsn = "mysql:host={$config['host']}" . (isset($config['port']) ? '' : ';port:' . $config['port']) . ";dbname={$config['database']}";
        $this->_dao = new Pdo($dsn, $username, $password, $driver_options);
        if (isset($config['fetch_mode'])) {
            $this->_dao->setDefaultMode($config['fetch_mode']);
        }
        if (isset($config['init_sql']) && is_array($config['init_sql'])) {
            foreach ($config['init_sql'] as $sql) {
                $this->_dao->exec($sql);
            }
        }
    }

    public function insertOnUpdate($table, $data, $update)
    {
        if (empty($data)) {
            return false;
        }
        $sql = SqlBuilder::buildInsertOnUpdateSql($table, $data, $update);
        $params = null;
        if (is_array($data)) {
            $params = array_values($data);
        }
        if (is_array($update)) {
            $params = array_merge($params, array_values($update));
        }
        $stmt = $this->_dao->prepare($sql);
        $result = $stmt->execute($params);
        $affect_count = $stmt->rowCount();
        return $affect_count === 0 ? $result : $affect_count;
    }

    public function select($table, $where = '', $order = '', $limit = 20, $offset = 0, $fields = '*')
    {
        $sql = SqlBuilder::buildQuerySql($table, $where, $order, $limit, $offset, $fields);
        $params = null;
        if (is_array($where)) {
            $params = array_values($where);
        }
        $stmt = $this->_dao->prepare($sql);
        if ($stmt->execute($params)) {
            return $stmt->fetchAll();
        }
        return false;
    }

    public function selectCount($table, $where = '')
    {
        $sql = SqlBuilder::buildSelectCountSql($table, $where);
        $params = null;
        if (is_array($where)) {
            $params = array_values($where);
        }
        $stmt = $this->_dao->prepare($sql);
        if ($stmt->execute($params)) {
            $result = $stmt->fetch();
            return $result['total'];
        }
        return false;

    }

    public function execute($sql)
    {
        return $this->_dao->exec($sql);
    }

    public function query($sql)
    {
        return $this->_dao->query($sql);
    }

    public function update($table, $data, $where, $option = '')
    {
        if (empty($data) || empty($where)) {
            return false;
        }
        $sql = SqlBuilder::buildUpdateSql($table, $data, $where, $option);
        $params = null;
        if (is_array($data)) {
            $params = array_values($data);
        }
        if (is_array($where)) {
            $params = array_merge($params, array_values($where));
        }
        $stmt = $this->_dao->prepare($sql);
        $result = $stmt->execute($params);
        $affect_count = $stmt->rowCount();
        return $affect_count === 0 ? $result : $affect_count;
    }

    public function delete($table, $where)
    {
        if (empty($where)) {
            return false;
        }
        $sql = SqlBuilder::buildDeleteSql($table, $where);
        $params = null;
        if (is_array($where)) {
            $params = array_values($where);
        }
        $stmt = $this->_dao->prepare($sql);
        $result = $stmt->execute($params);
        $affect_count = $stmt->rowCount();
        return $affect_count === 0 ? $result : $affect_count;
    }

    public function insert($table, array $data)
    {
        if (empty($data)) {
            return false;
        }
        $sql = SqlBuilder::buildInsertSql($table, $data);
        $params = array_values($data);
        $stmt = $this->_dao->prepare($sql);
        $result = $stmt->execute($params);
        $insert_id = $this->_dao->lastInsertId();
        if ($insert_id) {
            return $insert_id;
        }
        $affect_count = $stmt->rowCount();
        return $affect_count === 0 ? $result : $affect_count;

    }

    public function batchInsert($table, array $rows)
    {
        if (empty($rows)) {
            return false;
        }
        $sql = SqlBuilder::buildInsertSql($table, $rows[0]);
        try {
            $this->begin_transaction();
            $stmt = $this->_dao->prepare($sql);
            foreach ($rows as $row) {
                $params = array_values($row);
                $stmt->execute($params);
            }
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
        }
        return false;
    }

    public function beginTransaction()
    {
        if (!$this->_transactionStarted) {
            $this->_dao->beginTransaction();
            $this->_transactionStarted = true;
        }
    }

    public function commit()
    {
        if ($this->_transactionStarted) {
            $this->_dao->commit();
            $this->_transactionStarted = false;
        }
    }

    public function rollback()
    {
        if ($this->_transactionStarted) {
            $this->_dao->rollBack();
            $this->_transactionStarted = false;
        }
    }

}