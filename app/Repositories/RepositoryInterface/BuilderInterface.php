<?php


namespace App\Repositories\RepositoryInterface;


interface BuilderInterface
{
    public function viewAll();

    public function create($request);

    public function viewById($id);

    public function updateById($request, $id);

    public function deleteById($id);

    public function addMember(array $member);
}
