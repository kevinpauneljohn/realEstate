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
            ['rank_id' => $this->setRankByPoints($event->sales_points, $event->extra_points), 'sales_points' => $event->sales_points,'extra_points' => $event->extra_points]
        );
    }

    private function setRankByPoints($sales_point,$extra_points)
    {
        $point = $sales_point + $extra_points;
        $rank = Rank::where([
            ['start_points','<=',$point],
            ['end_points','>=',$point],
            ['timeline','=','lifetime'],
        ])->first();
        return $rank->id;
    }
}
