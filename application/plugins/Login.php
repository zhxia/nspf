<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/14
 * Time: 11:49
 */

namespace Plugins;

use Spf\Core\Plugin;
use Spf\Core\Request;
use Spf\Core\Response;

class Login extends Plugin
{
    public function routerStartup(Request $request, Response $response)
    {
        echo 'Router Startup!<br/>';
    }

    public function routerShutdown(Request $request, Response $response)
    {
        echo 'Router Shutdown!<br/>';
    }


}