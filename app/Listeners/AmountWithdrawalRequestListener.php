<?php

namespace App\Listeners;

use App\AmountWithdrawalRequest;
use App\Events\AmountWithdrawalRequestEvent;
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
        if($amount_withdrawal_request->isDirty('status'))
        {
            $amount_withdrawal_request->save();
        }
    }
}
