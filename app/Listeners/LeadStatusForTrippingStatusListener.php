<?php

namespace App\Listeners;

use App\Events\LeadStatusForTrippingEvent;
use App\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LeadStatusForTrippingStatusListener
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
     * @param  LeadStatusForTrippingEvent  $event
     * @return void
     */
    public function handle(LeadStatusForTrippingEvent $event)
    {
        $lead = Lead::find($event->id);
        $lead->lead_status = 'For tripping';
        $lead->save();
    }
}
