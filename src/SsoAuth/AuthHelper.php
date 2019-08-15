<?php

namespace SsoAuth;


use Util\Http;

class AuthHelper
{
    protected $http;
    protected $baseUrl;

    public function __construct($authServer)
    {
        $this->http = new Http($authServer);
        $this->baseUrl = $authServer;
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
        return $this->http->getJson("/user/{$id}");
    }

    public function getUserByTicket($ticket)
    {
        return $this->http->getJson('auth-token', ['ticket' => $ticket]);
    }
}