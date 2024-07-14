<?php

use App\Http\Controllers\FindingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('findings',FindingController::class);
});
