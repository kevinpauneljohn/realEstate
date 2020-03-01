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

/*Roles*/
Route::get('/roles-list','RolesController@roles_list')->name('roles.list')->middleware(['auth','permission:view role']);
Route::get('/roles','RolesController@index')->name('roles.index')->middleware(['auth','permission:view role']);
Route::post('/roles','RolesController@store')->name('roles.store')->middleware(['auth','permission:add role']);
Route::put('/roles/{role}','RolesController@update')->name('roles.update')->middleware(['auth','permission:edit role']);
Route::delete('/roles/{role}','RolesController@destroy')->name('roles.destroy')->middleware(['auth','permission:delete role']);

/*Permissions*/
Route::get('/permissions','PermissionController@index')->name('permissions.index')->middleware(['auth','permission:view permission']);
Route::get('/permission-list','PermissionController@permissions_list')->name('permission.list')->middleware(['auth','permission:view permission']);
Route::post('/permissions','PermissionController@store')->name('permissions.store')->middleware(['auth','permission:add permission']);
Route::post('/permission-roles','PermissionController@getPermissionRoles')->name('permissions.roles')->middleware(['auth','permission:view role|view permission']);
Route::put('/permissions/{permission}','PermissionController@update')->name('permissions.update')->middleware(['auth','permission:edit permission']);
Route::delete('/permissions/{permission}','PermissionController@destroy')->name('permissions.destroy')->middleware('auth','permission:delete permission');

/*users*/
Route::get('/users','UserController@index')->name('users.index')->middleware(['auth','permission:view user']);
Route::post('/users','UserController@store')->name('users.store')->middleware(['auth','permission:add user']);
Route::get('/users-list','UserController@userList')->name('users.list')->middleware(['auth','permission:view user']);
Route::get('/users/{user}','UserController@show')->name('users.show')->middleware(['auth','permission:view user']);
Route::put('/users/{user}','UserController@update')->name('users.update')->middleware(['auth','permission:edit user']);
Route::delete('/users/{user}','UserController@destroy')->name('users.destroy')->middleware('auth','permission:delete user');

/*leads*/
Route::get('/leads','LeadController@index')->name('leads.index')->middleware(['auth','permission:view lead']);
Route::get('/leads-list','LeadController@lead_list')->name('leads.list')->middleware(['auth','permission:view lead']);
Route::get('/leads/create','LeadController@create')->name('leads.create')->middleware(['auth','permission:add lead']);
Route::post('/leads/save','LeadController@store')->name('leads.store')->middleware(['auth','permission:add lead']);
Route::get('/leads/{lead}','LeadController@show')->name('leads.show')->middleware(['auth','permission:view lead']);
Route::get('/leads/{lead}/edit','LeadController@edit')->name('leads.edit')->middleware(['auth','permission:edit lead']);
Route::put('/leads/{lead}','LeadController@update')->name('leads.update')->middleware(['auth','permission:edit lead']);
Route::delete('/leads/{lead}','LeadController@destroy')->name('leads.destroy')->middleware(['auth','permission:delete lead']);

/*projects*/
Route::get('/projects','ProjectController@index')->name('projects.index')->middleware(['auth','permission:view project']);
Route::post('/projects','ProjectController@store')->name('projects.store')->middleware(['auth','permission:add project']);
Route::get('/projects-list','ProjectController@project_list')->name('projects.list')->middleware(['auth','permission:view project']);
Route::get('/projects/{project}','ProjectController@show')->name('projects.show')->middleware(['auth','permission:view project']);
Route::put('/projects/{project}','ProjectController@update')->name('projects.update')->middleware(['auth','permission:edit project']);
Route::delete('/projects/{project}','ProjectController@destroy')->name('projects.destroy')->middleware('auth','permission:delete project');
Route::get('/projects/{project}/profile','ProjectController@profile')->name('projects.profile')->middleware('auth','permission:view project');

/*lead activity schedule*/
Route::get('/leads-activity-schedule/{lead}','LeadActivityController@lead_activity_list')->name('leads.activity.list')->middleware(['auth','permission:view lead']);
Route::post('/leads-activities','LeadActivityController@store')->name('leads.activity.store')->middleware(['auth','permission:add lead']);
Route::get('/leads-schedule/{date}','LeadActivityController@checkSchedule')->name('leads.activity.schedule')->middleware(['auth','permission:view lead']);
Route::get('/leads-activity/{id}/edit','LeadActivityController@edit')->name('leads.activity.edit')->middleware(['auth','permission:edit lead']);
Route::put('/leads-activity/{id}','LeadActivityController@update')->name('leads.activity.update')->middleware(['auth','permission:edit lead']);
Route::delete('/leads-activity/{id}','LeadActivityController@destroy')->name('leads.activity.destroy')->middleware('auth','permission:delete lead');

/*schedule*/
Route::get('/schedule','ScheduleController@index')->name('schedules.index')->middleware(['auth','permission:view schedule']);
Route::get('/schedules-list','ScheduleController@schedule_list')->name('schedules.list')->middleware(['auth','permission:view schedule']);
Route::post('/update-schedule-status','ScheduleController@updateStatus')->name('schedule.status.update')->middleware(['auth','permission:edit schedule']);

