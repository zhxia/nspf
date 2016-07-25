<?php

/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/7/25
 * Time: 14:51
 */
namespace Jobs;

use Spf\Core\Job;

class TestJob extends Job
{
    protected function execute()
    {
        print_r(func_get_args());
    }
}