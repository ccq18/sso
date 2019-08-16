<?php


Route::get('/', 'HomeController@welcome');
Route::get('test', function (){


    return Ido\Tools\Demo\DemoProvider::demo();
});

Route::any('/testDto', 'HomeController@testDto');
Route::any('/sso/logout', function (){
    auth('sso')->logout();
    return redirect(resolve(Ido\Tools\SsoAuth\AuthHelper::class)->getLogoutUrl(build_url('/')));
});
Route::group(['middleware' => ['ssoauth']], function () {
    Route::get('/home', 'HomeController@home')->name('home');
});