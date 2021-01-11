<?php


namespace App\Repositories\RepositoryInterface;


interface DhgClientProjectInterface
{
    public function setCode($clientProjects);
    public function viewAll();
    public function viewById($id);
    public function updateById($request, $id);
    public function create($request);
}
