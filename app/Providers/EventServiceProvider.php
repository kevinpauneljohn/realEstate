<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
        'App\Events\CreateNetworkEvent' => [
            'App\Listeners\CreateNetworkListeners',
        ],
        'App\Events\UpdateLeadStatusEvent' => [
            'App\Listeners\UpdateLeadStatusListener',
        ],
        'App\Events\UpdateLeadGeneralStatusEvent' => [
            'App\Listeners\UpdateLeadGeneralStatusListener',
        ],
        'App\Events\LeadStatusForTrippingEvent' => [
            'App\Listeners\LeadStatusForTrippingStatusListener',
        ],
        'App\Events\UserRequestEvent' => [
            'App\Listeners\UserRequestListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
