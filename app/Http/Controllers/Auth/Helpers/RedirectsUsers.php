<?php

namespace App\Http\Controllers\Auth\Helpers;

use Ccq18\Auth\AuthHelper;

trait RedirectsUsers
{

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return resolve(AuthHelper::class)->getJumpUrlWithToken();;
    }
}
