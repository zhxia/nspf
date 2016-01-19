<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:08
 */

namespace Spf\Database\Mysqli;


use Spf\Database\IDaoAdapter;
use Spf\Database\SqlBuilder;

class DaoAdapter implements IDaoAdapter
{
    private $_transactionStarted = false;
    /**
     * @var null|Mysqli
     */
    private $_dao = null;

    private $_fetchModel = MYSQLI_ASSOC;

    public function __construct($config)
    {
        $this->_dao = new Mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
        if (isset($config['init_sql']) && $config['init_sql']) {
            foreach ($config['init_sql'] as $sql) {
                $this->_dao->query($sql);
            }
        }
    }

    public function insertOnUpdate($table, $data, $update)
    {
        // TODO: Implement insertOnUpdate() method.
    }

    public function select($table, $where = '', $order = '', $limit = 20, $offset = 0, $fields = '*')
    {
        $sql = SqlBuilder::buildQuerySql($table, $where, $order, $limit, $offset, $fields);
        $params = null;
        if (is_array($where)) {
            $params = array_values($where);
        }
        $stmt = $this->_dao->prepare($sql);
        if ($params) {
            $arrParam[] = str_repeat('s', count($params));
            $arrParam = array_merge($arrParam, $params);
            call_user_func_array(array($stmt, 'bind_param'), $this->makeRefVal($arrParam));
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all($this->_fetchModel);
        }
        return false;
    }

    /**
     * change value to ref
     * @param $arr
     * @return array
     */
    private function makeRefVal($arr)
    {
        $refs = array();
        if ($arr) {
            foreach ($arr as $k => $v) {
                $refs[$k] =& $arr[$k];
            }
        }
        return $refs;
    }

    public function selectCount($table, $where)
    {
        $sql=SqlBuilder::buildSelectCountSql($table,$where);
        $params = null;
        if (is_array($where)) {
            $params = array_values($where);
        }
        $stmt = $this->_dao->prepare($sql);
        if ($params) {
            $arrParam[] = str_repeat('s', count($params));
            $arrParam = array_merge($arrParam, $params);
            call_user_func_array(array($stmt, 'bind_param'), $this->makeRefVal($arrParam));
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
             return $result->fetch_row()[0];
        }
        return false;
    }

    public function execute($sql)
    {
        $result = $this->_dao->query($sql);
        return $result->num_rows;
    }

    public function query($sql)
    {
        $result = $this->_dao->query($sql);
        return $result->fetch_all($this->_fetchModel);
    }

    public function update($table, $data, $where)
    {
        // TODO: Implement update() method.
    }

    public function delete($table, $where)
    {
        // TODO: Implement delete() method.
    }

    public function insert($table, array $row)
    {
        // TODO: Implement insert() method.
    }

    public function batchInsert($table, array $rows)
    {
        // TODO: Implement batchInsert() method.
    }

    public function beginTransaction()
    {
        if (!$this->_transactionStarted) {
            $this->_dao->begin_transaction();
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
            $this->rollback();
            $this->_transactionStarted = false;
        }
    }

}