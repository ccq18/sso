<?php

namespace Ccq18\SsoAuth;

use GuzzleHttp\Client;

class AuthHelper
{
    protected $http;
    protected $baseUrl;

    public function __construct($authServer, $apiSecret)
    {
        $this->http = new Client([
            'timeout' => 2.0,
        ]);
        $this->baseUrl = $authServer;
        $this->apiSecret = $apiSecret;
    }



    public function getLoginUrl($fromUrl = '')
    {
        if (empty($fromUrl)) {
            $fromUrl = $this->getCurrentUri();
        }

        return $this->buildUrl($this->getUrl('/login'), ['fromUrl' => $fromUrl]);
    }

    public function getRegisterUrl($fromUrl = '')
    {
        if (empty($fromUrl)) {
            $fromUrl = $this->getCurrentUri();
        }

        return $this->buildUrl($this->getUrl('/register'), ['fromUrl' => $fromUrl]);
    }

    public function getLogoutUrl($fromUrl = '')
    {
        if (empty($fromUrl)) {
            $fromUrl = $this->getCurrentUri();
        }

        return $this->buildUrl($this->getUrl('/logout'), ['fromUrl' => $fromUrl]);
    }

    public function getUserById($id)
    {
        return $this->getApi("/user/{$id}", []);
    }

    public function getUserByTicket($ticket)
    {
        return $this->getApi('auth-token', ['ticket' => $ticket]);
    }

    public function getSign($arr)
    {
        unset($arr['sign']);
        ksort($arr);
        $str = http_build_query($arr);

        return md5($this->apiSecret . $str);
    }

    function getCurrentUri()
    {

        $url = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $url = 'https://';
        }
        if ($_SERVER['SERVER_PORT'] != '80') {
            $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }

        return $url;

    }

    protected function getApi($url, $params = [])
    {
        $params['_r'] = uniqid();//加入随机干扰
        $params['sign'] = $this->getSign($params);
        $url = $this->getUrl($url);
        $res = $this->http->get($url, ['query' => $params]);

        return json_decode($res->getBody()->getContents(), true);
    }

    protected function postApi($url, $data = [], $params = [])
    {
        $params['_r'] = uniqid();//加入随机干扰
        $params['sign'] = $this->getSign(array_merge($data, $params));
        $url = $this->getUrl($url);
        $res = $this->http->post($url, ['query' => $params, 'form_params' => $data]);

        return json_decode($res->getBody()->getContents(), true);
    }

    protected function buildUrl($path, $newParams = [])
    {

        //解析url参数，合并params为新的k-v 对
        $info = parse_url($path);
        $params = isset($info['query']) ? $info['query'] : "";
        parse_str($params, $output);
        $parm_str = http_build_query(array_merge($output, $newParams));
        //取得path部分
        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }

        return ltrim($path, '\/') . (empty($parm_str) ? "" : '?' . $parm_str);
    }


    protected function getUrl($url)
    {

        return rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');

    }
}