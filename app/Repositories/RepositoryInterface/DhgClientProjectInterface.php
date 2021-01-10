<?php


namespace App\Repositories\RepositoryInterface;


interface DhgClientProjectInterface
{
    public function setCode($clientProjects);
    public function viewAll();

    public function create($request);
}
