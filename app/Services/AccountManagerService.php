<?php


namespace App\Services;


use App\User;

class AccountManagerService
{
    public $superAdmin;

    public function __construct()
    {
        $this->superAdmin = User::whereHas("roles", function($q){ $q->where("name", "super admin"); })->first();
    }

    /**
     * if the logged in user is an account manager will return all the leads of the super admin
     * if the logged in user is not an account manager will return
     * @param $user
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function checkIfUserIsAccountManager(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        if(auth()->user()->hasRole('account manager'))
        {
            return $this->superAdmin;
        }
        return auth()->user();
    }
}
