<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes...
Route::post('auth/login', ['uses'=>'Auth\AuthController@postLogin']);
Route::get('auth/login',  ['uses'=>'Auth\AuthController@getLogin',  'as'=>'login']);
Route::get('auth/logout', ['uses'=>'Auth\AuthController@getLogout', 'as'=>'logout']);

Route::get('/home', [
     'middleware' => 'auth',
     'uses' =>function () {
                return view('layout');
            }
    ]);

Route::group(['middleware' => 'auth'], function()
{
    Route::post('/api/card/up', 'CardController@up'); // пополнение баланса
    Route::resource('/api/card', 'CardController');
    Route::resource('/api/user', 'UserController');
    Route::controller('/api/transaction', 'TransactionController');
});






