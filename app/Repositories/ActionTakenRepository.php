<?php


namespace App\Repositories;


use App\ActionTaken;
use App\User;
use App\Task;
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

    public function lists($action){
        return DataTables::of($action)
            ->editColumn('action',function($action){
                return $action->action;
            })
            ->editColumn('date',function($action){
                return $action->created_at->format('M d, Y g:i A');

            })
            ->editColumn('creator',function($action){
                $user = $this->getUser($action->user_id);

                return $user;
            })
            ->addColumn('button',function($action){
                $get_task = $this->getTask($action->task_checklist_id);
    
                $buttons = "";
                $date_today = date('Y-m-d H:i:s');
                $due_date = date('Y-m-d H:i:s', strtotime($get_task['updated_at']. '+ 3 days'));

                if ($date_today <= $due_date) {
                    if (
                        auth()->user()->id == $get_task['assigned_to'] || 
                        auth()->user()->id == $get_task['creator'] ||
                        auth()->user()->hasRole(["super admin"])
                    ) {
                        $buttons .= '<button type="button" class="btn btn-xs btn-primary edit-action-btn" title="Edit" id="'.$action->id.'" data-action="'.$action->action.'"><i class="fas fa-edit"></i></button>';
                    }
                } else {
                    if (auth()->user()->hasRole(["super admin"])) {
                        $buttons .= '<button type="button" class="btn btn-xs btn-primary edit-action-btn" title="Edit" id="'.$action->id.'" data-action="'.$action->action.'"><i class="fas fa-edit"></i></button>';
                    } else {
                        $buttons .= '<button type="button" class="btn btn-xs btn-primary" title="Unable to Edit. Please Contact the System Administrator" id="'.$action->id.'" data-action="'.$action->action.'" disabled><i class="fas fa-edit"></i></button>';
                    }
                }

                if (auth()->user()->hasRole(["super admin"])) {
                    $buttons .= '<button type="button" class="btn btn-xs btn-danger delete-action-btn" title="Delete" id="'.$action->id.'"><i class="fas fa-trash"></i></button>';
                }

                return $buttons;
            })
            ->rawColumns(['action','date','creator','button'])
            ->make(true);
    }

    public function getTask($id)
    {
        $task = Task::where('id', $id)->first();
        $data = [
            'assigned_to' => $task->assigned_to,
            'updated_at' => $task->updated_at,
            'creator' => $task->created_by
        ];
        return $data;
    }

    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        return $user->fullname;
    }
}
