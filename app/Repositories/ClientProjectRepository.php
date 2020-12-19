<?php


namespace App\Repositories;


class ClientProjectRepository
{
    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * set the client project id into code format
     * @param int $clientProjects
     * @return mixed
    */
    public function setClientProjectCode($clientProjects)
    {
        $num_padded = sprintf("%05d", $clientProjects);
        return 'dhg-'.$num_padded;
    }
}
