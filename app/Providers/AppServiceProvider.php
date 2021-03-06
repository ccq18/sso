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
            return new \Ccq18\SsoAuth\AuthHelper( env('AUTH_SERVER'),env('API_SECRET'));
        });
        $this->app->alias('ssohelper', \Ccq18\SsoAuth\AuthHelper::class);
    }
}
