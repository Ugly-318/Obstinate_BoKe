<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('help', 'StaticPagesController@help')->name('help');
Route::get('about', 'StaticPagesController@about')->name('about');

//注册路由
Route::get('signup', 'UsersController@create')->name('signup');

//用户相关路由
Route::resource('users', 'UsersController');

//用户登录退出相关路由
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

// 激活账户相关路由
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

// 忘记密码(重置)相关路由
Route::get('password/reset', 'PasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'PasswordController@sendRequestLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'PasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'PasswordController@reset')->name('password.update');

// 微博相关路由
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

// 关注列表和粉丝列表相关路由
Route::get('users/{user}/followings', 'UsersController@followings')->name('users.followings');
Route::get('users/{user}/followers', 'UsersController@followers')->name('users.followers');

// 关注和取消关注相关路由
Route::post('users/followers/{user}', 'FollowersController@store')->name('followers.store');
Route::delete('users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');
