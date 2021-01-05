<?php

namespace App\Providers;

use App\Lead;
use App\Repositories\ClientRepository;
use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AccessTokenClientInterface::class,
            ClientRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {

    }

}
