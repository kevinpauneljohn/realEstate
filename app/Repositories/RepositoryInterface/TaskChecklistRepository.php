<?php


namespace App\Repositories\RepositoryInterface;


use App\Task;
use App\TaskChecklist;
use App\User;
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
                if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('view checklist')))
                {
                    if($checklist->task->status === "on-going" || $checklist->task->status === "completed")
                    {
                        $action .= '<input type="checkbox" class="form-control check-list-box" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" '.$checked.'>';
                    }
                }else{
                    $action .= '<input type="checkbox" class="form-control" data-id="'.$checklist->task_id.'" value="'.$checklist->id.'" '.$checked.' disabled>';
                }
                return $action;
//                return auth()->user()->task;
//                return $checklist->task;
            })
            ->addColumn('action',function($checklist){
                $action = "";

                if($checklist->task->status === "on-going" || $checklist->task->status === "completed")
                {
                    $action .= '<button class="btn btn-info btn-xs log-action" id="'.$checklist->id.'" title="log action taken" data-toggle="modal" data-target="#action-taken"><i class="far fa-address-book"></i></button>';
                    if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($checklist->task->assigned_to === auth()->user()->id && auth()->user()->can('edit checklist')))
                    {
                        $action .= '<button class="btn btn-default btn-xs edit" id="'.$checklist->id.'" data-toggle="modal" data-target="#edit-checklist"><i class="fas fa-edit"></i></button>';
                        $action .= '<button class="btn btn-default btn-xs delete" id="'.$checklist->id.'"><i class="fas fa-trash"></i></button>';
                    }
                }
                return $action;
            })
            ->rawColumns(['completed','action','description'])
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

    public function logs($task_id)
    {
        $logs = $this->getChecklistActivityTaskId($task_id)->get();
        return DataTables::of($logs)
            ->addColumn('description',function($description){
                return strip_tags($description->description);
            })
            ->addColumn('description',function($description){
                return strip_tags($description->description);
            })
            ->addColumn('subject_type',function($subject){
                return substr($subject->subject_type, 4);
            })
            ->addColumn('causer_id',function($user){
                $users = User::where('id', $user->causer_id)->first();
                return $users['username'].' [ '.$users['firstname'].' '.$users['lastname'].' ]';
            })
            ->addColumn('created_at',function($created){
                return date('F d, Y H:i:s A', strtotime($created->created_at));
            })
            ->rawColumns(['id', 'description', 'subject_type', 'causer_id', 'created_at'])
            ->make(true);
    }

    public function getChecklistActivityTaskId($task_id)
    {
        return Activity::select('id', 'description', 'subject_type', 'causer_id', 'created_at')
            ->where('log_name', 'task')
            ->whereJsonContains('properties', ['task_id' => $task_id])
            ->orderBy('id', 'desc');
    }

    // public function getUser($id)
    // {
    //     $user = User::select('username')->where('id', $id)->get();
    //     return 'test';
    // }
}
