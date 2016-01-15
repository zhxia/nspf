<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/14
 * Time: 10:28
 */

namespace Spf\Core;


class Plugin
{
    const STEP_ROUTER_STARTUP = 1;
    const STEP_ROUTER_SHUTDOWN = 2;
    const STEP_DISPATCH_LOOP_STARTUP = 3;
    const STEP_DISPATCH_STARTUP = 4;
    const STEP_DISPATCH_SHUTDOWN = 5;
    const STEP_DISPATCH_LOOP_SHUTDOWN = 6;
    const STEP_PRE_RESPONSE = 7;

    public function routerStartup(Request $request, Response $response)
    {
    }

    public function routerShutdown(Request $request, Response $response)
    {
    }

    public function dispatchLoopStartup(Request $request, Response $response)
    {
    }

    public function dispatchStartup(Request $request, Response $response)
    {
    }

    public function dispatchShutdown(Request $request, Response $response)
    {
    }

    public function dispatchLoopShutdown(Request $request, Response $response)
    {
    }

    public function preResponse(Request $request, Response $response)
    {
    }
}