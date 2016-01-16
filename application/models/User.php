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
        $sql = 'SELECT * FROM USER';
        $result = $this->getDB()->query($sql);
        var_dump($result);
    }
}