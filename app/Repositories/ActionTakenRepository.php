<?php


namespace App\Repositories;


use App\ActionTaken;
use App\Repositories\RepositoryInterface\ActionTakenInterface;
use Yajra\DataTables\Facades\DataTables;

class ActionTakenRepository implements ActionTakenInterface
{
    public function create(array $action)
    {
        return ActionTaken::create($action);
    }

    public function displayActions($checklist_id)
    {
        $actionTaken =$this->getActionTakenByChecklist($checklist_id)->get();
        return DataTables::of($actionTaken)
            ->addColumn('action-button',function($checklist){
                $action = "";

                return $action;
//                return auth()->user()->task;
//                return $checklist->task;
            })
        ->rawColumns(['completed','action-button'])
        ->make(true);
    }

    public function getActionTakenByChecklist($checklist_id)
    {
        return ActionTaken::where('task_checklist_id',$checklist_id);
    }

    public function getAction($action_taken_id)
    {
        return ActionTaken::find($action_taken_id);
    }

    public function update($action_taken, $action_taken_id): bool
    {
        $actionTaken = $this->getAction($action_taken_id);
        $actionTaken->action = nl2br($action_taken);
        if($actionTaken->isDirty() && $actionTaken->user_id === auth()->user()->id)
        {
            return $actionTaken->save();
        } else if ($actionTaken->isDirty() && auth()->user()->hasRole(["super admin"])) {
            return $actionTaken->save();
        }
        return false;
    }

    public function destroy($action_taken_id): bool
    {
        $action = $this->getAction($action_taken_id);
        if(auth()->user()->hasRole(["super admin"]))
        {
            return $action->delete();
        }
        return false;
    }
}
