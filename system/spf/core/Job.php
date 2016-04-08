<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/29
 * Time: 14:40
 */

namespace Spf\Core;


abstract class Job
{
    function __construct()
    {

    }

    function __destruct()
    {

    }

    abstract protected function run();

}