<?php

namespace App\Listeners;

use App\Events\SendMoneyEvent;
use App\Wallet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMoneyListener
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
     * @param  SendMoneyEvent  $event
     * @return void
     */
    public function handle(SendMoneyEvent $event)
    {
        //send initial money to the user
        $wallet = new Wallet();
        $wallet->user_id = $event->user->user_id;
        $wallet->amount = $event->user->amount;
        $wallet->details = $event->user->details;
        $wallet->category = $event->user->category;
        $wallet->status = $event->user->status;
        $wallet->save();
    }
}
