<?php

namespace App\Http\Controllers;

use App\Models\User;
use SsoAuth\AuthHelper;
use Illuminate\Http\Request;

class AuthApiController  extends Controller
{
    public function __construct()
    {
        $sign =resolve(AuthHelper::class)->getSign(\request()->all());
        if(request('sign') != $sign){
            throw new \DomainException('签名校验不通过');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function authToken(Request $request)
    {
        return resolve(AuthHelper::class)->getUserByToken($request->get('ticket'));
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