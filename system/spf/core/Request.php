<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:16
 */

namespace Spf\Core;


class Request
{
    private $_matches;
    private $_params;
    private $_clientIp;

    public function setRouterMatches($matches)
    {
        $this->_matches = $matches;
    }

    public function getRouterMatches()
    {
        return $this->_matches;
    }

    public function isAjaxRequest()
    {
        return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtoupper($_SERVER["HTTP_X_REQUESTED_WITH"]) == "XMLHTTPREQUEST";
    }

    public function isPostMethod()
    {
        return $this->getMethod() == 'POST' ? TRUE : FALSE;
    }

    public function isGetMethod()
    {
        return $this->getMethod() == 'GET' ? TRUE : FALSE;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getParam($name, $defVal = null, $xss = false)
    {
        $params = $this->getParams();
        return isset($params[$name]) ? trim($params[$name]) : $defVal;
    }

    public function getParams($xss = false)
    {
        if ($this->_params === null) {
            $this->_params = array_merge($_GET, $_POST);
        }
        return $this->_params;
    }

    public function getCookies($xss = false)
    {
        return $_COOKIE;
    }

    public function getCookie($name, $defVal = '', $xss = false)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $defVal;
    }

    /**
     *
     * 检测是否是安全连接
     */
    public function isSecure()
    {
        return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on');
    }

    public function getHttpReferer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /***
     * 获取客户端ip
     */
    public function getClientIp()
    {
        if (!isset($this->_clientIp)) {
            $ip = FALSE;
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                if ($ip) {
                    array_unshift($ips, $ip);
                }
                //排除私有ip
                foreach ($ips as $v) {
                    if (!preg_match('/(10|172\.[16-31]|192\.168)\./', $v)) {
                        $ip = $v;
                        break;
                    }
                }
            }
            if (!$ip) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $this->_clientIp = $ip;
        }
        return $this->_clientIp;
    }
}