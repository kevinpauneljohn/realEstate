<?php

namespace App\Providers;

use App\Lead;
use App\Repositories\BuilderRepository;
use App\Repositories\ClientProjectRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DhgClientRepository;
use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use App\Repositories\RepositoryInterface\BuilderInterface;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\CheckCredentialRepository;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\Repositories\RepositoryInterface\DhgClientProjectInterface;
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

        $this->app->bind(
            DhgClientInterFace::class,
            DhgClientRepository::class
        );

        $this->app->bind(
            DhgClientProjectInterface::class,
            ClientProjectRepository::class
        );

        $this->app->bind(
            BuilderInterface::class,
            BuilderRepository::class
        );

        $this->app->bind(
            CheckCredentialInterface::class,
                    CheckCredentialRepository::class
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
