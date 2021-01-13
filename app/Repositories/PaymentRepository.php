<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\PaymentInterFace;
use Illuminate\Support\Facades\Request;

class PaymentRepository extends ClientRepository implements PaymentInterFace
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function viewAll($project_id)
    {
        $this->id = $project_id;
        // TODO: Implement viewAll() method.
        // we will call the projects url which will retrieve the payments thru relationships
        $this->requestResponse = $this->setHttpHeader()
            ->get($this->url.'/payments/project/'.$this->id)->json();
        return $this->runMethod('viewAll');
    }

    public function create($request)
    {
        // TODO: Implement create() method.
        $this->client = $request;
        $this->requestResponse = $this->setHttpHeader()
            ->post($this->url.'/payments',$this->client)->json();
        return $this->runMethod('create');
    }

    public function viewById($id)
    {
        // TODO: Implement viewById() method.
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->get($this->url.'/payments/'.$this->id.'/edit')->json();
        return $this->runMethod('viewById');
    }

    public function updateById($request, $id)
    {
        // TODO: Implement updateById() method.
        $this->client = $request;
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->put($this->url.'/payments/'.$this->id,$this->client)->json();
        return $this->runMethod('updateById');
    }

    private function runMethod($method)
    {
        if($this->tokenUnauthenticated() === true)
        {
            switch ($method)
            {
                case "viewAll":
                    return $this->viewAll($this->id);
                    break;
                case "create":
                    return $this->create($this->client);
                    break;
                case "viewById":
                    return $this->viewById($this->id);
                    break;
                case "updateById":
                    return $this->updateById($this->client,$this->id);
                    break;
//                case "deleteById":
//                    return $this->deleteById($this->id);
//                    break;
            }
        }
        return $this->requestResponse;
    }
}
