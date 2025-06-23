<?php

use App\Http\Controllers\FindingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('/display-task-change',[\App\Http\Controllers\ScrumController::class,'changeDisplayTask'])->name('display.task.change')->middleware(['auth','permission:view task']);
    Route::post('/display-my-task-change',[\App\Http\Controllers\ScrumController::class,'changeDisplayMyTask'])->name('display.my.task.change')->middleware(['auth','permission:view task']);
    Route::put('/tasks/agent','ScrumController@updateAgent')->name('tasks.update.agent')->middleware(['auth','permission:view task|edit task']);
    Route::put('/tasks/watcher','ScrumController@updateWatcher')->name('tasks.update.watcher')->middleware(['auth','permission:view task|edit task']);
    Route::get('/tasks','ScrumController@index')->name('tasks.index')->middleware(['auth','permission:view task']);
    Route::post('/tasks','ScrumController@store')->name('tasks.store')->middleware(['auth','permission:add task']);
    Route::get('/tasks-list','ScrumController@task_list')->name('tasks.list')->middleware(['auth','permission:view task']);
    Route::get('/my-tasks-list','ScrumController@myTaskList')->name('my.tasks.list')->middleware(['auth','permission:view task']);
    Route::get('/my-watched-list','ScrumController@myWatchedList')->name('my.watched.list')->middleware(['auth','permission:view task']);
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
    Route::get('/task-calendar',[\App\Http\Controllers\ScrumController::class,'viewTaskCalendar'])->name('task.calendar');
    Route::get('/get-all-tasks',[\App\Http\Controllers\ScrumController::class,'taskCalendarApi'])->name('get.all.tasks');
});
