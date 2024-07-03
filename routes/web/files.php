<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('files',\App\Http\Controllers\FileController::class);
    Route::post('/files/upload', [\App\Http\Controllers\FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/project/{project_id}',[\App\Http\Controllers\FileController::class,'files'])->name('get.project.files');
    Route::get('/files/download/{id}',[\App\Http\Controllers\FileController::class,'download'])->name('download.files');
});
