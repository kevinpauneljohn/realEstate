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

//Route::post('/login','Api\AuthController@authenticate')->middleware(['role:client','cors:api']);
Route::post('/login','Api\AuthController@authenticate')->name('login')->middleware(['cors:api']);
Route::middleware('auth:api','client')->group(function(){
    Route::resource('leads',\App\Http\Controllers\OpenHouseSeller\LeadsApiController::class);
});
