<?php

use App\Http\Controllers\CommissionVoucherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::post('/commission-request-status-update/{commission_request}',[\App\Http\Controllers\CommissionRequestController::class,'updateStatus'])->name('commission.request.status.update');
    Route::post('/preview-voucher',[\App\Http\Controllers\CommissionRequestController::class,'previewVoucher'])->name('preview.voucher');

    Route::post('/save-commission-voucher',[\App\Http\Controllers\CommissionRequestController::class,'saveVoucher'])->name('save.commission.voucher');
    Route::post('/approve-voucher/{id}',[\App\Http\Controllers\CommissionRequestController::class,'approveVoucher'])->name('approve.voucher');
    Route::delete('/remove-voucher/{id}',[\App\Http\Controllers\CommissionRequestController::class,'removeVoucher'])->name('remove.voucher');
    Route::post('/commission-request/update-sales-tcp/{sales_id}',[\App\Http\Controllers\CommissionRequestController::class,'updateSalesTotalPrice'])->name('update.sales.tcp');
    Route::patch('/commission-voucher/save-drive-link/{voucher_id}',[CommissionVoucherController::class,'saveDriveLink'])->name('voucher.save.drive.link');
});
