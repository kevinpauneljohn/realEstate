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
        'App\Events\NotificationEvent' => [
            'App\Listeners\NotificationListener',
        ],
        'App\Events\SendMoneyEvent' => [
            'App\Listeners\SendMoneyListener',
        ],
        'App\Events\SaveRequestExtraFieldEvent' => [
            'App\Listeners\SaveRequestExtraFieldListener',
        ],
        'App\Events\AmountWithdrawalRequestEvent' => [
            'App\Listeners\AmountWithdrawalRequestListener',
        ],
        'App\Events\UpdateCashRequestStatusEvent' => [
            'App\Listeners\UpdateCashRequestStatusListener',
        ],
        'App\Events\TransactionRecordEvent' => [
            'App\Listeners\TransactionRecordListener',
        ],
        'App\Events\UserRankPointsEvent' => [
            'App\Listeners\UserRankPointsListener',
        ],
        'App\Events\FirstBloodContestEvent' => [
            'App\Listeners\FirstBloodContestContestListener',
        ],
        'App\Events\UserCommissionRequestEvent' => [
            'App\Listeners\UserCommissionRequestListener',
        ]
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
