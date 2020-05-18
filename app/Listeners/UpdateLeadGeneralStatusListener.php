<?php

namespace App\Listeners;

use App\Events\UpdateLeadGeneralStatusEvent;
use App\Lead;
use App\LeadActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLeadGeneralStatusListener
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
     * @param  UpdateLeadGeneralStatusEvent  $event
     * @return void
     */
    public function handle(UpdateLeadGeneralStatusEvent $event)
    {
        $leads = Lead::all();
        foreach ($leads as $lead)
        {
            $leadUpdate = Lead::find($lead->id);
            $trippingCount = LeadActivity::where([
                ['lead_id','=',$lead->id],
                ['category','=','Tripping'],
                ['status','=','pending'],
            ])->count();

            if($lead->updated_at->diffInDays() >= 3 && $lead->lead_status === 'Hot')
            {
                $leadUpdate->lead_status = 'Warm';
                $leadUpdate->save();
            }
            elseif($lead->updated_at->diffInDays() >= 5 && $lead->lead_status === 'Warm'){
                $leadUpdate->lead_status = 'Cold';
                $leadUpdate->save();
            }
            elseif($lead->updated_at->diffInDays() >= 3 && $lead->lead_status === 'Qualified'){
                $leadUpdate->lead_status = 'Warm';
                $leadUpdate->save();
            }
            elseif($lead->lead_status === 'For tripping' && $trippingCount === 0 && $lead->updated_at->diffInDays() >= 3){
                $leadUpdate->lead_status = 'Warm';
                $leadUpdate->save();

            }
        }
        echo 'ok';
    }
}
