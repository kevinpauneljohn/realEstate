<?php


namespace App\Repositories\RepositoryInterface;


interface CheckCredentialInterface
{
    public function checkPassword($username, $password);
}
