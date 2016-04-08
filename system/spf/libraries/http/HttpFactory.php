<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/2/25
 * Time: 16:02
 */

namespace Spf\Libraries\Http;


class HttpFactory
{
    /**
     * @param string $name
     * @return null|IHttp
     */
    public static function getHttpClient($name = 'curl')
    {
        if ($name == 'curl') {
            return new HttpCurl();
        } elseif ($name == 'socket') {
            return new HttpSocket();
        }
        return null;
    }
}