<?php


namespace App\Services;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AccountManagerService
{


    /**
     *  if the logged in user is an account manager will return all the leads of the super admin
     *  if the logged in user is not an account manager will return
     * @return Authenticatable|null
     */
    public function checkIfUserIsAccountManager()
    {
        if(auth()->user()->hasRole(['account manager','online warrior']))
        {
            return User::whereHas("roles", function($q){ $q->where("name", "super admin"); })->first();
        }
        return auth()->user();
    }
}
