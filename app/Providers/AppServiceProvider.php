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
            return new \SsoAuth\AuthHelper( env('APP_URL'));
        });
        $this->app->alias('ssohelper',\SsoAuth\AuthHelper::class);
    }
}
