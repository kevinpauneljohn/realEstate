<?php


namespace App\Repositories;


class LabelerRepository
{
    public function role($roleNames)
    {
        $roles = "";
        foreach ($roleNames as $role)
        {
            $roles .='<span class="badge badge-info">'.$role.'</span>&nbsp;';
        }
        return $roles;
    }
}
