<?php

namespace App\Repositories;


use App\Repositories\RepositoryInterface\DhgClientInterFace;
use Illuminate\Support\Facades\Request;

class DhgClientRepository extends ClientRepository implements DhgClientInterFace
{
    public $serverResponse,
            $client;
    private $method;

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
        $this->method = 'create'; //this will instantiate what method was being requested

        $response = $this->setHttpHeader()
            ->post(config('dreamhomeguide.api_base_url').'/api/users',$this->client);
        //$result = json_decode((string) $response->getBody(), true);
        $this->requestResponse = $response->json();

        return $this->requestProceed();
    }

    //the request method will proceed if the token submitted was valid from the server
    public function requestProceed()
    {
        if($this->tokenUnauthenticated() === true)
        {
          switch ($this->method)
            {
                case 'create':
                    $this->create($this->client);
                    break;
            }
        }
        return $this->requestResponse;
    }

}
