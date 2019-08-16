<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ssohelper', function ($app) {
            return new \Ido\Tools\SsoAuth\AuthHelper( env('AUTH_SERVER'),env('API_SECRET'));
        });
        $this->app->alias('ssohelper', \Ido\Tools\SsoAuth\AuthHelper::class);
    }
}
