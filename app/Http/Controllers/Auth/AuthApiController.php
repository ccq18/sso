<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Ccq18\Auth\AuthUtil;
use Ccq18\SsoAuth\AuthHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthApiController  extends Controller
{
    public function __construct()
    {
        $sign =resolve(AuthHelper::class)->getSign(\request()->all());
        $_sign = request('sign');
        if(request('sign') != $sign){
            throw new \DomainException("签名校验不通过,request $_sign $sign");
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function authToken(Request $request)
    {
        return resolve(AuthUtil::class)->getUserByToken($request->get('ticket'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser($id)
    {
        return User::query()->find($id);
    }

}