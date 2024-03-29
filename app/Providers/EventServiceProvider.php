<?php

namespace App\Providers;

use App\Threshold;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
        ],
        'App\Events\DeleteSalesRequestEvent' => [
            'App\Listeners\DeleteSalesRequestListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
//        parent::boot();

        Event::listen(BuildingMenu::class, function(BuildingMenu $event){
            $event->menu->add([
                'text' => 'Welcome back '.auth()->user()->username,
                'url' => '#',
                'topnav' => true,
                'classes'  => 'text-info text-bold',
            ]);

            $event->menu->addAfter('contacts',[
                'text' => 'Contests',
                'route'  => 'contest.index',
                'icon'    => 'fas fa-trophy',
                'can'  => 'view contest',
                'icon_color' => 'warning',
                'key' => 'contest'
            ]);
            $event->menu->addAfter('settings',[
                'text' => 'Wallet Amount ('.number_format(\App\Wallet::where([['user_id','=',auth()->user()->id],['status','!=','completed']])->sum('amount'),2).')',
                'route'  => 'wallet.index',
                'icon'    => 'fas fa-wallet',
                'icon_color' => 'info',
                'key' => 'wallet'
            ]);

            $thresholds = Threshold::where([
                ['user_id','=',auth()->user()->id],
                ['status','=','pending'],
            ]);
            $event->menu->addAfter('contacts',[
                'text' => 'Admin Requests',
                'icon'    => 'fas fa-list',
                'route'  => 'thresholds.index',
                'label' => $thresholds->count() > 0 ? $thresholds->count() : '',
                'label_color' => $thresholds->count() > 0 ? 'danger' : 'default',
                'can'  => 'view request',
                'key' => 'admin_request'
            ]);
        });
    }
}
