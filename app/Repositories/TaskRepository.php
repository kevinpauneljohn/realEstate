<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class TaskRepository implements TaskInterface
{
    private $role;
    public function getAgents(array $roles)
    {
        $this->role = $roles;
        return  User::whereHas("roles", function($q){ $q->whereIn("name", $this->role); })->get();
    }

    public function create(array $task)
    {
        return Task::create($task);
    }

    public function getTask($task_id)
    {
        return Task::findOrFail($task_id);
    }

    public function setAssignee($assignee_id, $task_id)
    {
        $task = $this->getTask($task_id);
        $task->assigned_to = $assignee_id;
        return $task->save();
    }

    public function displayTasks($tasks)
    {
        return DataTables::of($tasks)
            ->editColumn('id',function($task){
                $request = str_pad($task->id, 5, '0', STR_PAD_LEFT);
                return '<a href="'.route('requests.show',['request' => $task->id]).'"><span style="color:#007bff">#'.$request.'</span></a>';
            })
            ->editColumn('created_by',function($task){
                return $task->creator->fullname;
            })
            ->editColumn('priority_id',function($task){
                return '<span class="right badge" style="color:white;background-color: '.$task->priority->color.'">'.$task->priority->name.'</span>';
            })
            ->editColumn('description',function($task){
                return Str::of($task->description)->limit(50);
            })
            ->editColumn('due_date',function($task){
                return Carbon::parse($task->due_date)->format('M d, Y').' - '.Carbon::parse($task->time)->format('g:i A');

            })
            ->editColumn('assigned_to',function($task){
                return $task->user->fullname ?? "";
            })
            ->editColumn('created_at',function($task){
                return $task->created_at->format('M d, Y g:i A');
            })
            ->addColumn('action',function($task){
                $action = "";

                if(auth()->user()->can('view checklist'))
                {
                    $action .= '<a href="'.route('tasks.overview',['id' => $task->id]).'" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit checklist'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-primary edit-task-btn" title="Edit" id="'.$task->id.'" data-toggle="modal" data-target="#edit-task-modal"><i class="fas fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete checklist'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-danger delete-rank-btn" title="Delete" id="'.$task->id.'"><i class="fas fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','id','priority_id'])
            ->make(true);
    }

    public function getAssignedTasks($user_id)
    {
        return Task::where('assigned_to',$user_id)->get();
    }
}
