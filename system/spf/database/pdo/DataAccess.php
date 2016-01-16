<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:05
 */

namespace Spf\Database\Pdo;


use Spf\Database\IDataAccess;
use Spf\Database\SqlBuilder;

/**
 * Class DataAccess
 * @package Spf\Database\Pdo
 */
class DataAccess implements IDataAccess
{
    private $transactionStarted = false;

    private $_pdo = null;

    public function __construct($config)
    {
        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        $driver_options = array();
        if (isset($config['driver_options'])) {
            $driver_options = $config['driver_options'];
        }
        $this->_pdo = new Pdo($dsn, $username, $password, $driver_options);
        if (isset($config['default_fetch_mode'])) {
            $this->_pdo->setDefaultMode($config['default_fetch_mode']);
        }
        if (isset($config['init_statements']) && is_array($config['init_statements'])) {
            foreach ($config['init_statements'] as $sql) {
                $this->_pdo->exec($sql);
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
        $stmt = $this->_pdo->prepare($sql);
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
        $stmt = $this->_pdo->prepare($sql);
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
        $stmt = $this->_pdo->prepare($sql);
        if ($stmt->execute($params)) {
            $result = $stmt->fetch();
            return $result['total'];
        }
        return false;

    }

    public function execute($sql)
    {
        return $this->_pdo->exec($sql);
    }

    public function query($sql)
    {
        return $this->_pdo->query($sql);
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
        $stmt = $this->_pdo->prepare($sql);
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
        $stmt = $this->_pdo->prepare($sql);
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
        $stmt = $this->_pdo->prepare($sql);
        $result = $stmt->execute($params);
        $insert_id = $this->_pdo->lastInsertId();
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
            $stmt = $this->_pdo->prepare($sql);
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
        if (!$this->transactionStarted) {
            $this->_pdo->beginTransaction();
            $this->transactionStarted = true;
        }
    }

    public function commit()
    {
        if ($this->transactionStarted) {
            $this->_pdo->commit();
            $this->transactionStarted = false;
        }
    }

    public function rollback()
    {
        if ($this->transactionStarted) {
            $this->_pdo->rollBack();
            $this->transactionStarted = false;
        }
    }

}