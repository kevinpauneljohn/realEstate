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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();
Route::get('/','LandingPageController');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login','CustomAuth\LoginController@login_form')->name('login');
Route::post('/login','CustomAuth\LoginController@authenticate')->name('authenticate');

Route::group(['middleware' => ['auth']], function(){
    Route::get('/dashboard','DashboardController@dashboard')->name('dashboard');
    Route::post('/logout','CustomAuth\LoginController@logout')->name('logout');
});

Route::get('/roles','RolesController@index')->name('roles.index')->middleware(['auth']);
