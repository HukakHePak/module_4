<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', 'AuthorizationConroller@auth');
    Route::delete('/login', 'AuthorizationConroller@out')->middleware('auth:api');
});

Route::middleware('auth:api')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/{type}', 'ListController@select');
    Route::get('/search/{category}?q={q}', 'ListController@search');

    Route::apiResource('machines', 'MachinesController');
    Route::post('/verify-compatibility', 'MachinesController@verify');
});

Route::get('/images/{id}', 'ImagesController@select');
