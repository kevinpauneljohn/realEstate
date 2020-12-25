<?php


namespace App\Repositories;


use App\ClientProjects;

class ClientPaymentRepository
{
    public function checkProjectId($project_id)
    {

        $project = ClientProjects::findOrFail($project_id);
        return $project;
    }

}

