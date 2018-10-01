<?php

namespace App\Models\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function register($data)
    {

        return User::create([
            'name'               => $data['name'],
            'email'              => $data['email'],
            'avatar'             => '/images/avatars/default.png',
            'confirmation_token' => str_random(40),
            'password'           => bcrypt($data['password']),
            'api_token'          => str_random(60),
            'settings'           => ['city' => ''],
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  User $user
     * @param  string $password
     * @return void
     */
    public function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        return $user;

    }
}