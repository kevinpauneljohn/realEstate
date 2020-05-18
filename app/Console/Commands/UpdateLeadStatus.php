<?php

namespace App\Console\Commands;

use App\Lead;
use App\LeadActivity;
use Illuminate\Console\Command;

class UpdateLeadStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lead_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update lead status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
//        echo 'ok';
    }
}
