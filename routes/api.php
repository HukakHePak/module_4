<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

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

Route::post('/login', 'App\Http\Controllers\AuthorizationConroller@auth');


Route::middleware(['auth:api'])->namespace('App\Http\Controllers')->group(function () {
    Route::delete('/login', 'AuthorizationConroller@out');

    Route::get('/{type}', 'ListController@select');
    Route::get('/search/{category}?q={q}', 'ListController@search');

    Route::apiResource('machines', 'MachinesController');
    Route::post('/verify-compatibility', 'MachinesController@verify');
});

Route::get('/images/{id}', App\Http\Controllers\ImagesController::class);

Route::get('/unauthorized/error', function(Request $request) {
    if($request->bearerToken()) {
        return response()->json(['message' => 'Неверный токен'], 403 );
    }
    return response()->json(['message' => 'Необходима аутентификация'], 401);
})->name('unauth');
