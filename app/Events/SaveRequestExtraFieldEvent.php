<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaveRequestExtraFieldEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $amount_withdrawal_request_id, $extra_field;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($amount_withdrawal_request_id, $extra_field)
    {
        $this->amount_withdrawal_request_id = $amount_withdrawal_request_id;
        $this->extra_field = $extra_field;
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
