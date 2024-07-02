<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('project-links',\App\Http\Controllers\ProjectLinkController::class);
    Route::get('/project-links/list/{project_id}',[\App\Http\Controllers\ProjectLinkController::class,'links'])->name('project.links.list');
});
