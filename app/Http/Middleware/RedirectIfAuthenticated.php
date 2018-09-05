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
        $authUrl = request()->get('authUrl');
        $fromUrl = request()->get('fromUrl');
        if (!empty($authUrl)) {
            session()->put('authUrl', $authUrl);
            session()->put('fromUrl', $fromUrl);

        }
        if (Auth::guard($guard)->check()) {
            $jumpUrl = resolve(AuthHelper::class)->getJumpUrlWithToken();

            return redirect($jumpUrl);
        }

        return $next($request);
    }
}
