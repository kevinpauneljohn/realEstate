<?php


namespace App\Repositories\RepositoryInterface;


interface DhgClientInterFace
{
    public function create(array $client);

    public function view();

    public function viewById(string $client);

}
