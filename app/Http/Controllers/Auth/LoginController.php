<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{

    protected $decayMinutes = 1;//超时时间
    protected $maxAttempts = 5;//允许最大登录失败次数


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->maxAttempts = config('auth.max_attempts',5);
        $this->decayMinutes = config('auth.decay_minutes',1);

    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $usernameKey = 'email';
        $this->validate($request, [
            $usernameKey => 'required|string',
            'password'        => 'required|string',
        ]);

        $throttleKey = Str::lower($request->input($usernameKey)) . '|' . $request->ip();
        $limiter = app(RateLimiter::class);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($limiter->tooManyAttempts(
            $throttleKey, $this->maxAttempts, $this->decayMinutes
        )) {
            event(new Lockout($request));
            $seconds = $limiter->availableIn($throttleKey);
            throw ValidationException::withMessages([
                $usernameKey => [Lang::get('auth.throttle', ['seconds' => $seconds])],
            ])->status(423);
        }

        if (Auth::guard()->attempt(
            $request->only($usernameKey, 'password'), $request->filled('remember')
        )) {
            $request->session()->regenerate();
            $limiter->clear($throttleKey);

            return redirect()->intended($this->redirectTo);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.

        $limiter->hit(
            $throttleKey, $this->decayMinutes
        );

        throw ValidationException::withMessages([
            $usernameKey => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
