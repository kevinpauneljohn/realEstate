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
        //if the lead was already converted to sales or with RESERVED status
        //status will not be updated
        $lead = Lead::find($event->id);
        if($lead->lead_status !== 'Reserved')
        {
            $lead->lead_status = 'For tripping';
            $lead->save();
        }
    }
}
