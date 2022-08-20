<?php


namespace App\Repositories;


use App\Events\TaskEvent;
use App\Priority;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\TaskRemark;
use App\User;
use App\Watcher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use \App\Mail\SendTask;

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

    public function getAssigneeUser($task_id){
        $taskAssignee = Task::select('assigned_to')->where('id',$task_id)->first();
        $users = User::where('id', $taskAssignee->assigned_to)->first();

        $data = [];
        $data = [
            'email' => $users->email,
            'username' => $users->username,
            'id' => $taskAssignee->assigned_to
        ];
        return $data;
    }

    public function getWatcher($task_id){
        $taskWatcher = Watcher::where('task_id',$task_id)->get();
        return $taskWatcher;
    }

    public function getTask($task_id)
    {
        return Task::findOrFail($task_id);
    }

    public function update($task_id, array $data)
    {
        $task = $this->getTask($task_id);
        $task->fill($data);
        if($task->isDirty())
        {
            $task->save();
            return $task;
        }
        return false;

    }

    public function setAssignee($assignee_id, $task_id)
    {
        $task = $this->getTask($task_id);
        $task->assigned_to = $assignee_id;

        //set the status to pending if it was assigned to an agent and open if there are no agents assigned
        $task->status = !empty($assignee_id) ? "pending" : "open";
        return $task->save();
    }

    public function displayTasks($tasks)
    {
        return DataTables::of($tasks)
            ->editColumn('id',function($task){
                $request = str_pad($task->id, 5, '0', STR_PAD_LEFT);
                return '<a href="'.route('tasks.overview',$task->id).'"><span style="color:#007bff">#'.$request.'</span></a>';
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

                if(auth()->user()->can('view task'))
                {
                    $action .= '<a href="'.route('tasks.overview',['id' => $task->id]).'" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit task'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-primary edit-task-btn" title="Edit" id="'.$task->id.'" data-toggle="modal" data-target="#edit-task-modal"><i class="fas fa-edit"></i></button>';
                }
                if(auth()->user()->hasRole('super admin') || ($task->created_by === auth()->user()->id && auth()->user()->can('delete task')))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-danger delete-task-btn" title="Delete" id="'.$task->id.'"><i class="fas fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','id','priority_id'])
            ->make(true);
    }

    public function displayRemarks($task_id)
    {
        $taskRemarks = TaskRemark::where('task_id',$task_id)->get();
        return DataTables::of($taskRemarks)
            ->addColumn('task',function($remarks){
                $action = '';
                $action .= '<h6 class="text-info">'.$remarks->user->fullname.'</h6>';
                $action .= '<span class="text-muted">'.Carbon::parse($remarks->created_at)->format('Y, M d g:i:a').'</span>';
                $action .= '<p class="text-bold">'.$remarks->remarks.'</p>';

                return $action;
            })
            ->addColumn('action',function($remarks){
                $action = "";
                return $action;
            })
            ->rawColumns(['action','task'])
            ->make(true);

    }

    public function getAssignedTasks($user_id)
    {
        $status =\session('statusMyTask');
        if(!isset($status))
        {
            $tasks = Task::where('assigned_to',$user_id)->get();
        }else{
            $tasks = Task::where('status',$status)->where('assigned_to',$user_id)->get();
        }
        return $tasks;
    }

    public function getWatchedIds($user_id)
    {
        $watch = Watcher::select('task_id')->where('user_id',$user_id)->get();
        return $watch;
    }

    public function getWatchedTickets($task_id)
    {
        $ticket = Task::where('id',$task_id)->get();
        return $task_id;
    }

    public function reopen($task_id, $remarks): TaskRemark
    {
        $taskRemarks = new TaskRemark();
        $taskRemarks->user_id = auth()->user()->id;
        $taskRemarks->remarks = $remarks;
        $taskRemarks->task_id = $task_id;
        $taskRemarks->save();
        return $taskRemarks;
    }

    public function getTaskStatusCount($status)
    {
        return Task::where('status',$status)->count();
    }

    public function getMyTaskStatusCount($user_id, $status)
    {
        return Task::where('assigned_to',$user_id)
            ->where('status',$status)->count();
    }

    public function delete($task_id): bool
    {
        $task = Task::where('created_by','=',auth()->user()->id)
            ->where('id','=',$task_id);
        if($task->count() > 0)
        {
            return $task->delete();
        }
        return false;
    }

    public function updateTaskStatus()
    {
        $tasks = $this->getAllTaskExcept(['completed']);
//        $data = array();
        foreach ($tasks as $key => $task)
        {
            $dueDate = Carbon::parse($task->due_date.' '.$task->time);
            $data[$key] = now().' ---- '.$task->due_date.' '.$task->priority->name.' ---- '.now()->diffInDays($dueDate, false);
            if(now()->diffInDays($dueDate, false) >= $this->getPriority('Low')->days)
            {
                $this->update($task->id,['priority_id' => $this->getPriority('Low')->id]);
            }
            elseif(now()->diffInDays($dueDate, false) >= $this->getPriority('Normal')->days)
            {
                $this->update($task->id,['priority_id' => $this->getPriority('Normal')->id]);
            }
            elseif(now()->diffInDays($dueDate, false) >= $this->getPriority('Warning')->days)
            {
                $this->update($task->id,['priority_id' => $this->getPriority('Warning')->id]);
            }
            elseif(now()->diffInDays($dueDate, false) <= $this->getPriority('Critical')->days)
            {
                $this->update($task->id,['priority_id' => $this->getPriority('Critical')->id]);
            }

            event(new TaskEvent([
                'assigned' => $task !== null ? $task->user->fullname : "nobody",
                'title' => $task->title,
                'priority' => $task->priority->name,
                'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                'action' => 'task updated'
            ]));

        }
//        return 'task status: updated!';
        return $data;
    }

    public function getPriority($title)
    {
        return Priority::where('name',$title)->first();
    }

    public function getAllTaskExcept(array $status)
    {
        return Task::whereNotIn('status',$status)->get();
    }

    public function getUser($id)
    {
        $users = User::where('id', $id)->get();
        $user_data = [];
        foreach ($users as $user) {
            $user_data = [
                'username' => $user->username,
                'email' => $user->email,
                'id' => $user->id,
            ];
        }

        return $user_data;
    }

    public function getPriorityById($id)
    {
        return Priority::where('id',$id)->first();
    }

    public function taskEmail(array $emails)
    {
        \Mail::to($emails['email'])->send(new SendTask($emails));
        return true;
    }
}
