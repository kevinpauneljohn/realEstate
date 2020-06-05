<?php

namespace App\Listeners;

use App\AmountWithdrawalRequest;
use App\CashRequest;
use App\Events\UpdateCashRequestStatusEvent;
use App\Wallet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCashRequestStatusListener
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
     * @param  UpdateCashRequestStatusEvent  $event
     * @return void
     */
    public function handle(UpdateCashRequestStatusEvent $event)
    {
        $amount_withdrawal_request = AmountWithdrawalRequest::where('cash_request_id',$event->cash_request_id);
        $amount_withdrawal_request_completed = AmountWithdrawalRequest::where([
            ['cash_request_id','=',$event->cash_request_id],
            ['status','!=','pending']
        ]);

        //if all the request under the cash request number has been acted it will update the cash request status
        //and reflect the changes to the user's wallet
        if($amount_withdrawal_request->count() === $amount_withdrawal_request_completed->count())
        {
            $cashRequest = CashRequest::find($event->cash_request_id);
            $cashRequest->status = 'completed';
            $cashRequest->save();

            //update the user's wallet value
            foreach ($amount_withdrawal_request->get() as $amount_withdrawn)
            {
                if($amount_withdrawn->status === 'approved')
                {
                    $wallet = Wallet::find($amount_withdrawn->wallet_id);
                    /// update the original wallet amount
                    $wallet->amount = $amount_withdrawn->original_amount - $amount_withdrawn->requested_amount;
                    $wallet->save();
                }
            }
        }
    }
}
