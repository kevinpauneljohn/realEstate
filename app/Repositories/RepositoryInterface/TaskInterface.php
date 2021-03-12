<?php


namespace App\Repositories\RepositoryInterface;


interface TaskInterface
{
    /**
     * get all lists of allowed agents
     * @param array $roles
     * @return mixed
     */
    public function getAgents(array $roles);

    /**
     * save new task
     * @param array $task
     * @return mixed
     */
    public function create(array $task);

    /**
     * get a specified task
     * @param $task_id
     * @return mixed
     */
    public function getTask($task_id);
}
