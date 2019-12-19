<?php

namespace Ccq18\SsoAuth\Laravel\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SsoAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        auth()->shouldUse('sso');
        /** @var \Illuminate\Auth\SessionGuard $guard */
        $guard = auth()->guard();
        //认证失败 跳转到登录页
        if (!$guard->check()) {
            //尝试认证
            $token = request('ticket');
            if(!empty($token)){
                //当前连接去除ticket
                $url = rtrim($request->fullUrlWithQuery(['ticket' => null]), '?');
                //校验
                $isAuth  = $guard->attempt(['ticket' => $request->get('ticket')]);
                if ($isAuth) {
                    return redirect($url);
                }
            }
            return redirect(resolve(\Ccq18\SsoAuth\AuthHelper::class)->getLoginUrl());
        }

        return $next($request);
    }
}