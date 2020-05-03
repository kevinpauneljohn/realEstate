<?php

namespace App\Console\Commands;

use App\Repositories\PriorityRepository;
use App\Threshold;
use Illuminate\Console\Command;

class UpdatePriority extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:priority';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update the request status';

    public $priorityRepository;

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
    public function handle(PriorityRepository $priorityRepository)
    {
        $this->priorityRepository = $priorityRepository;
        //retrieve all the pending requests
        $threshold = Threshold::where('status','pending')->get();

        //check the pending request one by one
        foreach ($threshold as $key => $value)
        {
            //check if the current dates is still below the due date
            if(today() <= $value->due_date)
            {
                $day = today()->diffInDays($value->due_date);//get the days left

                //check if the day exists on the priority table and will execute the inside action if its true
                if($this->priorityRepository->countPriorityByDays($day) > 0
                    && $this->priorityRepository->getPriorityByNumberOfDay($day)->id == $value->id)
                {
                    //this will update the priority id of the request
                    $request = Threshold::find($value->id);
                    $request->priority_id = $this->priorityRepository->getPriorityByNumberOfDay($day)->id;
                    $request->save();
                    $this->info('saved');
                }
            }
        }
    }
}
