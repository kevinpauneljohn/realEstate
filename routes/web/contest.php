<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('contest',\App\Http\Controllers\ContestController::class);
    Route::get('/contest-list','ContestController@contest_list')->name('contest.list');
    Route::post('/join-contest/{contest}',[\App\Http\Controllers\ContestController::class,'joinUserToContest'])->name('user-joined-contest');
});
