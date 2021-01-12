<?php


namespace App\Repositories\RepositoryInterface;


interface PaymentInterFace
{
    public function viewAll($project_id);
    public function viewById($id);
    public function create($request);
}
