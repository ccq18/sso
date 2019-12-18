<?php

namespace Ccq18\SsoAuth\Middleware;

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
            return redirect(resolve(\Ccq18\SsoAuth\AuthHelper::class)->getLoginUrl());
        }

        return $next($request);
    }
}