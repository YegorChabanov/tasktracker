<?php

use Illuminate\Http\Request;
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

//Auth routes
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');

Route::middleware('auth:api')->group(function() {
    //User routes
    Route::apiResource('user', 'Api\UserController')->except('store');

    //Task routes
    Route::apiResource('task', 'Api\TaskController');
    Route::put('task/{id}/change-status', 'Api\TaskController@changeTaskStatus')->where('id', '[0-9]+');
    Route::put('task/{id}/change-user', 'Api\TaskController@changeTaskUser')->where('id', '[0-9]+');
});

