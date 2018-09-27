<?php

namespace Ccq18\Auth;


use App\Models\User;
use Auth;
use Illuminate\Auth\Events\Registered;

class RegisterService
{

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data[name email password]
     * @return User
     */
    public function registerUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => '/images/avatars/default.png',
            'confirmation_token' => str_random(40),
            'password' => bcrypt($data['password']),
            'api_token' => str_random(60),
            'settings' => ['city' => ''],
        ]);
        //todo
        // (new UserMailer())->welcome($user);


        event(new Registered($user));

        Auth::guard()->login($user);
        return $user;
    }


}