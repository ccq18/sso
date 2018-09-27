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
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/auth-token', 'HomeController@authToken')->name('authToken');
Route::post('logout', 'AuthController@logout')->name('logout');
Route::group(['middleware' => 'guest'], function () {
    //登录
    Route::get('login', 'AuthController@showLoginForm')->name('login');
    Route::post('login', 'AuthController@login');
    //注册
    Route::get('register', 'AuthController@showRegistrationForm')->name('register');
    Route::post('register', 'AuthController@register');
    //忘记密码
    Route::group(['prefix' => 'password'], function () {
        //发送验证邮件
        Route::get('/reset', 'AuthController@showLinkRequestForm')->name('password.request');
        Route::post('/email', 'AuthController@sendResetLinkEmail')->name('password.email');
        //重置密码
        Route::get('/reset/{token}', 'AuthController@showResetForm')->name('password.reset');
        Route::post('/reset', 'AuthController@reset');
    });
});
