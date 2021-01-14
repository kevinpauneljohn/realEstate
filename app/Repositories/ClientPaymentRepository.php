<?php


namespace App\Repositories;


use App\ClientProjects;
use App\Repositories\RepositoryInterface\ClientPaymentInterface;
use Illuminate\Support\Facades\Request;

class ClientPaymentRepository extends ClientRepository implements ClientPaymentInterface
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function removeById($id)
    {
        // TODO: Implement removeById() method.
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->delete($this->url.'/payments/'.$this->id)->json();
        return $this->runMethod('removeById');
    }

    private function runMethod($method)
    {
        if($this->tokenUnauthenticated() === true)
        {
            switch ($method)
            {
//                case "viewAll":
//                    return $this->viewAll();
//                    break;
//                case "create":
//                    return $this->create($this->client);
//                    break;
//                case "viewById":
//                    return $this->viewById($this->id);
//                    break;
//                case "updateById":
//                    return $this->updateById($this->client, $this->id);
//                    break;
                case "removeById":
                    return $this->removeById($this->id);
                    break;
//                case "updateRoleById":
//                    return $this->updateRoleById($this->client,$this->id);
//                    break;
            }
        }
        return $this->requestResponse;
    }
}

