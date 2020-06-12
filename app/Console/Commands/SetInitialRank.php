<?php

namespace App\Console\Commands;

use App\Events\UserRankPointsEvent;
use App\Repositories\SalesRepository;
use App\User;
use Illuminate\Console\Command;

class SetInitialRank extends Command
{
    public $salesRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:rank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set the initial rank';

    /**
     * Create a new command instance.
     *
     * @return mixed
     */
    public function __construct(SalesRepository $salesRepository)
    {
        parent::__construct();
        $this->salesRepository = $salesRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user)
        {
            ///check if the user has extra points
            $extra_points = $user->userRankPoint == null ? 0 : $user->userRankPoint->exra_points;

            if($this->salesRepository->getTotalSales($user->id) > 0)
            {
                //the user has sales and set the appropriate points
                //$discounted_price = $user->sales[0]->total_contract_price - $user->sales[0]->discount;

                event(new UserRankPointsEvent($user,($this->salesRepository->getTotalSales($user->id) / 100000),$extra_points));
            }else{
                //set the points to zero
                event(new UserRankPointsEvent($user,0,$extra_points));
            }
            echo 'success';
        }
    }
}
