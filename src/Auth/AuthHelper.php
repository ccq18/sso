<?php

namespace Ccq18\Auth;


class AuthHelper
{

    public function getJumpUrlWithToken()
    {
        $token = $this->generateAuthToken();
        $authUrl = session()->get('authUrl');
        //request()->get('authUrl');
        $fromUrl = session()->get('fromUrl');
        return $this->buildUrl($authUrl, ['fromUrl' => $fromUrl, 'token' => $token]);
    }

    public function generateAuthToken()
    {
        $token = md5(uniqid().rand());
        \Cache::put('auth_' . $token, auth()->user(), 3);

        return $token;
    }

    public function getUserByToken($token)
    {
        $rs = \Cache::get('auth_' . $token);
        \Cache::forget('auth_' . $token);
        return $rs;
    }


    function buildUrl($path, $parameters = [])
    {

        $output = $this->getUrlParams($path);
        $parm_str = http_build_query(array_merge($output, $parameters));

        return url(ltrim($this->clearUrlcan($path), '\/') . (empty($parm_str) ? "" : '?' . $parm_str));
    }

    function getUrlParams($path)
    {
        $info = parse_url($path);
        $params = isset($info['query']) ? $info['query'] : "";
        parse_str($params, $output);

        return $output;
    }

    function clearUrlcan($url)
    {
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }

        return $url;
    }

}