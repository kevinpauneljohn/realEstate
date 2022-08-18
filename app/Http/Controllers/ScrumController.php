<?php

namespace App\Http\Controllers;

use App\ChildTask;
use App\Events\TaskEvent;
use App\Priority;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\User;
use App\Watcher;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Mail\SendTask;

class ScrumController extends Controller
{
    private $task;
    private $agents;

    public function __construct(
        TaskInterface $task
    )
    {
        $this->task = $task;
        $this->agents = ['admin','account manager','online warrior','super admin'];
    }
    public function index()
    {
        $priorities = Priority::all();
        $users = User::all();
        $agents = $this->task->getAgents($this->agents);
        $status = [
            'open' => $this->task->getTaskStatusCount('open'),
            'pending' => $this->task->getTaskStatusCount('pending'),
            'ongoing' => $this->task->getTaskStatusCount('on-going'),
            'completed' => $this->task->getTaskStatusCount('completed'),
        ];
        return view('pages.scrum.task',compact('priorities','users','agents','priorities','status'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = Validator::make($request->all(),[
            'title'         => 'required|max:300',
            'description'   => 'required|max:10000',
            'due_date'   => 'required|date',
            'priority'   => 'required',
            'assign_to'   => 'required',
        ]);

        if($validation->passes())
        {
            $task = [
                'created_by'    => auth()->user()->id,
                'title'    => $request->input('title'),
                'description'    => $request->input('description'),
                'due_date'    => $request->input('due_date'),
                'status'    => !empty($request->input('assign_to')) ? 'pending' : 'open',
                'time'    => $request->input('time'),
                'assigned_to'    => $request->input('assign_to'),
                'priority_id'    => $request->input('priority'),
            ];

            $emails = [];
            $watchers = $request->input('watchers');

            if($taskCreated = $this->task->create($task))
            {
                $new_emails = [
                    'email' => $taskCreated->user->email,
                    'username' => $taskCreated->user->username,
                    'message' => strip_tags($request->input('description')),
                    'title' => $request->input('title'),
                    'time' => date('h:i:s  a', strtotime($request->input('time'))),
                    'priority' => $taskCreated->priority->name,
                    'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                    'created_by' => auth()->user()->username,
                    'id' => $taskCreated->id,
                    'sub_title' => 'Kindly review the assigned task ticket.',
                    'submit_message' => auth()->user()->username.' has submitted a task ticket.',
                    'type' => 'new_ticket',
                    'view_ticket' => 'review the ticket.',
                ];
                
                $assigned_email = $this->task->taskEmail($new_emails);

                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $taskCreated->id
                    ];

                    $watchers_data = User::where('id', $watcher)->get();
                    foreach ($watchers_data as $watcher_data) {
                        $watchers_fullname = $watcher_data['firstname'].' '.$watcher_data['lastname'];
                        $watchers_username = $watcher_data['username'];
                        $watchers_email = $watcher_data['email'];
                    }

                    $watcher_emails = [
                        'email' => $watchers_email,
                        'username' => $watchers_username,
                        'message' => strip_tags($request->input('description')),
                        'title' => $request->input('title'),
                        'time' => date('h:i:s  a', strtotime($request->input('time'))),
                        'priority' => $taskCreated->priority->name,
                        'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                        'created_by' => auth()->user()->username,
                        'id' => $taskCreated->id,
                        'sub_title' => '',
                        'submit_message' => 'A ticket on your watch has been submitted!',
                        'type' => 'watched',
                        'view_ticket' => 'view the ticket.',
                    ];

                    $watched_email = $this->task->taskEmail($watcher_emails);
                    Watcher::create($watcher_data);
                }
                event(new TaskEvent([
                    'assigned' => $taskCreated->user !== null ? $taskCreated->user->fullname : "nobody",
                    'title' => $taskCreated->title,
                    'priority' => $taskCreated->priority->name,
                    'ticket' => str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT),
                    'action' => 'task created'
                ]));
                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn(Task::find($taskCreated->id))
                    ->withProperties($task)->log('<span class="text-info">'.auth()->user()->fullname.'</span> created the task');
                return response()->json(['success' => true, 'message' => 'Task successfully added!']);
            }
            return response()->json(['success' => false, 'message' => 'An error occurred'],400);
        }
        return response()->json($validation->errors());
    }

    public function task_list()
    {
        $status =\session('status');
        if(!isset($status))
        {
            $tasks = Task::whereNotIn('status',['completed']);
        }else{
            $tasks = Task::where('status',$status)->get();
        }
        return $this->task->displayTasks($tasks);
    }

    public function myTaskList()
    {
        $tasks = $this->task->getAssignedTasks(auth()->user()->id);
        return $this->task->displayTasks($tasks);
    }

    public function myWatchedList()
    {
        $watcher = $this->task->getWatchedIds(auth()->user()->id);
        
        $data = [];
        foreach ($watcher as $watchers) {
            $data [] = $watchers['task_id'];
        }

        $tasks = Task::whereIn('id', $data)->get();
        return $this->task->displayTasks($tasks);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function changeDisplayTask(Request $request): void
    {
        \session(['status' => $request->input('status')]);
    }

    public function changeDisplayMyTask(Request $request): void
    {
        \session(['statusMyTask' => $request->input('status')]);
    }

    /**
     * @since June 18, 2020
     * @author john kevin paunel
     * fetch the tasks data
     * @param int $id
     * @return object
     * */
    public function show($id)
    {
        $data = [
            'task' => $this->task->getTask($id),
            'watcher' => $this->task->getWatcher($id)
        ];
        return $data;
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'title'         => 'required|max:300',
            'description'   => 'required|max:10000',
            'due_date'   => 'required|date',
            'priority'   => 'required',
            'assign_to'   => 'required',
            'watchers'   => 'required',
        ]);

        $watchers = $request->input('watchers');
        if($validation->passes())
        {
            $data = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => !empty($request->input('assign_to')) ? 'pending' : 'open',
                'due_date' => $request->input('due_date'),
                'time' => $request->input('time'),
                'assigned_to' => $request->input('assign_to'),
                'priority_id' => $request->input('priority'),
            ];
            $get_priority_id = $this->show($id)['task']['priority_id'];
            $watcher = $this->delete_watcher($id);
            if($taskCreated = $this->task->update($id, $data))
            {
                if ($get_priority_id != $request->input('priority')) {
                    $update_emails = [
                        'email' => $taskCreated->user->email,
                        'username' => $taskCreated->user->username,
                        'message' => strip_tags($request->input('description')),
                        'title' => $request->input('title'),
                        'time' => date('h:i:s  a', strtotime($request->input('time'))),
                        'priority' => $taskCreated->priority->name,
                        'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                        'created_by' => auth()->user()->username,
                        'id' => $taskCreated->id,
                        'sub_title' => 'Kindly review the assigned task ticket.',
                        'submit_message' => auth()->user()->username.' has updated the priority of your task ticket.',
                        'type' => 'new_ticket',
                        'view_ticket' => 'review the ticket.',
                    ];
                    $assigned_email = $this->task->taskEmail($update_emails);
                }
                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $taskCreated->id
                    ];

                    if ($get_priority_id != $request->input('priority')) {
                        $watchers_data = User::where('id', $watcher)->get();
                        foreach ($watchers_data as $watcher_data) {
                            $watchers_fullname = $watcher_data['firstname'].' '.$watcher_data['lastname'];
                            $watchers_username = $watcher_data['username'];
                            $watchers_email = $watcher_data['email'];
                        }

                        $watcher_emails = [
                            'email' => $watchers_email,
                            'username' => $watchers_username,
                            'message' => strip_tags($request->input('description')),
                            'title' => $request->input('title'),
                            'time' => date('h:i:s  a', strtotime($request->input('time'))),
                            'priority' => $taskCreated->priority->name,
                            'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                            'created_by' => auth()->user()->username,
                            'id' => $taskCreated->id,
                            'sub_title' => '',
                            'submit_message' => auth()->user()->username.' has updated the priority of a task ticket you are watching.',
                            'type' => 'watched',
                            'view_ticket' => 'view the ticket.',
                        ];
    
                        $watched_email = $this->task->taskEmail($watcher_emails);
                    }
                    Watcher::create($watcher_data);
                }

                event(new TaskEvent([
                    'assigned' => $taskCreated->user !== null ? $taskCreated->user->fullname : "nobody",
                    'title' => $taskCreated->title,
                    'priority' => $taskCreated->priority->name,
                    'ticket' => str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT),
                    'action' => 'task updated'
                ]));

                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn($taskCreated)
                    ->withProperties($taskCreated)->log('<span class="text-info">'.auth()->user()->fullname.'</span> updated the task details');

                return response(['success' => true, 'message' => 'Task successfully updated!', $taskCreated]);
            } else {
                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $id
                    ];

                    Watcher::create($watcher_data);
                }

                return response(['success' => true, 'message' => 'Task watcher successfully updated!']);
            }
            return response(['success' => false, 'message' => 'No changes occurred!']);
        }
        return response($validation->errors());
    }

    public function delete_watcher($id)
    {
        $watcher = Watcher::where('task_id',$id)->delete();

        return $watcher;
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        $watchers = $this->show($id);
        if($this->task->delete($id))
        {
            if (!empty($watchers['watcher'])) {
                foreach ($watchers['watcher'] as $watcher) {
                    $watcher_data = User::where('id', $watcher['user_id'])->get();
                    foreach ($watcher_data as $watcher_data) {
                        $watchers_fullname = $watcher_data['firstname'].' '.$watcher_data['lastname'];
                        $watchers_username = $watcher_data['username'];
                        $watchers_email = $watcher_data['email'];
                    }

                    $watcher_emails = [
                        'email' => $watchers_email,
                        'username' => $watchers_username,
                        'message' => '',
                        'title' => $task->title,
                        'time' => date('h:i:s  a', strtotime($task->time)),
                        'priority' => $task->priority->name,
                        'due_date' => date('F d, Y', strtotime($task->due_date)),
                        'created_by' => auth()->user()->username,
                        'id' => $task->id,
                        'sub_title' => '',
                        'submit_message' => 'The ticket you are watching has been deleted by '.auth()->user()->username,
                        'type' => 'deleted_ticket',
                        'view_ticket' => 'view the ticket.',
                    ];

                    $watched_email = $this->task->taskEmail($watcher_emails);
                }
            }

            $deleted_emails = [
                'email' => $task->user->email,
                'username' => $task->user->username,
                'message' => '',
                'title' => $task->title,
                'time' => date('h:i:s  a', strtotime($task->time)),
                'priority' => $task->priority->name,
                'due_date' => date('F d, Y', strtotime($task->due_date)),
                'created_by' => auth()->user()->username,
                'id' => $task->id,
                'sub_title' => '',
                'submit_message' => 'Your task ticket has been deleted by '.auth()->user()->username,
                'type' => 'deleted_ticket',
                'view_ticket' => 'review the ticket.',
            ];
            $assigned_email = $this->task->taskEmail($deleted_emails);

            event(new TaskEvent([
                'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                'action' => 'task deleted'
            ]));

            activity('task')
                ->causedBy(auth()->user()->id)
                ->performedOn($task)
                ->withProperties($task)->log('<span class="text-info">'.auth()->user()->fullname.'</span> deleted the task');
            return response(['success' => true, 'message' => 'Task successfully deleted!']);
        }
        return response(['success' => false, 'message' => 'You are not allowed to delete this task!']);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview($id)
    {
        $watchers = Watcher::where('task_id', $id)->get();
        $data = [];
        foreach ($watchers as $watcher) {
            $data [] = $watcher['user_id'];
        }

        $users = User::whereIn('id', $data)->get();
        $users_data = [];
        foreach ($users as $user) {
            $users_data [] = [
                'first_name' => $user['firstname'],
                'last_name' => $user['lastname'],
            ];
        }

        return view('pages.scrum.index',[
            'task'  => $this->task->getTask($id),
            'agents' => $this->task->getAgents($this->agents),
            'watchers' => $users_data
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateAgent(Request $request)
    {
        $task_ticket = $this->show($request->task_id);
        $users_data = $this->task->getUser($task_ticket['task']['assigned_to']);
        $priority_name = $this->task->getPriorityById($task_ticket['task']['priority_id'])->name;

        if($taskUpdated = $this->task->setAssignee($request->input('assigned_id'), $request->input('task_id')))
        {
            $deleted_emails = [
                'email' => $users_data['email'],
                'username' => $users_data['username'],
                'message' => '',
                'title' => $task_ticket['task']['title'],
                'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                'priority' => $priority_name,
                'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                'created_by' => auth()->user()->username,
                'id' => $request->task_id,
                'sub_title' => '',
                'submit_message' => auth()->user()->username. ' was assigned your task ticket to another staff.',
                'type' => 'deleted_ticket',
                'view_ticket' => 'review the ticket.',
            ];
            $assigned_email = $this->task->taskEmail($deleted_emails);

            $assigned_user = User::find($request->input('assigned_id'));

            $task = Task::where([
                ['id','=',$request->input('task_id')],
                ['assigned_to','=',$request->input('assigned_id')],
            ])->first();
            event(new TaskEvent([
                'assigned' => $task->user !== null ? $task->user->fullname : "nobody",
                'title' => $task->title,
                'priority' => $task->priority->name,
                'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                'action' => 'task agent updated'
            ]));
            activity('task')
                ->causedBy(auth()->user()->id)
                ->performedOn(Task::find($request->input('task_id')))
                ->withProperties([
                    'assigned_id' => $request->input('assigned_id'),
                    'task_id' => $request->input('task_id'),
                ])->log('<span class="text-info">'.auth()->user()->fullname.'</span> assigned the task to '.$assigned_user->fullname);

            $new_emails = [
                'email' => $assigned_user->email,
                'username' => $assigned_user->username,
                'message' => strip_tags($task_ticket['task']['description']),
                'title' => $task_ticket['task']['title'],
                'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                'priority' => $priority_name,
                'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                'created_by' => auth()->user()->username,
                'id' => $task_ticket['task']['id'],
                'sub_title' => 'Kindly review the assigned task ticket.',
                'submit_message' => auth()->user()->username.' has assigned the task ticket to you.',
                'type' => 'new_ticket',
                'view_ticket' => 'review the ticket.',
            ];
            
            $assigned_new_email = $this->task->taskEmail($new_emails);

            if (!empty($task_ticket['watcher'])) {
                foreach ($task_ticket['watcher'] as $watcher) {
                    $watcher_data = User::where('id', $watcher['user_id'])->get();
                    foreach ($watcher_data as $watcher_data) {
                        $watchers_fullname = $watcher_data['firstname'].' '.$watcher_data['lastname'];
                        $watchers_username = $watcher_data['username'];
                        $watchers_email = $watcher_data['email'];
                    }

                    $watcher_emails = [
                        'email' => $watchers_email,
                        'username' => $watchers_username,
                        'message' => strip_tags($task_ticket['task']['description']),
                        'title' => $task_ticket['task']['title'],
                        'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                        'priority' => $priority_name,
                        'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                        'created_by' => auth()->user()->username,
                        'id' => $request->task_id,
                        'sub_title' => '',
                        'submit_message' => auth()->user()->username.' has assigned the task ticket to '.$assigned_user->fullname,
                        'type' => 'watched',
                        'view_ticket' => 'view the ticket.',
                    ];

                    $watched_email = $this->task->taskEmail($watcher_emails);
                }
            }

            return response(['success' => true, 'message' => 'Assignee successfully updated!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!'],400);
    }

    public function myTasks()
    {
        $priorities = Priority::all();
        $users = User::all();
        $agents = $this->task->getAgents($this->agents);
        $status = [
            'open' => $this->task->getMyTaskStatusCount(auth()->user()->id,'open'),
            'pending' => $this->task->getMyTaskStatusCount(auth()->user()->id,'pending'),
            'ongoing' => $this->task->getMyTaskStatusCount(auth()->user()->id,'on-going'),
            'completed' => $this->task->getMyTaskStatusCount(auth()->user()->id,'completed'),
        ];
        return view('pages.scrum.mytask',compact('priorities','users','agents','priorities','status'));
    }

    public function changeTaskStatus($id)
    {
        // if(auth()->user()->hasRole(['super admin','admin','account manager'])) {
        //     dd('test');
        // }
        //$test = User::find(auth()->user()->upline_id)->hasRole('account manager');
        $test = User::whereHas("roles", function($q) {
            $q->where("name", "super admin");
        })->get();

        dd($test);
        $task = $this->task->getTask($id);
        if($task->assigned_to === auth()->user()->id)
        {
            
            $task->status = $this->setStatus($task->status);
            if($task->save())
            {
                event(new TaskEvent([
                    'assigned' => $task !== null ? $task->user->fullname : "nobody",
                    'title' => $task->title,
                    'priority' => $task->priority->name,
                    'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                    'action' => 'task updated'
                ]));

                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn($task)
                    ->withProperties($task)->log('<span class="text-info">'.auth()->user()->fullname.'</span> updated the task status');

                if ($task->status == 'completed') {
  
                }

                return response(['success' => true,
                    'message' => 'Task ' . $this->setStatus($task->status)
                ]);
            }
            return response(['success' => false, 'message' => 'An error occurred'],400);
        }
        return response(['success' => false, 'message' => 'You are not allowed to access this action'],403);
    }


    /**
     * set the task status
     * @param $status
     * @return string
     */
    private function setStatus($status): string
    {
        switch ($status)
        {
            case $status === "pending":
                return "on-going";
            case $status === "on-going":
                return "completed";
            case $status === "completed":
                    return "done";
            default:
                return "";
        }
    }

    public function reopenTask(Request $request)
    {
        $validation = Validator::make($request->all(),[
           'remarks' => 'required|max:1000',
        ]);

        if($validation->passes())
        {
            if($this->task->reopen($request->input('task_id'),$request->input('remarks')))
            {
                $task = $this->task->update($request->input('task_id'),['status' => 'pending']);

                event(new TaskEvent([
                    'assigned' => $task !== null ? $task->user->fullname : "nobody",
                    'title' => $task->title,
                    'priority' => $task->priority->name,
                    'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                    'action' => 'task updated'
                ]));

                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn($task)
                    ->withProperties($task)->log('<span class="text-info">'.auth()->user()->fullname.'</span> reopened the task');
                return response(['success' => true, 'message' => 'Task Reopen']);
            }
            return response(['success' => false, 'message' => 'An error occurred!'],400);
        }
        return response($validation->errors());
    }

    public function displayRemarks($id)
    {
        return $this->task->displayRemarks($id);
    }

    public function taskStatus()
    {
        Artisan::call('taskStatus:update');
        return Artisan::output();
//        return $this->task->updateTaskStatus();
    }
}
