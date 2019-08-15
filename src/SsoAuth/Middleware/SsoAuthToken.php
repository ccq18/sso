<?php

namespace SsoAuth\Middleware;


use Closure;

class SsoAuthToken
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
        //token认证流程
        if (!$guard->check()) {
            //token认证流程
            $token = request('ticket');
            if(!empty($token)){
                $url = rtrim($request->fullUrlWithQuery(['ticket' => null]), '?');
                $isAuth  = $guard->attempt(['ticket' => $request->get('ticket')]);
                if ($isAuth) {
                    return redirect($url);
                }
            }
        }

        return $next($request);
    }
}