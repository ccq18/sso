<?php


Route::get('/', 'HomeController@welcome');
Route::get('test', function (){
    $r['r'] = uniqid();
    $r['sign'] = resolve(\SsoAuth\AuthHelper::class)->getSign($r) ;

    resolve(\SsoAuth\AuthHelper::class)->getUserById($r) ;

    return ;
});


// Route::any('/logout', 'AuthController@logout')->name('logout');
Route::any('/sso/logout', function (){
    auth('sso')->logout();
    return redirect(resolve(SsoAuth\AuthHelper::class)->getLogoutUrl(build_url('/')));
});
Route::group(['middleware' => ['ssoauth']], function () {
    Route::get('/home', 'HomeController@home')->name('home');
});