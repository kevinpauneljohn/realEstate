<?php


namespace App\Repositories\RepositoryInterface;


use App\Task;
use App\TaskChecklist;
use App\User;
use App\ActionTaken;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class TaskChecklistRepository implements TaskChecklistInterface
{
    public function create(array $checklist): bool
    {
//        return DB::table('task_checklists')->insert($checklist);
        return TaskChecklist::insert($checklist);
    }


    public function checklists($task_id)
    {
        return DataTables::of($this->getChecklistByTaskId($task_id)->get())
            ->addColumn('completed',function($checklist){
                $action = "";

                $checked = $checklist->status === "completed" ? "checked":"";
                // if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('view checklist')))
                // {
                //     if($checklist->task->status === "on-going" || $checklist->task->status === "completed")
                //     {
                //         $action .= '<input type="checkbox" class="form-control check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" '.$checked.'>';
                //     }
                // }else{
                //     $action .= '<input type="checkbox" class="form-control" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" '.$checked.' disabled>';
                // }

                $action_result = $this->getActionChecklist($checklist->id);
                if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('view checklist')))
                {
                    if($checklist->task->status === "on-going" || $checklist->task->status === "completed")
                    {
                        if ($action_result >= 1) {
                            $action .= '<button type="button" class="btn btn-success btn-xs check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'"><i class="fa fa-check-square"></i></button>';
                        } else {
                            $action .= '<button type="button" class="btn btn-success btn-xs check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'"><i class="fa fa-square"></i></button>';
                        }
                    } else {
                        $action .= '<button type="button" title="Waiting for Assignee to Start the Task..." class="btn btn-success btn-xs check-list-box" disabled><i class="fa fa-square"></i></button>';
                    }
                }else{
                    if ($action_result >= 1) {
                        $action .= '<button type="button" class="btn btn-success btn-xs check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" disabled><i class="fa fa-check-square" aria-hidden="true"></i></button>';
                    } else {
                        $action .= '<button type="button" title="Waiting for Assignee to Start the Task..." class="btn btn-success btn-xs check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" disabled><i class="fa fa-square" aria-hidden="true"></i></button>';
                    }
                }
                return $action;
//                return auth()->user()->task;
//                return $checklist->task;
            })
            ->addColumn('action',function($checklist){
                $action = "";
                $action_result = $this->getActionChecklist($checklist->id);
                if($checklist->task->status === "on-going" || $checklist->task->status === "completed")
                {
                    $title_log = '';
                    if ($action_result >= 1) {
                        $title_log = 'View Log Action Taken';
                        $action .= '<button class="btn btn-info btn-xs log-action" id="'.$checklist->id.'" title="'.$title_log.'" data-toggle="modal" data-target="#action-taken"><i class="far fa-address-book"></i></button>';
                    } else {
                        $title_log = 'No log action taken found.';
                        $action .= '<button class="btn btn-info btn-xs log-action" id="'.$checklist->id.'" title="'.$title_log.'" data-toggle="modal" data-target="#action-taken" disabled><i class="far fa-address-book"></i></button>';
                    }

                    if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('edit checklist')))
                    {
                        $action .= '<button class="btn btn-primary btn-xs edit" id="'.$checklist->id.'" data-toggle="modal" data-target="#edit-checklist"><i class="fas fa-edit"></i></button>';
                        $action .= '<button class="btn btn-danger btn-xs delete" id="'.$checklist->id.'"><i class="fas fa-trash"></i></button>';
                    }
                } else {
                    $action .= '<button type="button" title="Waiting for Assignee to Start the Task..." class="btn btn-danger btn-xs check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" disabled><i class="fa fa-ban" aria-hidden="true"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['completed','action','description'])
            ->make(true);
    }

    public function getActionChecklist($checklist_id)
    {
        $action = ActionTaken::where('task_checklist_id', $checklist_id)->get();

        $count_action = count($action);
        return $count_action;
    }

    public function update($checklist_id)
    {
        return $this->getChecklist($checklist_id)
            ->update(['status' => $this->getChecklist($checklist_id)->first()->status === "pending" ? "completed" : "pending"]);
    }

    public function getChecklistByTaskId($task_id)
    {
        return TaskChecklist::where('task_id',$task_id);
    }

    public function getChecklist($checklist_id)
    {
        return TaskChecklist::where('id',$checklist_id);
    }

    public function updateChecklist($checklist_id,array $checklist)
    {
        return $this->getChecklist($checklist_id)->update($checklist);
    }
}
