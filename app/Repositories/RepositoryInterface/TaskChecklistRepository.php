<?php


namespace App\Repositories\RepositoryInterface;


use App\Task;
use App\TaskChecklist;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
                if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('view checklist')))
                {
                    $action .= '<input type="checkbox" class="form-control check-list-box" value="'.$checklist->id.'" '.$checked.'>';
                }else{
                    $action .= '<input type="checkbox" class="form-control" value="'.$checklist->id.'" '.$checked.' disabled>';
                }
                return $action;
//                return auth()->user()->task;
//                return $checklist->task;
            })
            ->addColumn('action',function($checklist){
                $action = "";

                $action .= '<button class="btn btn-info btn-xs log-action" id="'.$checklist->id.'" title="log action taken" data-toggle="modal" data-target="#action-taken"><i class="far fa-address-book"></i></button>';
                if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('edit checklist')))
                {
                    $action .= '<button class="btn btn-default btn-xs edit" id="'.$checklist->id.'" data-toggle="modal" data-target="#edit-checklist"><i class="fas fa-edit"></i></button>';
                    $action .= '<button class="btn btn-default btn-xs delete" id="'.$checklist->id.'"><i class="fas fa-trash"></i></button>';
                }
                return $action;
//                return auth()->user()->task;
//                return $checklist->task;
            })
            ->rawColumns(['completed','action'])
            ->make(true);
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
