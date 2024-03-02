<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::post('/assign-permission-to-user',[\App\Http\Controllers\UserController::class,'assignPermissionToUser'])->name('assign-permission-to-user');
    Route::get('/user-permissions/{user}',[\App\Http\Controllers\UserController::class,'userPermissions'])->name('user-permissions');
    Route::post('/remove-user-permission/{user}/{permission}',[\App\Http\Controllers\UserController::class,'removePermission'])->name('remove-user-permission');
});
