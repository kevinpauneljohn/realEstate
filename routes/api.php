<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::post('/api-login','Api\AuthController@authenticate')->middleware(['role:client','cors:api']);

Route::middleware(['auth:api','client'])->group(function(){
    Route::post('/api-login','Api\AuthController@authenticate')->name('login');
    Route::resource('leads-api','LeadsApiController');
});

Route::get('/test', function (Request $request) { dd($request->header()); });
//Route::resource('leads-api','LeadsApiController');
