<?php

namespace App\Listeners;

use App\Events\TransactionRecordEvent;
use App\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionRecordListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TransactionRecordEvent  $event
     * @return void
     */
    public function handle(TransactionRecordEvent $event)
    {
        $transaction = new Transaction();
        $transaction->user_id = $event->transaction['user_id'];
        $transaction->wallet_id = $event->transaction['wallet_id'];
        $transaction->cash_request_id = $event->transaction['cash_request_id'];
        $transaction->details = $event->transaction['details'];
        $transaction->category = $event->transaction['category'];
        $transaction->status = $event->transaction['status'];
        $transaction->save();
    }
}
