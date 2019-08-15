<?php

namespace Ccq18\Auth;


use App\Models\Repositories\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordService
{
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $password
     * @return void
     */
    public function resetPassword($user, $password)
    {
        resolve(UserRepository::class)->resetPassword($user, $password);

        event(new PasswordReset($user));


    }
}