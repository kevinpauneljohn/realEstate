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
use App\User;
use App\UserRankPoint;
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
Route::put('/user/change-password',[\App\Http\Controllers\UserController::class,'userChangePassword'])->name('change.password')->middleware(['auth','permission:change password']);

/*leads*/
Route::get('/assigned-to-me',[\App\Http\Controllers\LeadController::class,'assignedPage'])->name('assigned.leads.mine')->middleware(['auth','permission:view assigned lead']);
Route::post('/assign-leads',[\App\Http\Controllers\LeadController::class,'assignTo'])->name('assign.leads')->middleware(['auth','permission:assign leads']);
Route::get('/leads','LeadController@index')->name('leads.index')->middleware(['auth','permission:view lead']);
Route::get('/leads-list','LeadController@lead_list')->name('leads.list')->middleware(['auth','permission:view lead']);
Route::get('/assigned-leads-list','LeadController@assignedLeadList')->name('assigned.leads.list')->middleware(['auth','permission:view lead|view assigned lead']);
Route::get('/leads-list/{user}','LeadController@downLine_lead_list')->name('user.leads.list')->middleware(['auth','permission:view down line leads']);
Route::get('/leads/create','LeadController@create')->name('leads.create')->middleware(['auth','permission:add lead']);
Route::post('/leads/save','LeadController@store')->name('leads.store')->middleware(['auth','permission:add lead']);
Route::get('/leads/{lead}','LeadController@show')->name('leads.show')->middleware(['auth','permission:view lead','onlyAssignedLeads']);
Route::get('/leads/{lead}/edit','LeadController@edit')->name('leads.edit')->middleware(['auth','permission:edit lead','onlyAssignedLeads']);
Route::put('/leads/{lead}','LeadController@update')->name('leads.update')->middleware(['auth','permission:edit lead','onlyAssignedLeads']);
Route::delete('/leads/{lead}','LeadController@destroy')->name('leads.destroy')->middleware(['auth','permission:delete lead','onlyAssignedLeads']);
Route::post('/leads/get','LeadController@getLeads')->name('leads.get')->middleware(['auth','permission:view lead']);
Route::post('/leads/status','LeadController@getLeadStatus')->name('leads.status')->middleware('auth','permission:view lead');
Route::post('/leads/mark','LeadController@markAsImportant')->name('leads.important')->middleware('auth','permission:view lead');
Route::post('/leads/status/update','LeadController@updateLeadStatus')->name('leads.status.update')->middleware('auth','permission:edit lead');
Route::get('/lead/general/update','LeadController@generalLeadStatusUpdate')->name('lead.status.general.update');
Route::get('/reserved/{lead_id}',[\App\Http\Controllers\LeadController::class,'reserved'])->name('lead.reserved.units')->middleware(['auth','permission:view sales','onlyAssignedLeads']);
Route::get('/reserved-units/{lead_id}',[\App\Http\Controllers\LeadController::class,'reservedUnits'])->name('reserved.units')->middleware(['auth','permission:view sales','onlyAssignedLeads']);

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
//Route::get('/model-units','ModelUnitController@index')->name('model.units.index')->middleware(['auth','permission:view model unit']);
Route::put('/model-units/{model}','ModelUnitController@update')->name('model.update')->middleware(['auth','permission:edit model unit']);
Route::delete('/model-units/{model}','ModelUnitController@destroy')->name('model.destroy')->middleware(['auth','permission:delete model unit']);
Route::post('/model-units','ModelUnitController@store')->name('model.units.store')->middleware(['auth','permission:add model unit']);
Route::get('/model-units-list','ModelUnitController@model_unit_list')->name('model.units.list')->middleware(['auth','permission:view model unit']);
Route::get('/project-model-units/{project_id}','ModelUnitController@project_model_unit')->name('projects.model.units')->middleware(['auth']);
Route::get('/project-model-unit-list/{project_id}','ModelUnitController@project_model_unit_list')->name('projects.model.units.list')->middleware(['auth','permission:view model unit']);
Route::post('/model-unit-details/{model}','ModelUnitController@getModelUnitDetails')->name('model.unit.details')->middleware(['auth','permission:view model unit|edit model unit']);

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
Route::delete('/sales/{sale}','SalesController@destroy')->name('sales.destroy')->middleware(['auth','permission:delete sales']);
Route::post('/sales/payment-date/{sale}',[\App\Http\Controllers\SalesController::class,'savePaymentDate'])->name('sales.save.payment.date')->middleware(['auth','permission:add sales|edit sales']);
Route::get('/sales/due-date/{sale}',[\App\Http\Controllers\SalesController::class,'getSalesDueDate'])->name('sales.due.date')->middleware(['auth','permission:view sales']);

/*commissions*/
Route::get('/commissions/{user}','CommissionController@index')->name('commissions.index')->middleware(['auth','permission:add commissions']);
Route::post('/commissions','CommissionController@store')->name('commissions.store')->middleware(['auth','permission:add commissions']);
Route::get('/commissions-list/{user}','CommissionController@commission_list')->name('commissions.list')->middleware(['auth','permission:view commissions']);
Route::get('/upline-commission/{project}','CommissionController@getUpLineCommissionOnAProject')->name('commissions.upline.projectId')->middleware(['auth','permission:view commissions']);

//Route::get('/test',function(){
//    $collection = collect([1, 2, 3, 4]);
//
//    $filtered = $collection->filter(function ($value, $key) {
//        return $value > 2;
//    });
//
//    return $filtered->all();
//})->middleware(['auth']);

//Route::post('/test',function(Request $request){
//
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
Route::post('/requirement/save-drive',[\App\Http\Controllers\ClientRequirementsController::class,'saveDriveLink'])->name('requirements.save.drive')->middleware(['auth','permission:edit client requirements|add client requirements']);
Route::get('/requirement-template/{template_id}',[\App\Http\Controllers\RequirementTemplateController::class,'show'])->name('requirement.template.show')->middleware(['auth','permission:view client requirements']);
Route::post('/duplicate/{template_id}',[\App\Http\Controllers\RequirementController::class,'duplicate'])->name('duplicate.requirements')->middleware(['auth','permission:duplicate requirements']);


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


/*wallet*/
Route::get('/wallet','WalletController@index')->name('wallet.index')->middleware(['auth','permission:view wallet']);
Route::get('/wallet-list','WalletController@total_wallet_amount')->name('wallet.list')->middleware(['auth','permission:view wallet']);
Route::post('/get-source','WalletController@source')->name('money.source')->middleware(['auth','permission:view wallet|withdraw money']);
Route::post('/withdraw','WalletController@withdrawMoney')->name('money.withdraw')->middleware(['auth','permission:view wallet|withdraw money']);

/*cash request*/
Route::get('/cash-request','CashRequestController@index')->name('cash.index')->middleware(['auth','role:super admin']);
Route::get('/cash-request-list','CashRequestController@cashRequestList')->name('cash.list')->middleware(['auth','role:super admin']);
Route::post('/cash-approval-result','CashRequestController@cash_approval')->name('cash.approval')->middleware(['auth','role:super admin']);
Route::get('/cash-request-history/{id}','CashRequestController@show')->name('cash.history.show')->middleware(['auth']);

/*amount withdrawal request*/
Route::get('/cash-request/amount-withdrawal/{id}','AmountWithdrawalRequestController@show')->name('withdrawal.show')->middleware(['auth','role:super admin']);
Route::get('/transaction-history','TransactionHistoryController@index')->name('transaction.index')->middleware(['auth']);
Route::get('/transaction-list','TransactionHistoryController@transaction_list')->name('transaction.list')->middleware(['auth']);
Route::post('/set-lead-graph-display','DashboardController@setDisplayLeadGraphStatus')->name('lead.graph.status.display')->middleware(['auth']);

/*rank*/
Route::get('/rank','RankController@index')->name('rank.index')->middleware(['auth','permission:view rank|add rank|edit rank|delete rank']);
Route::post('/rank','RankController@store')->name('rank.store')->middleware(['auth','permission:add rank']);
Route::get('/rank-list','RankController@rank_list')->name('rank.list')->middleware(['auth','permission:view rank']);
Route::post('/rank/{id}','RankController@getRank')->name('rank.get')->middleware(['auth','permission:view rank']);
Route::put('/rank/{id}','RankController@update')->name('rank.update')->middleware(['auth','permission:edit rank']);
Route::delete('/rank/{id}','RankController@destroy')->name('rank.destroy')->middleware(['auth','permission:delete rank']);

Route::get('/contest','ContestController@index')->name('contest.index')->middleware(['auth','permission:view contest']);
Route::get('/contest-list','ContestController@contest_list')->name('contest.list')->middleware(['auth','permission:view contest']);
Route::post('/contest','ContestController@store')->name('contest.index')->middleware(['auth','permission:add contest']);

Route::post('/display-task-change',[\App\Http\Controllers\ScrumController::class,'changeDisplayTask'])->name('display.task.change')->middleware(['auth','permission:view task']);
Route::post('/display-my-task-change',[\App\Http\Controllers\ScrumController::class,'changeDisplayMyTask'])->name('display.my.task.change')->middleware(['auth','permission:view task']);
Route::put('/tasks/agent','ScrumController@updateAgent')->name('tasks.update.agent')->middleware(['auth','permission:view task|edit task']);
Route::get('/tasks','ScrumController@index')->name('tasks.index')->middleware(['auth','permission:view task']);
Route::post('/tasks','ScrumController@store')->name('tasks.store')->middleware(['auth','permission:add task']);
Route::get('/tasks-list','ScrumController@task_list')->name('tasks.list')->middleware(['auth','permission:view task']);
Route::get('/my-tasks-list','ScrumController@myTaskList')->name('my.tasks.list')->middleware(['auth','permission:view task']);
Route::get('/tasks/{id}','ScrumController@show')->name('tasks.show')->middleware(['auth','permission:view task']);
Route::put('/tasks/{id}','ScrumController@update')->name('tasks.update')->middleware(['auth','permission:view task|edit task']);
Route::get('/tasks/overview/{id}','ScrumController@overview')->name('tasks.overview')->middleware(['auth','permission:view task']);
Route::get('/my-tasks',[\App\Http\Controllers\ScrumController::class,'myTasks'])->name('task.mine')->middleware(['auth','permission:view task']);
Route::put('/start-tasks/{task}',[\App\Http\Controllers\ScrumController::class,'changeTaskStatus'])->name('task.start')->middleware(['auth','permission:view task']);
Route::post('/reopen-task',[\App\Http\Controllers\ScrumController::class,'reopenTask'])->name('task.reopen')->middleware(['auth','permission:view task']);
Route::get('/display-remarks/{task_id}',[\App\Http\Controllers\ScrumController::class,'displayRemarks'])->name('remarks.display')->middleware(['auth','permission:view task']);
Route::delete('/tasks/{task_id}',[\App\Http\Controllers\ScrumController::class,'destroy'])->name('tasks.destroy')->middleware(['auth','permission:delete task']);
Route::get('/task-status/update',[\App\Http\Controllers\ScrumController::class,'taskStatus']);

Route::post('/child-tasks','ChildTaskController@store')->name('child.task.store')->middleware(['auth','permission:add task']);
Route::get('/child-tasks/{id}','ChildTaskController@show')->name('child.task.show')->middleware(['auth','permission:view task']);

Route::put('/clients/update-role/{client}','ClientController@updateRole')->name('client.update.role')->middleware(['auth','permission:edit client']);
Route::get('/clients','ClientController@index')->name('client.index')->middleware(['auth','permission:view client']);
Route::post('/clients','ClientController@store')->name('client.store')->middleware(['auth','permission:add client']);
Route::get('/clients-list','ClientController@client_list')->name('client.list')->middleware(['auth','permission:view client']);
Route::get('/client/{client}','ClientController@show')->name('client.show')->middleware(['auth','permission:view client']);
Route::get('/client-info/{client}','ClientController@edit')->name('client.edit')->middleware(['auth','permission:view client']);
Route::put('/clients/{client}','ClientController@update')->name('client.update')->middleware(['auth','permission:edit client']);
Route::delete('/clients/{client}','ClientController@destroy')->name('client.destroy')->middleware(['auth','permission:delete client']);

Route::post('/check-list','CheckListController@store')->name('checklist.store')->middleware(['auth','permission:add checklist']);
Route::get('/check-list/{client}','CheckListController@check_list')->name('checklist.client')->middleware(['auth','permission:view checklist']);

Route::post('/documentation','DocumentationController@store')->name('documentation.store')->middleware(['auth','permission:add documentation']);
Route::get('/documentation/{id}','DocumentationController@document_list')->name('document.list')->middleware(['auth','permission:view documentation']);

/*builders*/
Route::get('/builders','BuilderController@index')->name('builder.index')->middleware(['auth','permission:view builder']);
Route::post('/builders','BuilderController@store')->name('builder.store')->middleware(['auth','permission:add builder']);
Route::get('/builders/{builder}','BuilderController@show')->name('builder.show')->middleware(['auth','permission:view builder']);
Route::get('/builders-list','BuilderController@builderList')->name('builder.list')->middleware(['auth','permission:view builder']);
Route::get('/builders/{builder}/edit','BuilderController@edit')->name('builder.edit')->middleware(['auth','permission:edit builder']);
Route::put('/builders/{builder}','BuilderController@update')->name('builder.update')->middleware(['auth','permission:edit builder']);
Route::delete('/builders/{builder}','BuilderController@destroy')->name('builder.destroy')->middleware(['auth','permission:delete builder']);

Route::post('/add-member/builder','BuilderMemberController@addMember')->name('builder.member.add')->middleware(['auth','permission:add builder member']);
Route::get('/builder/{builder}/member','BuilderMemberController@member')->name('builder.member.list')->middleware(['auth','permission:view builder member']);
Route::delete('/builder/{id}/member','BuilderMemberController@destroy')->name('builder.member.destroy')->middleware(['auth','permission:delete builder member']);
/*end of builders*/

/*dream home guide projects*/
Route::get('/dhg-projects','ClientProjectController@index')->name('dhg.project.index')->middleware(['auth','permission:view dhg project']);
Route::post('/dhg-projects','ClientProjectController@store')->name('dhg.project.store')->middleware(['auth','permission:add dhg project']);
Route::get('/dhg-projects/{project}','ClientProjectController@show')->name('dhg.project.show')->middleware(['auth','permission:view dhg project']);
Route::get('/dhg-projects-list','ClientProjectController@dhgProjectList')->name('dhg.project.list')->middleware(['auth','permission:view dhg project']);
Route::get('/dhg-projects/{project}/edit','ClientProjectController@edit')->name('dhg.project.edit')->middleware(['auth','permission:edit dhg project']);
Route::put('/dhg-projects/{project}','ClientProjectController@update')->name('dhg.project.update')->middleware(['auth','permission:edit dhg project']);
Route::delete('/dhg-projects/{project}','ClientProjectController@destroy')->name('dhg.project.destroy')->middleware(['auth','permission:delete dhg project']);
Route::post('/dhg-project-access','ClientProjectController@checkCredentialForDelete')->name('dhg.project.check.access')->middleware(['auth','permission:delete dhg project']);
/*end dream home guide projects*/

/*dream home client payment*/
Route::get('/client-payment/{payment}/edit','ClientPaymentController@edit')->name('client.payment.edit')->middleware(['auth','permission:view client payment|edit client payment']);
Route::get('/client-payment/{project}','ClientPaymentController@clientPaymentList')->name('client.payment.list')->middleware(['auth','permission:view payment']);
Route::post('/client-payment','ClientPaymentController@store')->name('client.payment.store')->middleware(['auth','permission:add payment']);
Route::get('/client-payment/edit/layout/{id}','ClientPaymentController@paymentModal')->name('client.edit.payment.modal')->middleware(['auth','permission:edit client payment']);
Route::post('/admin/credential','ClientPaymentController@adminCredential')->name('admin.check.credential')->middleware(['auth','permission:edit client payment']);
Route::put('/client-payment/{id}','ClientPaymentController@update')->name('client.payment.update')->middleware(['auth','permission:edit client payment']);
Route::delete('/client-payment/{id}','ClientPaymentController@destroy')->name('client.payment.destroy')->middleware(['auth','permission:delete client payment']);
Route::post('/client-payment-access','ClientPaymentController@checkCredentialForDelete')->name('client.payment.check.access')->middleware(['auth','permission:delete client payment']);
/*end dream home client payment*/

Route::get('/developers','DevelopersController@index')->name('developers.index')->middleware(['auth','role:super admin']);

Route::get('/client-requirements/sales/{sales_id}',[\App\Http\Controllers\ClientRequirementsController::class,'salesRequirements']);
Route::put('/client-requirements/check-document',[\App\Http\Controllers\ClientRequirementsController::class,'checkDocument']);
Route::resource('client-requirements','ClientRequirementsController');

Route::get('/task-checklist/{task_id}/display',[\App\Http\Controllers\TaskChecklistController::class,'displayChecklist'])->name('checklist.display');
Route::put('/task-checklist/{checklist_id}/update/checklist',[\App\Http\Controllers\TaskChecklistController::class,'updateChecklist'])->name('checklist.update');
Route::resource('task-checklist','TaskChecklistController');

Route::get('/action-taken/{checklist_id}/display',[\App\Http\Controllers\ActionTakenController::class,'actionTakenList'])->name('action.taken.display');
Route::resource('action-taken','ActionTakenController');
Route::get('test', function () {

    $user = [
        'name' => 'kevin paunel',
        'info' => 'sample email reminder'
    ];
//
//    \Illuminate\Support\Facades\Mail::to('johnkevinpaunel@gmail.com')->send(new \App\Mail\MyTestMail($user));
//    return \Carbon\Carbon::create(2018, 1, 31, 0, 0, 0)-
//    $sales = \App\Sales::find(46);
//    return $sales->location;
//        $month = now()->month;
//        foreach (\App\PaymentReminder::whereMonth('schedule',$month)->where('completed',false)->get() as $reminder){
//            if(today()->diffInDays($reminder->schedule, false) === 5)
//            {
//                echo today()->day.' 5 day before true<br/>';
//            }elseif (today()->diffInDays($reminder->schedule, false) === 1){
//                echo today()->day.' 1 day before true<br/>';
//            }elseif (today()->diffInDays($reminder->schedule, false) === 0){
//                echo 'today => true '.today()->format('Y-m-d').'<br/>'.$reminder->schedule.'<br/>';
//                if(today()->format('Y-m-d') === $reminder->schedule)
//                {
//                    echo 'match';
//                    \App\PaymentReminder::where('schedule',today()->format('Y-m-d'))->update(['completed' => true]);
//                }
//            }
////            echo 'Schedule: '.$reminder->schedule.' - '.$reminder->amount.' Date Today: '.now()->format('Y-m-d').'  = '.today()->diffInDays($reminder->schedule, false).'<br/>';
////            return $reminder->sales->lead;
//        }
    \Illuminate\Support\Facades\Artisan::call('reminder:run');
    return \Illuminate\Support\Facades\Artisan::output();
});

Route::get('/staycation/availability',[\App\Http\Controllers\Staycation\StaycationAppointmentController::class,'availability'])->name('staycation.availability');
Route::resource('staycation','Staycation\StaycationClientController');

Route::get('/payment-reminder','PaymentReminderController');


