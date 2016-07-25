<?php

/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/7/25
 * Time: 14:51
 */
namespace Jobs;

use Spf\Core\Job;

/**
 *  eg:   php application/lanucher.php Jobs\\TestJob param1 param2 param3 ...
 * Class TestJob
 * @package Jobs
 */
class TestJob extends Job
{
    protected function execute()
    {
        print_r(func_get_args());
    }
}