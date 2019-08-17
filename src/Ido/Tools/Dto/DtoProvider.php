<?php

namespace Ido\Tools\Dto;


use Illuminate\Support\ServiceProvider;

class DtoProvider extends ServiceProvider
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
        $formater = function ($value, $format) {
            settype($value, $format);
            return $value;
        };

        foreach (['boolean', 'bool', 'integer', 'int', 'float', 'double', 'string'] as $format) {
            DtoBuilder::addFormater($format, $formater);
        }

    }
}