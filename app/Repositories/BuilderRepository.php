<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\BuilderInterface;
use App\Traits\RemovePrefix;
use Illuminate\Support\Facades\Request;

class BuilderRepository extends ClientRepository implements BuilderInterface
{
    use RemovePrefix;
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function viewAll()
    {
        // TODO: Implement viewAll() method.
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/builders')->json();

        return $this->runMethod('viewAll');
    }

    public function create($request)
    {
        // TODO: Implement create() method.
        $this->client = $request;
        $this->requestResponse = $this->setHttpHeader()
            ->post(config('dreamhomeguide.api_base_url').'/api/builders',$this->client)->json();
        return $this->runMethod('create');

    }

    public function updateById($request, $id)
    {
        // TODO: Implement updateById() method.
        $this->client = $this->editArrayKeys('edit_','',$request);
        $this->id = $id;

        $this->requestResponse = $this->setHttpHeader()
            ->put(config('dreamhomeguide.api_base_url').'/api/builders/'.$this->id, $this->client)->json();
        return $this->runMethod('updateById');
    }

    public function viewById($id)
    {
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/builders/'.$this->id)->json();
        return $this->runMethod('viewById');
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->delete(config('dreamhomeguide.api_base_url').'/api/builders/'.$this->id)->json();
        return $this->runMethod('deleteById');
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
                case "viewById":
                    return $this->viewById($this->id);
                    break;
                case "updateById":
                    return $this->updateById($this->client,$this->id);
                    break;
                case "deleteById":
                    return $this->deleteById($this->id);
                    break;
            }
        }
        return $this->requestResponse;
    }
}
