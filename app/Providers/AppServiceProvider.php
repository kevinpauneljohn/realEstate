<?php

namespace App\Providers;

use App\Lead;
use App\Repositories\BuilderRepository;
use App\Repositories\ClientPaymentRepository;
use App\Repositories\ClientProjectRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ClientRequirementRepository;
use App\Repositories\DhgClientRepository;
use App\Repositories\Leads;
use App\Repositories\PaymentRepository;
use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use App\Repositories\RepositoryInterface\BuilderInterface;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\CheckCredentialRepository;
use App\Repositories\RepositoryInterface\ClientPaymentInterface;
use App\Repositories\RepositoryInterface\ClientRequirementInterface;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\Repositories\RepositoryInterface\DhgClientProjectInterface;
use App\Repositories\RepositoryInterface\LeadInterface;
use App\Repositories\RepositoryInterface\PaymentInterFace;
use App\Repositories\RepositoryInterface\RequirementsInterface;
use App\Repositories\RepositoryInterface\SalesInterface;
use App\Repositories\RepositoryInterface\TaskChecklistInterface;
use App\Repositories\RepositoryInterface\TaskChecklistRepository;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Repositories\RequirementsRepository;
use App\Repositories\Sales;
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

        $this->app->bind(
          PaymentInterFace::class,
          PaymentRepository::class
        );

        $this->app->bind(
            ClientPaymentInterface::class,
            ClientPaymentRepository::class
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
