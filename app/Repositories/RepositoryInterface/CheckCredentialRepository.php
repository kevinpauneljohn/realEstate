<?php


namespace App\Repositories\RepositoryInterface;


use Illuminate\Support\Facades\Auth;

class CheckCredentialRepository implements CheckCredentialInterface
{
    /**
     * validate the password
     * @param string $username
     * @param string $password
     * @return boolean
     * */
    public function checkPassword($username, $password)
    {
        if(Auth::attempt(['username' => $username, 'password' => $password]))
        {
            return true;
        }
        return false;
    }
}
