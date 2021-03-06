<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Ccq18\Auth\AuthUtil;
use Ccq18\Auth\LoginService;
use Ccq18\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


use Ccq18\Auth\RegisterService;
use Illuminate\Support\Facades\Validator;


use Ccq18\Auth\ResetPasswordService;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{


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

        return redirect($request->get('fromUrl','/'));
    }


    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if(empty(request('fromUrl'))){
            throw new \DomainException('链接非法');
        }
        return view('auth.login');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $this->validate($request, [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);
        $loginer = new LoginService('email');
        $username = $request->input('email');
        $password = $request->input('password');
        $hasRemember = $request->has('remember');
        $ip = $request->ip();
        $loginer->login($username, $password, $hasRemember, $ip);

        // $this->flash('欢迎回来！', 'success');
        // $authUrl = session()->get('authUrl');
        $fromUrl = session()->get('fromUrl');
        return redirect()->intended(resolve(AuthUtil::class)->getJumpUrlWithToken( $fromUrl));


    }




    //register

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        Validator::make($request->all(), [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ])->validate();
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        resolve(RegisterService::class)->registerUser($data);
        $authUrl = session()->get('authUrl');
        $fromUrl = session()->get('fromUrl');
        return redirect(resolve(AuthUtil::class)->getJumpUrlWithToken($fromUrl));
    }

    //ForgotPasswordController

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', trans($response))
            : back()->withErrors(
                ['email' => trans($response)]
            );
    }



//reset

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string|null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ], []);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        //重置密码
        $response =  resolve(PasswordBroker::class)->reset($credentials, function ($user, $password) {
                resolve(ResetPasswordService::class)->resetPassword($user, $password);
                $this->guard()->login($user);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        $fromUrl = session()->get('fromUrl');
        return $response == Password::PASSWORD_RESET
            ? redirect(resolve(AuthUtil::class)->getJumpUrlWithToken($fromUrl))
                ->with('status', trans($response))
            : redirect()->back()
                        ->withInput($request->only('email'))
                        ->withErrors(['email' => trans($response)]);
    }

}