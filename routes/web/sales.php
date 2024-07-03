<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('/sales-date-range',[\App\Http\Controllers\SalesController::class,'salesDateRange'])->name('sales.date.range');
});
