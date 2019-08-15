<?php


Route::get('/', 'HomeController@welcome');


// Route::any('/logout', 'AuthController@logout')->name('logout');
Route::any('/sso/logout', function (){
    auth('sso')->logout();
    return redirect(resolve(SsoAuth\AuthHelper::class)->getLogoutUrl(build_url('/')));
});
Route::group(['middleware' => ['ssoauth']], function () {
    Route::get('/home', 'HomeController@home')->name('home');
});