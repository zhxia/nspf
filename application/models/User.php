<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 14:03
 */

namespace Models;


use Spf\Core\Model;

class UserModel extends Model
{
    const TABLE_NAME = 'user';

    public function getList()
    {
        $rows = $this->getDB()->select(self::TABLE_NAME, array('id>=' => 1));
        return $rows;
    }

    public function getTotal()
    {
        return $this->getDB()->selectCount(self::TABLE_NAME, array('id>=' => 1));
    }

    public function updateUser()
    {
        return $this->getDB('master')->update(self::TABLE_NAME, array('name' => '李四'), array('id' => 1), 'limit 1');
    }

    public function addUser($data)
    {
        return $this->getDB('master')->insert(self::TABLE_NAME, $data);
    }

    public function addUsers($data)
    {
        return $this->getDB('master')->batchInsert(self::TABLE_NAME, $data);
    }
}