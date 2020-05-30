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

use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/','LandingPageController');
Route::get('/home', function (){
    return redirect(route('dashboard'));
})->name('home');

//Route::group(['middleware' => 'throttle'], function (){
    Route::get('/login','CustomAuth\LoginController@login_form')->name('login');
    Route::post('/login','CustomAuth\LoginController@authenticate')->name('authenticate');
//});


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
Route::get('/users/{user}/profile','UserController@profile')->name('users.profile')->middleware(['auth','permission:view user']);
Route::get('/users/{user}/agents','UserController@agents')->name('users.agents')->middleware(['auth','permission:view user']);
Route::get('/users/{user}/commissions','UserController@commissions')->name('users.commissions')->middleware(['auth','permission:view user']);
Route::put('/users/{user}','UserController@update')->name('users.update')->middleware(['auth','permission:edit user']);
Route::delete('/users/{user}','UserController@destroy')->name('users.destroy')->middleware('auth','permission:delete user');
Route::get('/users/downline/{upline}','UserController@downLines')->name('users.down.lines')->middleware(['auth','permission:view down lines']);

/*leads*/
Route::get('/leads','LeadController@index')->name('leads.index')->middleware(['auth','permission:view lead']);
Route::get('/leads-list','LeadController@lead_list')->name('leads.list')->middleware(['auth','permission:view lead']);
Route::get('/leads-list/{user}','LeadController@downLine_lead_list')->name('user.leads.list')->middleware(['auth','permission:view down line leads']);
Route::get('/leads/create','LeadController@create')->name('leads.create')->middleware(['auth','permission:add lead']);
Route::post('/leads/save','LeadController@store')->name('leads.store')->middleware(['auth','permission:add lead']);
Route::get('/leads/{lead}','LeadController@show')->name('leads.show')->middleware(['auth','permission:view lead']);
Route::get('/leads/{lead}/edit','LeadController@edit')->name('leads.edit')->middleware(['auth','permission:edit lead']);
Route::put('/leads/{lead}','LeadController@update')->name('leads.update')->middleware(['auth','permission:edit lead']);
Route::delete('/leads/{lead}','LeadController@destroy')->name('leads.destroy')->middleware(['auth','permission:delete lead']);
Route::post('/leads/get','LeadController@getLeads')->name('leads.get')->middleware(['auth','permission:view lead']);
Route::post('/leads/status','LeadController@getLeadStatus')->name('leads.status')->middleware('auth','permission:view lead');
Route::post('/leads/mark','LeadController@markAsImportant')->name('leads.important')->middleware('auth','permission:view lead');
Route::post('/leads/status/update','LeadController@updateLeadStatus')->name('leads.status.update')->middleware('auth','permission:edit lead');
Route::get('/lead/general/update','LeadController@generalLeadStatusUpdate')->name('lead.status.general.update');

/*Log touches*/
Route::post('/logs','LogTouchController@store')->name('logs.store')->middleware('auth','permission:edit lead');
Route::get('/logs/{id}','LogTouchController@show')->name('logs.show')->middleware('auth','permission:add lead');
Route::put('/logs/{id}','LogTouchController@update')->name('logs.update')->middleware(['auth','permission:edit lead']);
Route::delete('/logs/{id}','LogTouchController@destroy')->name('logs.destroy')->middleware('auth','permission:edit lead');

Route::post('/website-link','WebsiteLinkController@store')->name('website.link.store')->middleware(['auth','permission:add lead']);
Route::delete('/website-link/{id}','WebsiteLinkController@destroy')->name('website.link.destroy')->middleware(['auth','permission:add lead']);

/*Lead Notes*/
Route::post('/lead-notes','LeadNotesController@store')->name('leadNotes.store')->middleware(['auth','permission:add lead','throttle:50,1']);
Route::put('/lead-notes/{note}','LeadNotesController@update')->name('leadNotes.update')->middleware(['auth','permission:edit lead']);
Route::delete('/lead-notes/{note}','LeadNotesController@destroy')->name('leadNotes.destroy')->middleware(['auth','permission:edit lead']);

/*projects*/
Route::get('/projects','ProjectController@index')->name('projects.index')->middleware(['auth','permission:view project']);
Route::post('/projects','ProjectController@store')->name('projects.store')->middleware(['auth','permission:add project']);
Route::get('/projects-list','ProjectController@project_list')->name('projects.list')->middleware(['auth','permission:view project']);
Route::get('/projects/{project}','ProjectController@show')->name('projects.show')->middleware(['auth','permission:view project']);
Route::put('/projects/{project}','ProjectController@update')->name('projects.update')->middleware(['auth','permission:edit project']);
Route::delete('/projects/{project}','ProjectController@destroy')->name('projects.destroy')->middleware('auth','permission:delete project');
Route::get('/projects/{project}/profile','ProjectController@profile')->name('projects.profile')->middleware('auth','permission:view project');

/*model units*/
Route::get('/model-units','ModelUnitController@index')->name('model.units.index')->middleware(['auth','permission:view model unit']);
Route::post('/model-units','ModelUnitController@store')->name('model.units.store')->middleware(['auth','permission:add model unit']);
Route::get('/model-units-list','ModelUnitController@model_unit_list')->name('model.units.list')->middleware(['auth','permission:view model unit']);
Route::get('/project-model-units/{project_id}','ModelUnitController@project_model_unit')->name('projects.model.units')->middleware(['auth']);

/*lead activity schedule*/
Route::get('/leads-activity-schedule/{lead}','LeadActivityController@lead_activity_list')->name('leads.activity.list')->middleware(['auth','permission:view lead']);
Route::post('/leads-activities','LeadActivityController@store')->name('leads.activity.store')->middleware(['auth','permission:add lead']);
Route::get('/leads-schedule/{date}','LeadActivityController@checkSchedule')->name('leads.activity.schedule')->middleware(['auth','permission:view lead']);
Route::get('/leads-activity/{id}/edit','LeadActivityController@edit')->name('leads.activity.edit')->middleware(['auth','permission:edit lead']);
Route::put('/leads-activity/{id}','LeadActivityController@update')->name('leads.activity.update')->middleware(['auth','permission:edit lead']);
Route::delete('/leads-activity/{id}','LeadActivityController@destroy')->name('leads.activity.destroy')->middleware('auth','permission:delete lead');
Route::post('/leads-activity-schedule/','LeadActivityController@getSchedule')->name('leads.schedule')->middleware('auth','permission:view lead');

/*schedule*/
Route::get('/schedule','ScheduleController@index')->name('schedules.index')->middleware(['auth','permission:view schedule']);
Route::get('/schedules-list','ScheduleController@schedule_list')->name('schedules.list')->middleware(['auth','permission:view schedule']);
Route::post('/update-schedule-status','ScheduleController@updateStatus')->name('schedule.status.update')->middleware(['auth','permission:edit schedule']);

/*sales*/
Route::get('/sales','SalesController@index')->name('sales.index')->middleware(['auth','permission:view sales','checkCommission']);
Route::post('/sales','SalesController@store')->name('sales.store')->middleware(['auth','permission:add sales','checkCommission']);
Route::get('/sales-list','SalesController@sales_list')->name('sales.list')->middleware(['auth','permission:view sales','checkCommission']);
Route::get('/user-sales-list/{id}','UserController@user_sales_list')->name('users.sales.list')->middleware(['auth','permission:view sales','checkCommission']);
Route::get('/sales/{sale}','SalesController@show')->name('sales.show')->middleware(['auth','permission:view sales','checkCommission']);
Route::get('/sales/edit/{sale}','SalesController@edit')->name('sales.edit')->middleware(['auth','permission:edit sales','checkCommission']);
Route::get('/get-model-unit-details/{modelUnit}','SalesController@model_unit_details')->name('model.units.details')->middleware(['auth','checkCommission']);
Route::put('/sale-status-update','SalesController@updateSaleStatus')->name('sales.status.update')->middleware(['auth','permission:edit sales','checkCommission']);
Route::put('/sales/{sale}','SalesController@update')->name('sales.update')->middleware(['auth','permission:edit sales','checkCommission']);
Route::get('/add-sales','SalesController@create')->name('sales.create')->middleware(['auth','permission:add sales','checkCommission']);

/*commissions*/
Route::get('/commissions/{user}','CommissionController@index')->name('commissions.index')->middleware(['auth','permission:add commissions']);
Route::post('/commissions','CommissionController@store')->name('commissions.store')->middleware(['auth','permission:add commissions']);
Route::get('/commissions-list/{user}','CommissionController@commission_list')->name('commissions.list')->middleware(['auth','permission:view commissions']);
Route::get('/upline-commission/{project}','CommissionController@getUpLineCommissionOnAProject')->name('commissions.upline.projectId')->middleware(['auth','permission:view commissions']);

Route::get('/test',function(){
    $notifications = \App\Notification::all();

    foreach ($notifications as $notification)
    {
        echo $notification->data->lead_id.' - '.\App\Lead::find($notification->data->lead_id)->fullname.'<br/>';
    }

})->middleware(['auth']);

//Route::post('/test',function(Request $request){
//    event(new NotificationEvent($request->test));
//})->name('test');

/*change password*/
Route::get('/change-password','UserController@changePassword')->name('users.change.password')->middleware(['auth']);
Route::put('/change-password','UserController@changePasswordValidate')->name('users.change.password.update')->middleware(['auth']);

/*requirements*/
Route::get('/requirements','RequirementController@index')->name('requirements.index')->middleware(['auth','permission:view requirements']);
Route::post('/requirements','RequirementController@store')->name('requirements.store')->middleware(['auth','permission:add requirements']);
Route::get('/requirements-list','RequirementController@requirements_list')->name('requirements.list')->middleware(['auth','permission:view requirements']);
Route::post('/get-requirements','RequirementController@getRequirements')->name('requirements.get')->middleware(['auth','permission:view requirements']);
Route::put('/requirements/{requirement}','RequirementController@update')->name('requirements.update')->middleware(['auth','permission:edit requirements']);
Route::delete('/requirements/{requirement}','RequirementController@destroy')->name('requirements.destroy')->middleware('auth','permission:delete requirements');
Route::get('/get-requirements-by-template/{template}','SalesController@getRequirementsByTemplate')->name('get.template.requirements')->middleware(['auth','permission:view requirements']);
Route::put('/save-requirements-template','SalesController@save_requirements_template')->name('save.requirements.template')->middleware(['auth']);
Route::get('/upload-requirements/{sale}','SalesController@requirements')->name('sales.upload.requirements')->middleware(['auth','permission:upload requirements']);
Route::post('/upload-requirements-image','SalesController@upload_requirements')->name('requirements.image.upload')->middleware(['auth','permission:upload requirements']);
Route::post('/template/{template}','RequirementController@show')->name('requirements.show')->middleware(['auth','permission:view requirement template']);

/*thresholds*/
Route::get('/thresholds','RequestController@index')->name('thresholds.index')->middleware(['auth','permission:view request']);
Route::get('/thresholds/list','RequestController@requestList')->name('thresholds.list')->middleware(['auth','permission:view request']);
Route::get('/requests/{request}','RequestController@show')->name('requests.show')->middleware(['auth','checkLid','permission:view request']);
Route::put('/requests/{request}','RequestController@update')->name('requests.update')->middleware(['auth','permission:approve request']);
Route::post('/requests/status','RequestController@setRequestStatus')->name('requests.status')->middleware(['auth']);
Route::post('/requests/number','RequestController@getRequestNumber')->name('requests.tickets')->middleware(['auth','permission:view request']);
Route::post('/requests/open','RequestController@openRequest')->name('requests.open')->middleware(['auth','permission:view request']);

/*priorities*/
Route::get('/priorities','PriorityController@index')->name('priorities.index')->middleware(['auth','permission:view priority']);
Route::get('/priorities/list','PriorityController@priorityList')->name('priorities.list')->middleware(['auth','permission:view priority']);
Route::post('/priorities','PriorityController@store')->name('priorities.store')->middleware(['auth','permission:add priority']);
Route::put('/priorities/{priority}','PriorityController@update')->name('priorities.update')->middleware(['auth','permission:edit priority']);
Route::delete('/priorities/{priority}','PriorityController@destroy')->name('priorities.delete')->middleware(['auth','permission:delete priority']);
Route::get('/update-priority-status',function(){
    \Illuminate\Support\Facades\Artisan::call('update:priority');
    //
});

/*notifications*/
Route::get('/notify-user','NotificationsController@notify')->name('reminder');
Route::get('/notifications','NotificationsController@index')->name('notifications.index')->middleware(['auth']);
Route::get('/notifications-list','NotificationsController@notifications_list')->name('notifications.list')->middleware(['auth']);
Route::put('/notifications/{notification}','NotificationsController@update')->name('notifications.update')->middleware(['auth']);
Route::put('/notifications-bulk','NotificationsController@markBulk')->name('notifications.bulk.update')->middleware(['auth']);

/*actions*/
Route::get('/actions','ActionController@index')->name('actions.index')->middleware(['auth','permission:view action']);
Route::get('/actions/list','ActionController@actionList')->name('actions.list')->middleware(['auth','permission:view action']);
Route::post('/actions','ActionController@store')->name('actions.store')->middleware(['auth','permission:add action']);
Route::get('/action/get/{action}','ActionController@getAction')->name('actions.get')->middleware(['auth','permission:view action']);
Route::put('/actions/{action}','ActionController@update')->name('actions.update')->middleware(['auth','permission:edit action']);
Route::delete('/actions/{action}','ActionController@destroy')->name('actions.destroy')->middleware(['auth','permission:delete action']);

/*canned message*/
Route::get('/canned/create','CannedMessageController@create')->name('canned.create')->middleware(['auth','permission:add canned message']);
Route::put('/canned/{id}','CannedMessageController@update')->name('canned.update')->middleware(['auth','permission:edit canned message']);
Route::post('/canned','CannedMessageController@store')->name('canned.store')->middleware(['auth','permission:add canned message']);
Route::get('/canned-message-list','CannedMessageController@cannedMessageList')->name('canned.message.list')->middleware(['auth','permission:view canned message']);
Route::delete('/canned/{id}','CannedMessageController@destroy')->name('canned.destroy')->middleware(['auth','permission:delete canned message']);
Route::get('/canned/{id}','CannedMessageController@show')->name('canned.show')->middleware(['auth','permission:view canned message']);

/*canned category*/
Route::post('/canned-category','CannedCategoryController@store')->name('canned.category.store')->middleware(['auth','permission:add canned message']);
Route::put('/canned-category/{id}','CannedCategoryController@update')->name('canned.category.update')->middleware(['auth','permission:add canned message']);
Route::get('/canned-category-list','CannedCategoryController@cannedCategoryList')->name('canned.category.list')->middleware(['auth','permission:view canned message']);
Route::delete('/canned-category/{id}','CannedCategoryController@destroy')->name('canned.category.destroy')->middleware(['auth','permission:add canned message']);

Route::get('/computations','ComputationController@index')->name('computations.index')->middleware(['auth','permission:add computation']);
Route::get('/computations-list','ComputationController@computation_list')->name('computations.list')->middleware(['auth','permission:view computation']);
Route::post('/computations','ComputationController@store')->name('computations.store')->middleware(['auth','permission:add computation']);
Route::post('/computations/{computation}','ComputationController@show')->name('computations.show')->middleware(['auth','permission:edit computation']);
Route::put('/computations/{computation}','ComputationController@update')->name('computations.update')->middleware(['auth','permission:edit computation']);
Route::delete('/computations/{computation}','ComputationController@destroy')->name('computations.destroy')->middleware(['auth','permission:delete computation']);
Route::post('/sample-computation','ComputationController@sampleComputations')->name('computations.sample')->middleware(['auth','permission:view computation']);

Route::post('/calculator','CalculatorController@calculator')->name('calculator.template')->middleware(['auth']);

Route::get('/contacts','ContactController@index')->name('contacts.index')->middleware(['auth','permission:view contacts']);
Route::post('/contacts','ContactController@store')->name('contacts.store')->middleware(['auth','permission:add contacts']);
Route::get('/contacts-list','ContactController@contact_list')->name('contacts.list')->middleware(['auth','permission:view contacts']);
Route::get('/contacts/{contact}','ContactController@show')->name('contacts.show')->middleware(['auth','permission:view contacts']);
Route::put('/contacts/{contact}','ContactController@update')->name('contacts.update')->middleware(['auth','permission:edit contacts']);
Route::delete('/contacts/{contact}','ContactController@destroy')->name('contacts.destroy')->middleware(['auth','permission:delete contacts']);
//Route::post('/all-contacts','ContactController@contacts')->name('contacts.all')->middleware(['auth','permission:view contacts']);

