<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\DhgClientProjectInterface;
use Illuminate\Support\Facades\Request;

class ClientProjectRepository extends ClientRepository implements DhgClientProjectInterface
{

    /**
     * set the client project id into code format
     * @author john kevin paunel
     * @param $clientProjects
     * @return string
     */
    public function setCode($clientProjects):string
    {
        $num_padded = sprintf("%05d", $clientProjects);
        return 'dhg-'.$num_padded;
    }

    /**
     * view all projects
     * @return mixed
     */
    public function viewAll()
    {
        // TODO: Implement viewAll() method.
        $this->requestResponse = $this->setHttpHeader()
            ->get($this->url.'/projects')->json();
        return $this->runAction('viewAll');
    }

    /**
     * view specified project
     * @param $id
     * @return mixed
     */
    public function viewById($id)
    {
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->get($this->url.'/projects/'.$this->id.'/edit')->json();
        return $this->runAction('viewById');
    }


    /**
     * create new project
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        $this->client = $request;
        // TODO: Implement create() method.
        $this->requestResponse = $this->setHttpHeader()
            ->post($this->url.'/projects',$this->client)->json();
        return $this->runAction('create');
    }

    /**
     * update the specified project
     * @param $request
     * @param $id
     * @return mixed
     */
    public function updateById($request, $id)
    {
        // TODO: Implement updateById() method.
        $this->client = $request;
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->put($this->url.'/projects/'.$this->id,$this->client)->json();
        return $this->runAction('updateById');
    }

    /**
     * remove or soft delete specified project
     * @param $id
     * @return mixed
     */
    public function removeById($id)
    {
        // TODO: Implement deleteById() method.
        $this->id = $id;
        $this->requestResponse = $this->setHttpHeader()
            ->delete($this->url.'/projects/'.$this->id)->json();
        return $this->runAction('deleteById');
    }

    /**
     * @param $method
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
                    break;
                case "create":
                    return $this->create($this->client);
                    break;
                case "viewById":
                    return $this->viewById($this->id);
                    break;
                case "updateById":
                    return $this->updateById($this->client, $this->id);
                    break;
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
