<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/2/25
 * Time: 16:02
 */

namespace Spf\Libraries\Http;


use Spf\Core\Exception;

class HttpSocket implements IHttp
{
    public function sendRequest($method, $url, $data = null, $header = array(), $proxy = '', $needHeader = false, $connectTimeout = 5, $timeout = 5)
    {
        $arrURL = parse_url($url);
        $host = $arrURL['host'];
        $port = isset($arrURL['port']) ? $arrURL['post'] : 80;
        if (strpos($proxy, ':') !== false) {
            list($host, $port) = explode(':', $proxy);
        }
        $fp = fsockopen($host, $port, $errno, $errstr, $connectTimeout);
        if (!$fp) {
            throw  new Exception($errstr, $errno);
        }
        $httpHeader = sprintf('%s %s %s', $method, $url, 'HTTP/1.1') . "\r\n";
        $httpHeader .= 'Host:' . $host . ':' . $port . "\r\n";
        if ($method == self::HTTP_POST) {
            if (is_array($data)) {
                $httpBody = http_build_query($data);
            } else {
                $httpBody = $data;
            }
            $httpHeader .= 'Content-Length: ' . strlen($httpBody) . "\r\n";
        }
        $fixedHeader = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
            'Connection: close',
        );
        $header = array_merge($header, $fixedHeader);
        $httpHeader .= implode("\r\n", $header) . "\r\n\r\n";
        fwrite($fp, $httpHeader);
        if ($method == self::HTTP_POST) {
            fwrite($fp, $httpBody);
        }
        $resp = '';
        while (!feof($fp)) {
            $resp .= fgets($fp, 1024);
        }
        fclose($fp);
        $arrContent = explode("\r\n\r\n", $resp);
        return $needHeader ? $resp : $arrContent[1];
    }

    public function sendAsyncRequest($method, $url, $data, $callback, $header, $proxy, $needHeader, $connectTimeout, $timeout)
    {
        throw new Exception('not supported');
    }

}