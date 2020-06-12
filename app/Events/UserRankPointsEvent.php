<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRankPointsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user, $sales_points, $extra_points;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $sales_points, $extra_points)
    {
        $this->user = $user;
        $this->sales_points = $sales_points;
        $this->extra_points = $extra_points;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
