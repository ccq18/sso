<?php

namespace App\Http\Middleware;

use Ccq18\Auth\AuthHelper;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $fromUrl = request()->get('fromUrl');//认证来源地址
        if (!empty($fromUrl)) {
            session()->put('fromUrl', $fromUrl);

        }
        if (Auth::guard($guard)->check()) {
            $jumpUrl = resolve(AuthHelper::class)->getJumpUrlWithToken($fromUrl);
            return redirect($jumpUrl);
        }


        return $next($request);
    }
}
