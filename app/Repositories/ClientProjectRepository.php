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
    public function setClientProjectCode($clientProjects)
    {
        $num_padded = sprintf("%05d", $clientProjects);
        return 'dhg-'.$num_padded;
    }

    public function viewAll()
    {
        // TODO: Implement viewAll() method.
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/builders')->json();
        return $this->runMethod('viewAll');
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
