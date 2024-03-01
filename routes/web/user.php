<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::post('/assign-permission-to-user',[\App\Http\Controllers\UserController::class,'assignPermissionToUser'])->name('assign-permission-to-user');
});
