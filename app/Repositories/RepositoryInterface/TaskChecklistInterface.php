<?php


namespace App\Repositories\RepositoryInterface;


interface TaskChecklistInterface
{
    /**
     * create checklist for a specified task
     * @param array $checklist
     * @return mixed
     */
    public function create(array $checklist);

    /**
     * display all created checklist through a specified task id
     * @param $task_id
     * @return mixed
     */
    public function checklists($task_id);

    /**
     * @param $task_id
     * @return mixed
     */
    public function getChecklistByTaskId($task_id);

    /**
     * @param $checklist_id
     * @return mixed
     */
    public function getChecklist($checklist_id);

    /**
     * @param $checklist_id
     * @return mixed
     */
    public function update($checklist_id);

}
