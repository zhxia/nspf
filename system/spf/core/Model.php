<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:49
 */

namespace Spf\Core;


use Spf\Database\DataAccessFactory;

class Model
{
    /**
     * @param bool|false $master
     * @return bool|\Spf\Database\IDataAccess
     */
    protected function getDB($master = false)
    {
        return DataAccessFactory::getInstance()->getDao($master);
    }

}