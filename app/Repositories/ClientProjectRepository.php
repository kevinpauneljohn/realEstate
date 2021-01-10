<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\DhgClientProjectInterface;
use Illuminate\Support\Facades\Request;

class ClientProjectRepository extends ClientRepository implements DhgClientProjectInterface
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * set the client project id into code format
     * @param int $clientProjects
     * @return mixed
    */
    public function setCode($clientProjects)
    {
        $num_padded = sprintf("%05d", $clientProjects);
        return 'dhg-'.$num_padded;
    }

    public function viewAll()
    {
        // TODO: Implement viewAll() method.
        $this->requestResponse = $this->setHttpHeader()
            ->get($this->url.'/projects')->json();
        return $this->runMethod('viewAll');
    }

    public function create($request)
    {
        $this->client = $request;
        // TODO: Implement create() method.
        $this->requestResponse = $this->setHttpHeader()
            ->post($this->url.'/projects',$this->client)->json();
        return $this->runMethod('create');
    }

    private function runMethod($method)
    {
        if($this->tokenUnauthenticated() === true)
        {
            switch ($method)
            {
                case "viewAll":
                    return $this->viewAll();
                    break;
                case "create":
                    return $this->create($this->client);
                    break;
//                case "viewById":
//                    return $this->viewById($this->client);
//                    break;
//                case "updateById":
//                    return $this->updateById($this->client, $this->id);
//                    break;
//                case "removeById":
//                    return $this->removeById($this->id);
//                    break;
//                case "updateRoleById":
//                    return $this->updateRoleById($this->client,$this->id);
//                    break;
            }
        }
        return $this->requestResponse;
    }
}
