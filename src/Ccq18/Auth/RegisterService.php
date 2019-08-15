<?php

namespace Ccq18\Auth;


use App\Models\Repositories\UserRepository;
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
        $user = resolve(UserRepository::class)->register($data);
        //todo
        // (new UserMailer())->welcome($user);


        event(new Registered($user));

        Auth::guard()->login($user);
        return $user;
    }


}