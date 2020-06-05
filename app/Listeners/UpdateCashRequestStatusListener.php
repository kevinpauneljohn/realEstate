<?php

namespace App\Listeners;

use App\Events\UpdateCashRequestStatusEvent;
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
        //
    }
}
