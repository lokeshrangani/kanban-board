<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');
    });
});

Route::group(['prefix' => 'task', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'TaskController@index');
    Route::post('create', 'TaskController@create');
    Route::post('update', 'TaskController@update');
    Route::post('delete', 'TaskController@destroy');
});
