<?php

namespace App\Repositories;


use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\Traits\RemovePrefix;
use Illuminate\Support\Facades\Request;

class DhgClientRepository extends ClientRepository implements DhgClientInterFace
{
    use RemovePrefix;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Jan. 06, 2021
     * @author john kevin paunel
     * Create new user client to DHG server
     * @param array $client
     * @return $this->runMethod
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
    public function viewAll()
    {
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/users')->json();
        return $this->runMethod('viewAll');
    }

    /**
     * @param string $client
     * @return DhgClientRepository|mixed
     */
    public function viewById($client)
    {
        $this->client = $client;

        $this->requestResponse = $this->setHttpHeader()
        ->get(config('dreamhomeguide.api_base_url').'/api/users/'.$client)->json();
        return $this->runMethod('viewById');
    }

    /**
     * @param array $clients
     * @param string $id
     * @return DhgClientRepository|mixed
     */
    public function updateById($clients, $id)
    {
        $this->client = $this->editArrayKeys('edit_','',$clients);//trait remove prefix method
        $this->id = $id;

        $this->requestResponse = $this->setHttpHeader()
            ->put(config('dreamhomeguide.api_base_url').'/api/users/'.$id,$this->client)->json();
        return $this->runMethod('updateById');
    }

    /**
     * @param string $id
     * @return DhgClientRepository|mixed
     */
    public function removeById($id)
    {
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->delete(config('dreamhomeguide.api_base_url').'/api/users/'.$id)->json();
        return $this->runMethod('removeById');
    }

    /**
     * @param array $clients
     * @param string $id
     * @return DhgClientRepository|mixed
     */
    public function updateRoleById($clients, $id)
    {
        $this->client = $clients;
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->put(config('dreamhomeguide.api_base_url').'/api/users/update-role/'.$id, $this->client)->json();
        return $this->runMethod('updateRoleById');
    }

    /**
     * @param $role
     * @return DhgClientRepository|mixed
     */
    public function viewByRole($role)
    {
        // TODO: Implement viewByRole() method.
        $this->role = $role;
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/users/view-by-role/'.$this->role)->json();
        return $this->runMethod('viewByRole');
    }


    /**
     * if the request is unauthenticated callback the method again
     * this will return the api response if successful
     * @param $method
     * @return DhgClientRepository|mixed
     */
    private function runMethod($method)
    {
        if($this->tokenUnauthenticated() === true)
        {
            switch ($method)
            {
                case "create":
                    return $this->create($this->client);
                    break;
                case "viewAll":
                    return $this->viewAll();
                    break;
                case "viewById":
                    return $this->viewById($this->client);
                    break;
                case "updateById":
                    return $this->updateById($this->client, $this->id);
                    break;
                case "removeById":
                    return $this->removeById($this->id);
                    break;
                case "updateRoleById":
                    return $this->updateRoleById($this->client,$this->id);
                    break;
                case "viewByRole":
                    return $this->viewByRole($this->role);
                    break;
            }
        }
        return $this->requestResponse;
    }



}
