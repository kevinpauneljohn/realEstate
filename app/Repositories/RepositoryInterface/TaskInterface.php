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
     * @param $task_id
     * @param array $data
     * @return mixed
     */
    public function update($task_id, array $data);

    /**
     * get a specified task
     * @param $task_id
     * @return mixed
     */
    public function getTask($task_id);

    /**
     * @param $assignee_id
     * @param $task_id
     * @return mixed
     */
    public function setAssignee($assignee_id, $task_id);

    /**
     * @param $tasks
     * @return mixed
     */
    public function displayTasks($tasks);

    /**
     * @param $task_id
     * @return mixed
     */
    public function displayRemarks($task_id);

    /**
     * @param $user_id
     * @return mixed
     */
    public function getAssignedTasks($user_id);

    /**
     * @param $task_id
     * @param $remarks
     * @return mixed
     */
    public function reopen($task_id, $remarks);
}
