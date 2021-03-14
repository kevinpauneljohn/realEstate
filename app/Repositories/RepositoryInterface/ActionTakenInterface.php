<?php


namespace App\Repositories\RepositoryInterface;


interface ActionTakenInterface
{
    /**
     * @param array $action
     * @return mixed
     */
    public function create(array $action);

    /**
     * @param $action_taken
     * @param $action_taken_id
     * @return mixed
     */
    public function update($action_taken, $action_taken_id);

    /**
     * @param $checklist_id
     * @return mixed
     */
    public function displayActions($checklist_id);

    public function getActionTakenByChecklist($checklist_id);
}
