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
Route::get('/api/test','Api\UserController@test');//phpinfo
Route::get('/api/my/order','Api\UserController@orders')->middleware('check_pri');

Route::middleware('check_pri','access_brush')->group(function (){

Route::get('/api/a','Api\TestController@a');//
Route::get('/api/b','Api\TestController@b');//

});
//测试
Route::get('/test/test','TestController@test');
Route::get('/test/secret','TestController@secret');
Route::get('/test/www','TestController@www');
Route::get('/test/send_data','TestController@send_data');
Route::get('/test/post_data','TestController@post_data');
Route::get('/test/encrypt1','TestController@encrypt1');
Route::get('/test/rsa/encrypt1','TestController@resEncrypt1');
Route::get('/test/rsa/aaa','TestController@aaa');
Route::get('/test/rsa/rsaSign','TestController@rsaSign');
Route::get('/test/rsa/aaa','TestController@abc');

//考试
Route::get('/work/aaa','Work\WorkController@aaa');
Route::get('/work/bbb','Work\WorkController@bbb');
Route::post('/work/ccc','Work\WorkController@ccc');//防刷
Route::post('/work/ddd','Work\WorkController@ddd')->middleware('fangshua');//防刷

Route::post('/work/login','Work\WorkController@login');
