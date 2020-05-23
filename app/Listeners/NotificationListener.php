<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationListener
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
     * @param  NotificationEvent  $event
     * @return mixed
     */
    public function handle(NotificationEvent $event)
    {
        $checkNotification = Notification::where([
            ['user_id','=',$event->notification->user],
            ['data->lead_id','=',$event->notification->data['lead_id']],
            ['data->schedule','=',$event->notification->data['schedule']],
            ['data->time','=',$event->notification->data['time']],
            ['data->category','=',$event->notification->data['category']],
            ['data->time_left','=',$event->notification->data['time_left']],
            ['viewed','=',$event->notification->viewed],
            ['type','=',$event->notification->type],
        ]);

        if($checkNotification->count() < 1)
        {
            $saveNotification = new Notification();
            $saveNotification->user_id = $event->notification->user;
            $saveNotification->data = $event->notification->data;
            $saveNotification->viewed = $event->notification->viewed;
            $saveNotification->type = $event->notification->type;

            if($saveNotification->save())
            {
                echo 'success';
                return true;
            }else{
                echo 'failed';
                return false;
            }
        }
        echo 'failed';
        return false;
    }
}
