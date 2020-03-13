<?php

namespace App\Listeners;

use App\Events\CreateNetworkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class CreateNetworkListeners
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
     * @param  CreateNetworkEvent  $event
     * @return void
     */
    public function handle(CreateNetworkEvent $event)
    {
        $network = DB::table('networks')->insert([
            [
                'user_id'   => $event->user,
                'upline_id'    => $event->upline
            ]
        ]);
    }
}
