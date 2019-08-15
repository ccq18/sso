<?php

namespace App\Providers;

use Auth;
use Ccq18\Auth\EloquentUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use SsoAuth\SsoUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Auth::provider('my-eloquent', function ($app, $config) {
            return new EloquentUserProvider($this->app['hash'], $config['model']);
        });
        Auth::provider('sso_authorization', function () {
            return new SsoUserProvider();
        });
    }
}
