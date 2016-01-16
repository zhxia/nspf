<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/15
 * Time: 16:54
 */

namespace Interceptors;


use Spf\Core\Interceptor;

class Auth extends Interceptor
{
    public function before()
    {
//        echo 'Before<br/>';
        return self::STEP_CONTINUE;
    }

    public function after()
    {
//        echo 'After<br/>';
        return self::STEP_CONTINUE;
    }

}