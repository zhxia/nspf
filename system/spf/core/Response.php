<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:16
 */

namespace Spf\Core;


class Response
{
    /**
     *
     * 删除cookie
     * @param string $name
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param string $httponly
     */
    public function removeCookie($name, $path = NULL, $domain = NULL, $secure = FALSE, $httponly = FALSE)
    {
        $this->set_cookie($name, NULL, -3600, $path, $domain, $secure, $httponly);
    }

    /**
     *
     * 设置cookie
     * @param string $name
     * @param string $value
     * @param string $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param string $httponly
     */
    public function setCookie($name, $value, $expire = 0, $path = NULL, $domain = NULL, $secure = FALSE, $httponly = FALSE)
    {
        $expire = $expire ? time() + intval($expire) : 0;
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }


    public function setContentType($content_type, $charset = 'utf-8')
    {
        $this->setHeader('Content-type', "{$content_type};charset={$charset}");
    }

    public function redirect($url, $permanent = false)
    {
        header("Location:$url", TRUE, $permanent ? 301 : 302);
        exit(0);
    }

    public function setCacheControl($value)
    {
        $this->setHeader('Cache-Control', $value);
    }

    /**
     *
     * 设置页面header信息
     * @param string $name
     * @param string $value
     * @param string $http_response_code
     * @param string $separator
     */
    public function setHeader($name, $value, $http_response_code = NULL, $separator = ':')
    {
        header("{$name}{$separator} {$value}", TRUE, $http_response_code);
    }

    public function addHeader($name, $value, $http_response_code = NULL, $separator = ':')
    {
        header("{$name}{$separator} {$value}", FALSE, $http_response_code);
    }

}