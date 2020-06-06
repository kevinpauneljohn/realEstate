<?php

namespace App\Listeners;

use App\AmountWithdrawalRequest;
use App\Events\AmountWithdrawalRequestEvent;
use App\Events\TransactionRecordEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AmountWithdrawalRequestListener
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
     * @param  AmountWithdrawalRequestEvent  $event
     * @return void
     */
    public function handle(AmountWithdrawalRequestEvent $event)
    {
        $amount_withdrawal_request = AmountWithdrawalRequest::find($event->amount_withdrawal_requests_id);
        $amount_withdrawal_request->status = $event->action;
        $amount_withdrawal_request->remarks = $event->remarks;
        if($amount_withdrawal_request->isDirty())
        {
            $amount_withdrawal_request->save();


            ///log the data to transaction table
            $transaction = array(
                'user_id'           => $amount_withdrawal_request->wallet->user_id,
                'wallet_id'         => $amount_withdrawal_request->wallet_id,
                'cash_request_id'   => $amount_withdrawal_request->cash_request_id,
                'details'           => 'System '.$event->action.' your request to encash amount of <span class="text-primary">&#8369; '.number_format($amount_withdrawal_request->requested_amount,2).'</span> from the source amount of <span class="text-success">&#8369; '.number_format($amount_withdrawal_request->original_amount,2).'</span>',
                'category'          => $amount_withdrawal_request->wallet->category,
                'status'            => $amount_withdrawal_request->status
            );
            event(new TransactionRecordEvent($transaction));
        }
    }
}
