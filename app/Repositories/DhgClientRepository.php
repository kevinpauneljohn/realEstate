<?php

namespace App\Repositories;


use App\Repositories\RepositoryInterface\DhgClientInterFace;
use Illuminate\Support\Facades\Request;

class DhgClientRepository extends ClientRepository implements DhgClientInterFace
{
    public $serverResponse,
            $client;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Jan. 06, 2021
     * @author john kevin paunel
     * Create new user client to DHG server
     * @param array $client
     * @return mixed
     * */
    public function create($client)
    {
        $this->client = $client; //this is the request client instance

        $response = $this->setHttpHeader()
            ->post(config('dreamhomeguide.api_base_url').'/api/users',$this->client);

        $this->requestResponse = $response->json();
        return $this->runMethod('create');
    }

    /**
     * Jan. 07, 2021
     * @author john kevin paunel
     * view all the created client
     * @return DhgClientRepository view
     * */
    public function view()
    {
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/users')->json();
        return $this->runMethod('view');
    }

    //if the request is unauthenticated callback the method again
    //this will return the api response if successful
    private function runMethod($method)
    {
        if($this->tokenUnauthenticated() === true)
        {
            switch ($method)
            {
                case "create":
                    return $this->create($this->client);
                    break;
                case "view":
                    return $this->view();
                    break;
            }
        }
        return $this->requestResponse;
    }



}
