<?php

namespace App\Console\Commands;

use App\Events\NotificationEvent;
use App\Repositories\TimeRepository;
use DateTime;
use Illuminate\Console\Command;

class ReminderNotification extends Command
{
    public $timeRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save the reminder to the notification table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TimeRepository $timeRepository)
    {
        $this->timeRepository = $timeRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $schedule = \App\LeadActivity::where([
            ['status','=','pending'],
            ['deleted_at','=',null],
        ])->get();

        foreach ($schedule as $sched){
            $dueDate = $this->timeRepository->date_time($sched->schedule, $sched->start_date)->diffForHumans();
            $notification = array(
                'user' => $sched->user_id,
                'data'    => array(
                    'lead_id'   => $sched->lead_id,
                    'schedule'  => $sched->schedule->format('M d, Y'),
                    'time'      => $sched->start_date,
                    'category'  => $sched->category,
                    'time_left' => $dueDate,
                    'link'      => '/schedule'
                ),
                'viewed'  => false,
                'type'    => 'lead activity'
            );

            if($dueDate == '1 day from now')
            {
                event(new NotificationEvent((object)$notification));
            }elseif ($dueDate == '3 days from now'){
                event(new NotificationEvent((object)$notification));
            }
            elseif ($dueDate == '5 hours from now'){
                event(new NotificationEvent((object)$notification));
            }elseif ($dueDate == '1 hour from now'){
                event(new NotificationEvent((object)$notification));
            }
        }
        echo 'ok';
    }
}
