<?php


namespace App\Repositories\RepositoryInterface;


interface DhgClientProjectInterface
{
    /**
     * format the project id (ex. dhg-00001)
     * @param $clientProjects
     * @return mixed
     */
    public function setCode($clientProjects);

    /**
     * view all created project resource
     * @return mixed
     */
    public function viewAll();

    /**
     * view a specified project by id
     * @param $id
     * @return mixed
     */
    public function viewById($id);

    /**
     * remove the specified project bu id
     * @param $id
     * @return mixed
     */
    public function removeById($id);

    /**
     * update the specified project resource
     * @param $request
     * @param $id
     * @return mixed
     */
    public function updateById($request, $id);

    /**
     * create new project resource
     * @param $request
     * @return mixed
     */
    public function create($request);
}
