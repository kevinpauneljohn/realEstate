<?php


namespace App\Repositories;


use Illuminate\Support\Facades\Auth;

class CheckPassword
{
    /**
     * Dec. 26, 2020
     * @author john kevin paunel
     * check the admin credential
     * @param string $username
     * @param string $password
     * @return array
     * */
    public function checkPassword($username, $password)
    {
        if(Auth::attempt(['username' => $username, 'password' => $password]))
        {
            return ["success" => true];
        }
        return ["success" => false, 'message' => 'You are not allowed to update the payment'];
    }
}
