<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\BuilderInterface;
use App\Traits\RemovePrefix;
use Illuminate\Support\Facades\Request;

class BuilderRepository extends ClientRepository implements BuilderInterface
{
    use RemovePrefix;

    /**
     * view all created and active builders
     * @return mixed
     */
    public function viewAll()
    {
        // TODO: Implement viewAll() method.
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/builders')->json();

        return $this->runAction('viewAll');
    }

    /**
     * create new builder
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        // TODO: Implement create() method.
        $this->client = $request;
        $this->requestResponse = $this->setHttpHeader()
            ->post(config('dreamhomeguide.api_base_url').'/api/builders',$this->client)->json();
        return $this->runAction('create');

    }

    /**
     * updated the specified builder
     * @param $request
     * @param $id
     * @return mixed
     */
    public function updateById($request, $id)
    {
        // TODO: Implement updateById() method.
        $this->client = $this->editArrayKeys('edit_','',$request);
        $this->id = $id;

        $this->requestResponse = $this->setHttpHeader()
            ->put(config('dreamhomeguide.api_base_url').'/api/builders/'.$this->id, $this->client)->json();
        return $this->runAction('updateById');
    }

    /**
     * view the specified builder
     * @param $id
     * @return mixed
     */
    public function viewById($id)
    {
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->get(config('dreamhomeguide.api_base_url').'/api/builders/'.$this->id)->json();
        return $this->runAction('viewById');
    }

    /**
     * remove or soft delete specified builder
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->delete(config('dreamhomeguide.api_base_url').'/api/builders/'.$this->id)->json();
        return $this->runAction('deleteById');
    }

    public function addMember(array $member)
    {
        // TODO: Implement addMember() method.
        $this->client = $member;

        $this->requestResponse = $this->setHttpHeader()
            ->post($this->url.'/builders/member',$this->client)->json();
        return $this->runAction('addMember');
    }

    public function removeMember($request)
    {
        $this->client = $request;
        $this->requestResponse = $this->setHttpHeader()
            ->delete($this->url.'/builders/members/remove',$this->client)->json();
        return $this->runAction('removeMember');
    }


    /**
     * this method will automatically request to create another access token
     * if the current token was revoked or deleted from the provider
     * @param $method //this is the method name you are invoking
     * @return mixed
     */
    private function runAction($method)
    {
        if($this->tokenUnauthenticated() === true)
        {
            switch ($method)
            {
                case "viewAll":
                    return $this->viewAll();
                case "create":
                    return $this->create($this->client);
                case "viewById":
                    return $this->viewById($this->id);
                case "updateById":
                    return $this->updateById($this->client,$this->id);
                case "deleteById":
                    return $this->deleteById($this->id);
                case "addMember":
                    return $this->addMember($this->client);
                case "removeMember":
                    return $this->removeMember($this->client);
            }
        }
        return $this->requestResponse;
    }
}
