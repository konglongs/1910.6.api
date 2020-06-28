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



//接口
Route::post('/api/reg','Api\UserController@reg');//注册
Route::post('/api/login','Api\UserController@login');//登录
Route::get('/api/list','Api\UserController@list');//个人中心
