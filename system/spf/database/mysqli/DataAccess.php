<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:08
 */

namespace Spf\Database\Mysqli;


use Spf\Database\IDataAccess;

class DataAccess implements IDataAccess
{
    public function __construct($config)
    {
        // TODO: Implement __construct() method.
    }

    public function insertOnUpdate($table, $data, $update)
    {
        // TODO: Implement insertOnUpdate() method.
    }

    public function select($table, $where, $order, $limit, $offset, $fields)
    {
        // TODO: Implement select() method.
    }

    public function selectCount($table, $where)
    {
        // TODO: Implement selectCount() method.
    }

    public function execute($sql)
    {
        // TODO: Implement execute() method.
    }

    public function query($sql)
    {
        // TODO: Implement query() method.
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
        // TODO: Implement beginTransaction() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

}