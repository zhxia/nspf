<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 10:51
 */

namespace Spf\Database;


/**
 * Interface IDaoAdapter
 * @package Spf\Database
 */
interface IDaoAdapter
{
    public function __construct($config);

    public function insertOnUpdate($table, $data, $update);

    public function select($table, $where = '', $order = '', $limit = 20, $offset = 0, $fields = '*');

    public function selectCount($table, $where);

    public function execute($sql);

    public function query($sql);

    public function update($table, $data, $where,$option='');

    public function delete($table, $where,$option='');

    public function insert($table, array $data);

    public function batchInsert($table, array $data);

    public function beginTransaction();

    public function commit();

    public function rollback();
}