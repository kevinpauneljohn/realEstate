<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('contest',\App\Http\Controllers\ContestController::class);
    Route::get('/contest-list','ContestController@contest_list')->name('contest.list');
    Route::post('/join-contest/{contest}',[\App\Http\Controllers\ContestController::class,'joinUserToContest'])->name('user-joined-contest');
    Route::get('/get-contest-participants/{contest}',[\App\Http\Controllers\ContestController::class,'getContestParticipants'])->name('get-contest-participants');
    Route::post('/declare-contest-winner/{contest}/{user}',[\App\Http\Controllers\ContestController::class,'declareWinner'])->name('declare-contest-winner');
});
