<?php

namespace App\Listeners;

use App\Events\FirstBloodContestEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FirstBloodContestContestListener
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
     * @param  FirstBloodContestEvent  $event
     * @return void
     */
    public function handle(FirstBloodContestEvent $event)
    {
        //
    }
}
