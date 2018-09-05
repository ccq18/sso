<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth-token', 'HomeController@authToken')->name('authToken');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/api/login', 'Auth\LoginController@loginApi');

Route::group(['middleware' => 'guest'], function () {
    //登录
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');

    //注册
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    //密码重置
    Route::group(['prefix' => 'password'], function () {
        Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('/reset/{token}', 'Auth\ForgotPasswordController@showResetForm')->name('password.reset');
        Route::post('/reset', 'Auth\ForgotPasswordController@reset');
    });
});

//
// Auth::routes();
//
Route::get('/home', 'HomeController@index')->name('home');
