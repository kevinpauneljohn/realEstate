<?php


namespace App\Repositories;


use App\User;

class UserRepository
{
    /**
     *
     * */
    public function getUserById($id)
    {
        return User::findOrFail($id);
    }
}
