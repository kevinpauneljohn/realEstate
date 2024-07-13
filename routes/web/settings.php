<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('settings',[\App\Http\Controllers\SettingsController::class,'settings'])->name('settings');
    Route::post('/hide-sensitive-content',[\App\Http\Controllers\SettingsController::class,'hideSensitiveContent'])->name('hide.content');
});
