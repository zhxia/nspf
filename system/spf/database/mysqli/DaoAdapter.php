<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:08
 */

namespace Spf\Database\Mysqli;


use Spf\Core\Logger\LoggerFactory;
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
        if ($this->_dao->errno) {
            trigger_error('mysqli connect to server failed! error:' . $this->_dao->error, E_USER_ERROR);
        }
        if (isset($config['init_sql']) && $config['init_sql']) {
            foreach ($config['init_sql'] as $sql) {
                $this->_dao->query($sql);
            }
        }
    }

    public function insertOnUpdate($table, $data, $update)
    {
        if (empty($data) || empty($update)) {
            return false;
        }
        $sql = SqlBuilder::buildInsertOnUpdateSql($table, $data, $update);
        $stmt = $this->_dao->prepare($sql);
        $params = $this->buildParams($data, $update);
        $this->bindParams($stmt, $params);
        if ($stmt->execute()) {
            return $stmt->affected_rows;
        }
        return false;
    }

    public function select($table, $where = '', $order = '', $limit = 20, $offset = 0, $fields = '*')
    {
        $sql = SqlBuilder::buildQuerySql($table, $where, $order, $limit, $offset, $fields);
        $stmt = $this->_dao->prepare($sql);
        $params = $this->buildParams($where);
        $this->bindParams($stmt, $params);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result) {
                return $result->fetch_all($this->_fetchModel);
            }
        }
        return false;
    }

    public function selectCount($table, $where)
    {
        $sql = SqlBuilder::buildSelectCountSql($table, $where);
        $stmt = $this->_dao->prepare($sql);
        $params = $this->buildParams($where);
        $this->bindParams($stmt, $params);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result) {
                return $result->fetch_assoc()['total'];
            }
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

    public function update($table, $data, $where, $option = '')
    {
        if (empty($data) || empty($where)) {
            return false;
        }
        $sql = SqlBuilder::buildUpdateSql($table, $data, $where, $option);
        $stmt = $this->_dao->prepare($sql);
        $params = $this->buildParams($data, $where);
        $this->bindParams($stmt, $params);
        if ($stmt->execute()) {
            return $stmt->affected_rows;
        }
        return false;
    }

    public function delete($table, $where, $option = '')
    {
        if (empty($where)) {
            return false;
        }
        $sql = SqlBuilder::buildDeleteSql($table, $where, $option);
        $stmt = $this->_dao->prepare($sql);
        $params = $this->buildParams($where);
        $this->bindParams($stmt, $params);
        if ($stmt->execute()) {
            return $stmt->affected_rows;
        }
        return false;
    }

    public function insert($table, array $data)
    {
        if (empty($data)) {
            return false;
        }
        $sql = SqlBuilder::buildInsertSql($table, $data);
        $stmt = $this->_dao->prepare($sql);
        $params = $this->buildParams($data);
        $this->bindParams($stmt, $params);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    public function batchInsert($table, array $data)
    {
        if (empty($data)) {
            return false;
        }
        $sql = SqlBuilder::buildInsertSql($table, $data[0]);
        $stmt = $this->_dao->prepare($sql);
        try {
            $this->beginTransaction();
            foreach ($data as $row) {
                $params = $this->buildParams($row);
                $this->bindParams($stmt, $params);
                $stmt->execute();
            }
            $this->commit();
            return $stmt->affected_rows;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }

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

    protected function buildParams()
    {
        $arr = null;
        $args = func_get_args();
        if ($args) {
            $arr = array();
            foreach ($args as $arg) {
                if (!is_array($arg)) {
                    continue;
                }
                $arr = array_merge($arr, $arg);
            }
        }
        return $arr;
    }

    /**
     * @param Statement $stmt
     * @param array $params
     */
    protected function bindParams(Statement $stmt, array $params)
    {
        if ($params) {
            $arrParam[] = str_repeat('s', count($params));
            $arrParam = array_merge($arrParam, $params);
            call_user_func_array(array($stmt, 'bind_param'), $this->makeRefVal($arrParam));
        }
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

}