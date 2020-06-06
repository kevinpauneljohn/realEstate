<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AmountWithdrawalRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $amount_withdrawal_requests_id, $action, $remarks;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($amount_withdrawal_requests_id, $action, $remarks)
    {
        $this->amount_withdrawal_requests_id = $amount_withdrawal_requests_id;
        $this->action = $action;
        $this->remarks = $remarks;
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
