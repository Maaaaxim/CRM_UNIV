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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'api.key'], function () {
    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Route::post('/store', 'ApiController@store')->name('store');
        Route::get('/get', 'ApiController@get')->name('get');
    });
});
