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
Route::post('login', 'authen\AuthenticateController@login');
Route::post('signUp', 'authen\SignUpController@postCreate');

Route::post('card/create', 'CardController@createCard');
Route::post('card/delete', 'CardController@destroy');
Route::post('card/update', 'CardController@updateCard');
Route::post('card/all', 'CardController@getCards');
Route::post('card/search', 'CardController@searchCards');

Route::get('pay/checkCard', 'PayController@checkCardExist');

Route::post('pay','PayController@postPayOff');

Route::post('bill/all','BillController@getBills');
Route::post('bill/complete','BillController@getBillComplete');
Route::post('bill/detail','BillController@getBillDetail');
		


