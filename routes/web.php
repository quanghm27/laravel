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

Route::resource('admin/shop','CuaHangController');
Route::resource('admin/payOff','PayOffController');

Route::group(['middleware' => 'cors'], function()
{
	Route::resource('admin/shop', 'CuaHangController');
});

Route::resource('admin/shop', 'CuaHangController');

Route::resource('admin/product','ProductController');

Route::get('admin/', function(){
	return view('admin.layout.index');
});


Route::resource('auth/login', 'authen\AuthenticateController');
Route::post('auth/login', 'authen\AuthenticateController@login');
Route::post('card/create', 'CardController@createCard');
Route::post('payOff','PayOffController@postPayOff');



