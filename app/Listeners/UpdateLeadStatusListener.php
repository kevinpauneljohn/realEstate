<?php

namespace App\Listeners;

use App\Events\UpdateLeadStatusEvent;
use App\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLeadStatusListener
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
     * @param  UpdateLeadStatusEvent  $event
     * @return void
     */
    public function handle(UpdateLeadStatusEvent $event)
    {
        $lead = Lead::find($event->lead);
        $lead->lead_status = 'Reserved';
        if($lead->isDirty())
        {
            $lead->save();
        }
    }
}
