<?php


namespace App\Repositories\RepositoryInterface;


interface DhgClientInterFace
{
    public function create(array $client);

    public function viewAll();

    public function viewById(string $client);

    public function updateById(array $client, string $id);

    public function removeById(string $id);

    public function updateRoleById(array $client, string $id);

}
