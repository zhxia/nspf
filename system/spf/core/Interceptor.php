<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/14
 * Time: 12:31
 */

namespace Spf\Core;


class Interceptor
{
    const STEP_CONTINUE = 1;
    const STEP_BREAK = 2;
    const STEP_EXIT = 3;

    public function before()
    {
        return self::STEP_CONTINUE;
    }

    public function after()
    {
        return self::STEP_CONTINUE;
    }
}