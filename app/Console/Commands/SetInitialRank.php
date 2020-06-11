<?php

namespace App\Console\Commands;

use App\Events\UserRankPointsEvent;
use App\User;
use Illuminate\Console\Command;

class SetInitialRank extends Command
{
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
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
            if($user->sales->count() > 0)
            {
                //the user has sales and set the appropriate points
                $discounted_price = $user->sales[0]->total_contract_price - $user->sales[0]->discount;
                event(new UserRankPointsEvent($user,($discounted_price / 100000)));
            }else{
                //set the points to zero
                event(new UserRankPointsEvent($user,0));
            }
            echo 'success';
        }
    }
}
