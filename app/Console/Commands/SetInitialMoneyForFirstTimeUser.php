<?php

namespace App\Console\Commands;

use App\Repositories\WalletRepository;
use App\User;
use Illuminate\Console\Command;

class SetInitialMoneyForFirstTimeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:money';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send initial 500 pesos for first time user';

    public $walletRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WalletRepository $walletRepository)
    {
        parent::__construct();
        $this->walletRepository = $walletRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();

        foreach($users as $user)
        {
            $this->walletRepository->setMoney(
                $user->id,
                User::where('username','kevinpauneljohn')->first()->id,
                500,'Initial incentives can be cashed out if there is a reservation',
                true,false,'incentive','for-approval'
            );
        }

        echo 'success';
    }
}
