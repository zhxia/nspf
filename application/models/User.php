<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 14:03
 */

namespace Models;


use Spf\Core\Model;

class User extends Model
{
    public function getList()
    {
        $sql = 'select * from user';
        $query = $this->getDB()->query($sql);
        if ($query) {
            $result = $query->fetchAll();
            print_r($result);
            return $result;
        }
        return false;
    }
}