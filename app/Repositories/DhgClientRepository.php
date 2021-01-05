<?php

namespace App\Repositories;


use App\Repositories\RepositoryInterface\DhgClientInterFace;

class DhgClientRepository extends ClientRepository implements DhgClientInterFace
{

    /**
     * Jan. 06, 2021
     * @author john kevin paunel
     * Create new user client to DHG server
     * @param array $client
     * @return mixed
     * */
    public function create($client)
    {
        $response = $this->setHttpHeader()
            ->post(config('dreamhomeguide.api_base_url').'/api/users',$client);
        return json_decode((string) $response->getBody(), true);
    }
}
