<?php

namespace App\Listeners;

use App\Events\UserRankPointsEvent;
use App\Rank;
use App\UserRankPoint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UserRankPointsListener
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
     * @param  UserRankPointsEvent  $event
     * @return mixed
     */
    public function handle(UserRankPointsEvent $event)
    {
        UserRankPoint::updateOrCreate(
            ['user_id' => $event->user->id],
            ['rank_id' => $this->setRankByPoints($event->points), 'points' => $event->points]
        );
    }

    private function setRankByPoints($point)
    {
        $rank = Rank::where([
            ['start_points','<=',$point],
            ['end_points','>=',$point],
        ])->first();
        return $rank->id;
    }
}
