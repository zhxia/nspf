<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/4/7
 * Time: 15:00
 */

namespace Spf\Libraries\Http;


interface IHttp
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';

    public function sendRequest($method, $url, $data, $header, $proxy, $needHeader, $connectTimeout, $timeout);

    public function sendAsyncRequest($method, $url, $data, $callback, $header, $proxy, $needHeader, $connectTimeout, $timeout);
}