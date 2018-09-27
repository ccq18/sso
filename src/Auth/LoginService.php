<?php

namespace Ccq18\Auth;

use Illuminate\Cache\RateLimiter;
use App\Http\Controllers\Controller;
use Auth;
use Ccq18\Auth\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Lang;

class LoginService
{

    protected $maxAttempts;
    protected $decayMinutes;

    public function __construct($usernameKey = 'email', $maxAttempts = 5, $decayMinutes = 1)
    {
        $this->usernameKey = $usernameKey;
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;
    }
    //usernameKey对应的键值
    //password
    // remember

    public function login(Request $request)
    {
        $limiter = app(RateLimiter::class);
        $throttleKey = Str::lower($request->input($this->usernameKey)) . '|' . $request->ip();;
        //校验登录错误次数 超出限制则禁止登录
        if ($this->hasTooManyLoginAttempts($throttleKey)) {
            event(new Lockout($request));

            $seconds = $limiter->availableIn(
                $throttleKey
            );

            throw ValidationException::withMessages([
                $this->usernameKey => [Lang::get('auth.throttle', ['seconds' => $seconds])],
            ])->status(423);
        }
        //尝试登录
        if (!$this->attemptLogin($request)) {

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $limiter->hit(
                $throttleKey, $this->decayMinutes
            );

            throw ValidationException::withMessages([
                $this->usernameKey => [trans('auth.failed')],
            ]);
        }

        $request->session()->regenerate();
        $limiter->clear($throttleKey);
    }


    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  string $throttleKey
     * @return bool
     */
    public function hasTooManyLoginAttempts($throttleKey)
    {

        return app(RateLimiter::class)->tooManyAttempts(
            $throttleKey, $this->maxAttempts, $this->decayMinutes
        );
    }


    /**
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = array_merge($request->only($this->usernameKey, 'password'), ['is_active' => 1]);

        return Auth::guard()->attempt(
            $credentials, $request->has('remember')
        );
    }


}