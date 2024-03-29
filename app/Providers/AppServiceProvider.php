<?php

namespace App\Providers;

use App\Lead;
use App\Repositories\ActionTakenRepository;
use App\Repositories\ClientRequirementRepository;
use App\Repositories\Leads;
use App\Repositories\RepositoryInterface\ActionTakenInterface;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\CheckCredentialRepository;
use App\Repositories\RepositoryInterface\ClientRequirementInterface;
use App\Repositories\RepositoryInterface\LeadInterface;
use App\Repositories\RepositoryInterface\RequirementsInterface;
use App\Repositories\RepositoryInterface\SalesInterface;
use App\Repositories\RepositoryInterface\StaycationInterface;
use App\Repositories\RepositoryInterface\TaskChecklistInterface;
use App\Repositories\RepositoryInterface\TaskChecklistRepository;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Repositories\RequirementsRepository;
use App\Repositories\Sales;
use App\Repositories\StaycationRepository;
use App\Repositories\TaskRepository;
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
            CheckCredentialInterface::class,
            CheckCredentialRepository::class
        );


        $this->app->bind(
            SalesInterface::class,
            Sales::class
        );

        $this->app->bind(
            LeadInterface::class,
            Leads::class
        );

        $this->app->bind(
            ClientRequirementInterface::class,
            ClientRequirementRepository::class
        );

        $this->app->bind(
            RequirementsInterface::class,
            RequirementsRepository::class
        );

        $this->app->bind(
            TaskInterface::class,
            TaskRepository::class
        );

        $this->app->bind(
            TaskChecklistInterface::class,
            TaskChecklistRepository::class
        );

        $this->app->bind(
            ActionTakenInterface::class,
            ActionTakenRepository::class
        );

        $this->app->bind(
            StaycationInterface::class,
            StaycationRepository::class
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
