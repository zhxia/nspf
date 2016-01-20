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
     * @param string $configKey
     * @return bool|\Spf\Database\IDaoAdapter
     */
    protected function getDB($configKey='slave')
    {
        return DaoAdapterFactory::getInstance()->getDao($configKey);
    }

}