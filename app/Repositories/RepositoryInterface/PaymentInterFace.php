<?php


namespace App\Repositories\RepositoryInterface;


interface PaymentInterFace
{
    public function viewAll($project_id);
    public function viewById($id);
    public function removeById($id);
    public function updateById($request, $id);
    public function create($request);
}
