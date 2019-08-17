<?php

namespace Ido\Tools\SsoAuth;


use Ido\Tools\Util\Http;

class AuthHelper
{
    protected $http;
    protected $baseUrl;

    public function __construct($authServer,$apiSecret)
    {
        $this->http = new Http($authServer);
        $this->baseUrl = $authServer;
        $this->apiSecret = $apiSecret;
    }

    private function getUrl($url)
    {

        return rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');

    }

    public function getLoginUrl($fromUrl = '')
    {
        if (empty($fromUrl)) {
            $fromUrl = \Request::getUri();
        }

        return build_url($this->getUrl('/login'), ['fromUrl' => $fromUrl]);
    }

    public function getRegisterUrl($fromUrl = '')
    {
        if (empty($fromUrl)) {
            $fromUrl = \Request::getUri();
        }

        return build_url($this->getUrl('/register'), ['fromUrl' => $fromUrl]);
    }

    public function getLogoutUrl($fromUrl = '')
    {
        if (empty($fromUrl)) {
            $fromUrl = \Request::getUri();
        }

        return build_url($this->getUrl('/logout'), ['fromUrl' => $fromUrl]);
    }

    public function getUserById($id)
    {
        return $this->getApi("/user/{$id}",[]);
    }

    public function getUserByTicket($ticket)
    {
        return $this->getApi('auth-token', ['ticket' => $ticket]);
    }

    public function getSign($arr){
        unset($arr['sign']);
        ksort($arr);
        $str = http_build_query($arr);
        return md5($this->apiSecret.$str);
    }

    protected function getApi($url, $params = [] ){
        $params['_r'] = uniqid();//加入随机干扰
        $params['sign'] = $this->getSign($params);
        return   $this->http->getJson($url, $params );
    }

    protected function postApi($url, $data = [], $params = []){
        $params['_r'] = uniqid();//加入随机干扰
        $params['sign'] = $this->getSign(array_merge($data,$params));
        return   $this->http->postJson($url, $data, $params );
    }
}