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
    const TABLE_NAME='user';
    public function getList()
    {
       $rows=$this->getDB()->select(self::TABLE_NAME,array('id>'=>1));
        print_r($rows);
    }

}