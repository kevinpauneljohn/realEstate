<?php


namespace App\Repositories;


use App\Events\TaskEvent;
use App\Priority;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\TaskRemark;
use App\User;
use App\Watcher;
use App\ActionTaken;
use Spatie\Activitylog\Models\Activity;
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
        $taskWatcher = Watcher::where('task_id',$task_id)
            ->where(function ($query) {
                $query->where('request_status', 'completed');
                $query->orWhere('request_status', 'remove');
                $query->orWhere('request_status', '');
            })
            ->get();
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
                $roles = $this->getRoles($task->assigned_to);
                $fullname = '';
                if (!empty($task->user->fullname)) {
                    $fullname = $task->user->fullname;
                }

                return '<span class="top" title="'.$roles.'" data-original-title="Tooltip on right">'.$fullname.'</span>';
            })
            ->editColumn('created_at',function($task){
                return $task->created_at->format('M d, Y g:i A');
            })
            ->editColumn('status',function($task){
                if ($task->status == 'pending') {
                    return '<span class="right badge badge-warning">'.ucfirst($task->status).'</span>';
                } else if ($task->status == 'on-going') {
                    return '<span class="right badge badge-primary">'.ucfirst($task->status).'</span>';
                } else if ($task->status == 'completed') {
                    return '<span class="right badge badge-success">'.ucfirst($task->status).'</span>';
                }
            })
            ->addColumn('action',function($task){
                $watch_id = $this->getwatchedTicketByUserIdTaskId($task->id);
                $formatted_task_id = str_pad($task->id, 5, '0', STR_PAD_LEFT);
                $action = "";

                if(auth()->user()->can('view task'))
                {
                    $action .= '<a href="'.route('tasks.overview',['id' => $task->id]).'" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->hasRole('super admin') || ($task->created_by === auth()->user()->id && auth()->user()->can('delete task')))
                {
                    if ($task->status != 'completed') {
                        $action .= '<button type="button" class="btn btn-xs btn-primary edit-task-btn" title="Edit" id="'.$task->id.'" data-toggle="modal" data-target="#edit-task-modal"><i class="fas fa-edit"></i></button>';
                    } else {
                        if(auth()->user()->hasRole('super admin')){
                            $action .= '<button type="button" class="btn btn-xs btn-primary edit-task-btn" title="Edit" id="'.$task->id.'" data-toggle="modal" data-target="#edit-task-modal"><i class="fas fa-edit"></i></button>';
                        }
                    }
                }
                if(auth()->user()->hasRole('super admin') || ($task->created_by === auth()->user()->id && auth()->user()->can('delete task')))
                {
                    if ($task->status != 'completed') {
                        $action .= '<button type="button" class="btn btn-xs btn-danger delete-task-btn" title="Delete" id="'.$task->id.'"><i class="fas fa-trash"></i></button>';
                    } else {
                        if(auth()->user()->hasRole('super admin')){
                            $action .= '<button type="button" class="btn btn-xs btn-danger delete-task-btn" title="Delete" id="'.$task->id.'"><i class="fas fa-trash"></i></button>';
                        }
                    }
                }

                if ($task->status != 'completed') {
                    if ($task->assigned_to != auth()->user()->id) {
                        if(!empty($watch_id)) {
                            if ($task->created_by != auth()->user()->id) {
                                if ($watch_id->task_id === $task->id) {
                                    if ($watch_id->request_status == 'pending') {
                                        $action .= '<button type="button" class="btn btn-xs btn-warning request-task-watch" title="Please Wait for request approval" id="'.$task->id.'" data-action="watch" data-id="'.$formatted_task_id.'" disabled><i class="fa fa-tags"></i></button>';
                                    } else if ($watch_id->request_status == 'remove') {
                                        $action .= '<button type="button" class="btn btn-xs btn-warning request-task-watch" title="Please Wait for request approval" id="'.$task->id.'" data-action="watch" data-id="'.$formatted_task_id.'" disabled><i class="fa fa-eye-slash"></i></button>';
                                    } else {
                                        $action .= '<button type="button" class="btn btn-xs btn-warning request-task-watch" title="Request to Remove Watch Ticket" id="'.$task->id.'" data-action="watch" data-id="'.$formatted_task_id.'"><i class="fa fa-eye-slash"></i></button>';
                                    }
                                }
                            }
                        } else {
                            if ($task->created_by != auth()->user()->id) {
                                $action .= '<button type="button" class="btn btn-xs btn-warning request-task-watch" title="Request to Watch this Ticket" id="'.$task->id.'" data-action="unwatch" data-id="'.$formatted_task_id.'"><i class="fa fa-tags"></i></button>';
                            }
                        }
                    }
                }

                return $action;
            })
            ->addColumn('action_taken',function($task){
                return $this->actionTakenCount($task->id);
            })
            ->addColumn('status_due',function($task){
                $due_date = $task->due_date.' '.$task->time;
                return $due_date;
            })
            ->addColumn('date_today',function($task){
                return date('Y-m-d H:i:s');
            })
            ->addColumn('status_text',function($task){
                return $task->status;
            })
            ->rawColumns(['action','id','priority_id','assigned_to','status'])
            ->make(true);
    }

    public function actionTakenCount($id)
    {
        $count = ActionTaken::all()->where('task_checklist_id', $id)->count();

        $get_count = '-';
        if ($count >= 1) {
            $get_count = $count;
        }

        return $get_count;
    }

    public function getRoles($id)
    {
        $user = User::find($id);
        $role = '';
        if (!empty($user)) {
            $role = $user->roles()->whereNotIn('name', ['super admin'])->get(['name']);
        }

        $data = [];
        $datas = '';
        if (!empty($role)) {
            foreach ($role as $roles) {
                $data [] = ucfirst($roles->name);
            }

            $datas = implode(", ", $data);
        }

        return $datas;
    }

    public function getwatchedTicketByUserIdTaskId($task_id)
    {
        $watch = Watcher::select('id', 'task_id','request_status')
            ->where('user_id', auth()->user()->id)
            ->where('task_id', $task_id)
            ->first();

        if (!empty($watch)) {
            return $watch;
        } else {
            return false;
        }

    }

    public function displayRemarks($task_id)
    {
        $taskRemarks = TaskRemark::where('task_id',$task_id)->get();
        return DataTables::of($taskRemarks)
            ->addColumn('task',function($remarks){
                $action = '';
                $action .= '<span class="text-info">'.$remarks->user->fullname.'</span> (Re-Open Task ticket)<br />';
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
        if (auth()->user()->hasRole(["super admin"])) {
            $task = Task::where('id','=',$task_id);
        } else {
            $task = Task::where('id','=',$task_id)->where('created_by','=',auth()->user()->id);
        }

        if($task->count() > 0)
        {
            return $task->delete();
        }
        return false;
    }

    public function updateTaskStatus()
    {
        $tasks = $this->getAllTaskExcept(['completed']);
        $data = array();
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

    public function displayRequest($task_id)
    {
        $taskRequest = Watcher::where('task_id',$task_id)
            ->whereNotIn('request_status', ['completed',''])->get();

        return DataTables::of($taskRequest)
            ->addColumn('name',function($name){
                $action = '';
                $action .= $name->user->fullname;
                return $action;
            })
            ->addColumn('type',function($type){
                $action = '';
                if ($type->request_status == 'pending') {
                    $status_type = 'watch';
                } else if ($type->request_status == 'remove') {
                    $status_type = 'un-watch';
                }

                $action .= $status_type;
                return $action;
            })
            ->addColumn('action',function($task){
                $action = '';
                $action .= '<button type="button" class="btn btn-xs btn-primary update-task-request" data-email="'.$task->user->email.'" data-username="'.$task->user->username.'" data-name="'.$task->user->fullname.'" data-id="'.$task->task_id.'" data-request="'.$task->request_status.'" data-user="'.$task->user_id.'" data-type="approved" title="Approve" id="'.$task->id.'"><i class="fa fa-check-circle"></i></button>';
                $action .= '<button type="button" class="btn btn-xs btn-danger update-task-request" data-email="'.$task->user->email.'" data-username="'.$task->user->username.'" data-name="'.$task->user->fullname.'" data-id="'.$task->task_id.'" data-request="'.$task->request_status.'" data-user="'.$task->user_id.'" data-type="rejected" title="Reject" id="'.$task->id.'"><i class="fa fa-times-circle"></i></button>';
                return $action;
            })
            ->rawColumns(['name','type','action'])
            ->make(true);
    }

    public function logs($task_id)
    {
        $logs = $this->getChecklistActivityTaskId($task_id)->get();
        return DataTables::of($logs)
            ->addColumn('description',function($description){
                $data = '';
                $data_val = 'aaaaa';
                if (!empty($description->properties['action']))
                {
                    $data_val = $description->properties['action'];
                } else if (!empty($description->properties['description'])) {
                    $data_val = $description->properties['description'];
                } else {
                    $data_val = 'bbbbbbbbbbb';
                }

                $data .= 'Action: '.$description->description;
                $data .='<br />';
                $data .= 'Data: '.$data_val;
                return $data;
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
        return Activity::select('id', 'description', 'subject_type', 'causer_id', 'created_at', 'properties')
            ->where('log_name', 'task')
            ->whereJsonContains('properties', ['task_id' => $task_id])
            ->orderBy('id', 'desc');
    }

    public function getAllTasks(): \Illuminate\Support\Collection
    {
        return collect(Task::where('privacy',null)->get())->mapWithKeys(function($item, $key){
            return [
                $key => [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'start' => Carbon::parse($item['due_date']),
                    'allDay' => false,
//                    'color' => '#ff2f25',
                    'color' => $item['status'] == "completed" ? '#28a745' : '#ff2f25',
                    'category' => collect($item)->has('sales_id') ? 'completed' : 'upcoming',
                ]
            ];
        });
    }
}
