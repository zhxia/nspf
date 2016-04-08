<?php

/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/2/25
 * Time: 16:00
 */
namespace Spf\Libraries\Http;


class HttpCurl implements IHttp
{
    private $mh = null;

    /**
     * http请求
     * @param $method
     * @param $url
     * @param array $data
     * @param int $connectTimeout
     * @param int $timeout
     * @param array $header
     * @param string $proxy
     * @param bool $needHeader
     * @return mixed
     */
    public function sendRequest($method, $url, $data = array(), $header = array(), $proxy = '', $needHeader = false, $connectTimeout = 5, $timeout = 5)
    {
        $ch = $this->initCurl($method, $url, $data, $connectTimeout, $timeout, $header, $proxy, $needHeader);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 异步http请求
     * @param $method
     * @param $url
     * @param array $data
     * @param int $connectTimeout
     * @param int $timeout
     * @param null $callback
     * @param null $header
     * @param string $proxy
     * @param bool $needHeader
     */
    public function sendAsyncRequest($method, $url, $data = array(), $callback = null, $header = array(), $proxy = '', $needHeader = false, $connectTimeout = 5, $timeout = 5)
    {
        if (!$this->mh) {
            $this->mh = curl_multi_init();
        }
        $ch = $this->initCurl($method, $url, $data, $connectTimeout, $timeout, $header, $proxy, $needHeader);
        curl_multi_add_handle($this->mh, $ch);
        //开始执行
        do {
            $mrc = curl_multi_exec($this->mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($done = curl_multi_info_read($this->mh)) {
            $result = $done['result'];
            $ch = $done['handle'];
            if ($result === CURLE_OK) { //请求正常返回
                if (is_callable($callback)) {
                    $resp = curl_multi_getcontent($ch);
                    call_user_func($callback, $resp);
                }
            }
            curl_multi_remove_handle($this->mh, $ch);
            curl_close($ch);
        }
    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @param int $connectTimeout
     * @param int $timeout
     * @param array $header array('Content-Type: application/json')
     * @param string $proxy 127.0.0.1:8080
     * @param bool $needHeader
     * @return resource
     */
    private function initCurl($method, $url, $data = array(), $connectTimeout = 5, $timeout = 5, $header = array(), $proxy = '', $needHeader = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $method = strtoupper($method);
        if ($method == 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $fixedHeader = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
            'Connection: close',
        );
        $header = array_merge($header, $fixedHeader);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($proxy) {
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $needHeader);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        return $ch;
    }

}