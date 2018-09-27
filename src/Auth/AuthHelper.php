<?php

namespace Ccq18\Auth;

class AuthHelper
{

    public function getJumpUrlWithToken()
    {
        $token = md5(uniqid() . rand());
        \Cache::put($this->key($token), auth()->user(), 3);
        $authUrl = session()->get('authUrl');
        $fromUrl = session()->get('fromUrl');

        return $this->buildUrl($authUrl, ['fromUrl' => $fromUrl, 'token' => $token]);
    }

    public function getUserByToken($token)
    {
        $rs = \Cache::get($this->key($token));
        \Cache::forget($this->key($token));

        return $rs;
    }

    protected function key($token)
    {
        return 'auth_' . $token;
    }




    protected function buildUrl($path, $parameters = [])
    {

        $output = $this->getUrlParams($path);
        $parm_str = http_build_query(array_merge($output, $parameters));

        return url(ltrim($this->clearUrlcan($path), '\/') . (empty($parm_str) ? "" : '?' . $parm_str));
    }

    protected function getUrlParams($path)
    {
        $info = parse_url($path);
        $params = isset($info['query']) ? $info['query'] : "";
        parse_str($params, $output);

        return $output;
    }

    protected function clearUrlcan($url)
    {
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }

        return $url;
    }

}