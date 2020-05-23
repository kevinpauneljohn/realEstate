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
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
//        $saveNotification = new Notification();
//        $saveNotification->user_id = $event->notification->user;
//        $saveNotification->data = $event->notification->data;
//        $saveNotification->viewed = $event->notification->viewed;
//        $saveNotification->type = $event->notification->type;
//        $saveNotification->save();
    }
}
