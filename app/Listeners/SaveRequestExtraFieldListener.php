<?php

namespace App\Listeners;

use App\Events\SaveRequestExtraFieldEvent;
use App\extraField;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveRequestExtraFieldListener
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
     * @param  SaveRequestExtraFieldEvent  $event
     * @return void
     */
    public function handle(SaveRequestExtraFieldEvent $event)
    {
        foreach ($event->extra_field as $field)
        {
            $extra_field = new extraField();
            $extra_field->amount_withdrawal_request_id = $event->amount_withdrawal_request_id;
            $extra_field->amount = $field['amount'];
            $extra_field->extra_field_description = $field['description'];
            $extra_field->save();
        }

    }
}
