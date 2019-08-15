<?php

namespace Ccq18\Auth;

class AuthUtil
{

    public function getJumpUrlWithToken($fromUrl)
    {
        $token = md5(uniqid() . rand());
        \Cache::put($this->key($token), auth()->user(), 3);


        return build_url($fromUrl, ['ticket' => $token]);
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






}