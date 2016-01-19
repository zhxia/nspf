<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:49
 */

namespace Spf\Core;


use Spf\Database\DaoAdapterFactory;

class Model
{

    /**
     * @param bool|false $master
     * @return bool|\Spf\Database\IDaoAdapter
     */
    protected function getDB($master = false)
    {
        return DaoAdapterFactory::getInstance()->getDao($master);
    }

}