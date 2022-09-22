<?php

namespace App\Http\Controllers;

use App\ChildTask;
use App\Events\TaskEvent;
use App\Priority;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\User;
use App\Watcher;
use App\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Mail\SendTask;
use App\Exports\TaskExport;
use Excel;
use App\TaskChecklist;
use App\ActionTaken;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;

class ScrumController extends Controller
{
    private $task;
    private $agents;

    public function __construct(
        TaskInterface $task
    )
    {
        $this->task = $task;
        $this->agents = ['admin','account manager','online warrior','super admin','agent'];
    }
    public function index()
    {
        $roles = Role::select('name')->get()->toArray();
        $priorities = Priority::all();
        $users = User::all();
        $agents = $this->task->getAgents($roles);
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
            // 'assign_to'   => 'required',
            'watchers'   => 'required',
        ]);

        if($validation->passes())
        {
            $privacy = NULL;
            if (!empty($request->input('privacy'))) {
                $privacy = $request->input('privacy');
            }

            $task = [
                'created_by'    => auth()->user()->id,
                'title'    => $request->input('title'),
                'description'    => $request->input('description'),
                'due_date'    => $request->input('due_date'),
                'status'    => !empty($request->input('assign_to')) ? 'pending' : 'open',
                'time'    => $request->input('time'),
                'assigned_to'    => $request->input('assign_to'),
                'priority_id'    => $request->input('priority'),
                'privacy' => $privacy
            ];

            $emails = [];
            $watchers = $request->input('watchers');

            if($taskCreated = $this->task->create($task))
            {
                $user_email = '';
                $user_name = '';
                if (!empty($taskCreated->user->email)) {
                    $user_email = $taskCreated->user->email;
                    $user_name = $taskCreated->user->username;
                }

                $new_emails = [
                    'email' => $user_email,
                    'username' => $user_name,
                    'message' => strip_tags($request->input('description')),
                    'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                    'time' => date('h:i:s  a', strtotime($request->input('time'))),
                    'priority' => $taskCreated->priority->name,
                    'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                    'created_by' => 'Dream Home Guide System Administrator',
                    'id' => $taskCreated->id,
                    'sub_title' => 'Kindly review the assigned task ticket.',
                    'submit_message' => auth()->user()->username.' has submitted a task ticket.',
                    'type' => 'new_ticket',
                    'view_ticket' => 'review the ticket.',
                ];
                
                if (!empty($taskCreated->user->email)) {
                    SendEmailJob::dispatch($new_emails);
                }
                
                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $taskCreated->id,
                        'request_status' => 'completed'
                    ];
                    Watcher::create($watcher_data);
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
                        'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                        'time' => date('h:i:s  a', strtotime($request->input('time'))),
                        'priority' => $taskCreated->priority->name,
                        'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                        'created_by' => 'Dream Home Guide System Administrator',
                        'id' => $taskCreated->id,
                        'sub_title' => '',
                        'submit_message' => 'A ticket on your watch has been submitted!',
                        'type' => 'watched',
                        'view_ticket' => 'view the ticket.',
                    ];
                    if (!empty($watchers_email)) {
                        SendEmailJob::dispatch($watcher_emails);
                    }
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
        $watcher = Watcher::where('user_id', auth()->user()->id)->get();
        $data_watcher = [];
        foreach ($watcher as $watchers) {
            $data_watcher [] = $watchers->task_id;
        }
        if(!isset($status))
        {
            if(auth()->user()->hasRole('super admin')) {
                $tasks = Task::whereNotIn('status',['completed']);
            } else {
                $tasks = Task::whereNotIn('status',['completed'])
                ->where(function ($query) use ($data_watcher) {
                    $query->where('privacy', NULL);
                    $query->orWhere(function ($privacy) {
                        $privacy->where('privacy', 'on');
                        $privacy->where('assigned_to', auth()->user()->id);
                        $privacy->orWhere('created_by', auth()->user()->id);
                    });
                    $query->orWhere(function ($watch) use ($data_watcher) {
                        $watch->where('privacy', 'on');
                        $watch->whereIn('id', $data_watcher);
                    });
                });
            }
        }else{
            if(auth()->user()->hasRole('super admin')) {
                $tasks = Task::where('status',$status)->get();
            } else {
                $tasks = Task::where('status',$status)
                ->where(function ($query) use ($data_watcher) {
                    $query->where('privacy', NULL);
                    $query->orWhere(function ($privacy) {
                        $privacy->where('privacy', 'on');
                        $privacy->where('assigned_to', auth()->user()->id);
                        $privacy->orWhere('created_by', auth()->user()->id);
                    });
                    $query->orWhere(function ($watch) use ($data_watcher) {
                        $watch->where('privacy', 'on');
                        $watch->whereIn('id', $data_watcher);
                    });
                })->get();
            }
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

        $status = \session('statusMyTask');
        if(!isset($status))
        {
            $tasks = Task::whereNotIn('status',['completed'])->whereIn('id', $data);
        }else{
            $tasks = Task::where('status',$status)->whereIn('id', $data)->get();
        }

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
            // 'assign_to'   => 'required',
            'watchers'   => 'required',
        ]);

        $watchers = $request->input('watchers');
        if($validation->passes())
        {
            $privacy = NULL;
            if (!empty($request->input('privacy'))) {
                $privacy = $request->input('privacy');
            }

            $data = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => !empty($request->input('assign_to')) ? 'pending' : 'open',
                'due_date' => $request->input('due_date'),
                'time' => $request->input('time'),
                'assigned_to' => $request->input('assign_to'),
                'priority_id' => $request->input('priority'),
                'privacy' => $privacy
            ];
            $get_priority_id = $this->show($id)['task']['priority_id'];
            $get_watchers = $this->show($id)['watcher'];
            $assigned_user = $this->task->getAssigneeUser($id);

            $get_list_watchers = [];
            foreach ($get_watchers as $get_watcher) {
                $get_list_watchers [] = $get_watcher['user_id'];
            }

            $new_watchers = [];
            foreach ($watchers as $new_watch) {
                $new_watchers [] = $new_watch;
            }

            $email_new_watchers = array_diff($new_watchers, $get_list_watchers);
            $email_removed_watchers = array_diff($get_list_watchers, $new_watchers);

            $watcher = $this->delete_watcher($id);
            if($taskCreated = $this->task->update($id, $data))
            {
                $user_email = '';
                $user_name = '';
                if (!empty($taskCreated->user->email)) {
                    $user_email = $taskCreated->user->email;
                    $user_name = $taskCreated->user->username;
                }

                if ($assigned_user['id'] != $request->input('assign_to')) {
                    $assigned_user_email = '';
                    $assigned_user_name = '';
                    if (!empty($assigned_user['email'])) {
                        $assigned_user_email = $assigned_user['email'];
                        $assigned_user_name = $assigned_user['username'];
                    }

                    $new_assigned_user = [
                        'email' => $user_email,
                        'username' => $user_name,
                        'message' => strip_tags($request->input('description')),
                        'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                        'time' => date('h:i:s  a', strtotime($request->input('time'))),
                        'priority' => $taskCreated->priority->name,
                        'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                        'created_by' => 'Dream Home Guide System Administrator',
                        'id' => $taskCreated->id,
                        'sub_title' => 'Kindly review the assigned task ticket.',
                        'submit_message' => auth()->user()->username.' has assigned the task ticket to you.',
                        'type' => 'new_ticket',
                        'view_ticket' => 'review the ticket.',
                    ];

                    if (!empty($taskCreated->user->email)) {
                        SendEmailJob::dispatch($new_assigned_user);
                    }

                    $old_assigned_user = [
                        'email' => $assigned_user_email,
                        'username' => $assigned_user_name,
                        'message' => strip_tags($request->input('description')),
                        'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                        'time' => date('h:i:s  a', strtotime($request->input('time'))),
                        'priority' => $taskCreated->priority->name,
                        'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                        'created_by' => 'Dream Home Guide System Administrator',
                        'id' => $taskCreated->id,
                        'sub_title' => 'Kindly review the assigned task ticket.',
                        'submit_message' => auth()->user()->username.' was assigned your task ticket to another staff.',
                        'type' => 'deleted_ticket',
                        'view_ticket' => 'review the ticket.',
                    ];

                    if (!empty($assigned_user['email'])) {
                        SendEmailJob::dispatch($old_assigned_user);
                    }
                }
                
                if ($get_priority_id != $request->input('priority')) {
                    $update_emails = [
                        'email' => $user_email,
                        'username' => $user_name,
                        'message' => strip_tags($request->input('description')),
                        'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                        'time' => date('h:i:s  a', strtotime($request->input('time'))),
                        'priority' => $taskCreated->priority->name,
                        'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                        'created_by' => 'Dream Home Guide System Administrator',
                        'id' => $taskCreated->id,
                        'sub_title' => 'Kindly review the assigned task ticket.',
                        'submit_message' => auth()->user()->username.' has updated the priority of your task ticket.',
                        'type' => 'new_ticket',
                        'view_ticket' => 'review the ticket.',
                    ];

                    if (!empty($taskCreated->user->email)) {
                        SendEmailJob::dispatch($update_emails);
                    }
                }

                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $taskCreated->id,
                        'request_status' => 'completed'
                    ];
                    Watcher::create($watcher_data);
                    
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
                            'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                            'time' => date('h:i:s  a', strtotime($request->input('time'))),
                            'priority' => $taskCreated->priority->name,
                            'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                            'created_by' => 'Dream Home Guide System Administrator',
                            'id' => $taskCreated->id,
                            'sub_title' => '',
                            'submit_message' => auth()->user()->username.' has updated the priority of a task ticket you are watching.',
                            'type' => 'watched',
                            'view_ticket' => 'view the ticket.',
                        ];
                        SendEmailJob::dispatch($watcher_emails);
                    }
                }

                if (!empty($email_removed_watchers)) {
                    foreach ($email_removed_watchers as $removed_watcher) {
                        $user_removed_info = $this->task->getUser($removed_watcher);
                        $removed_emails = [
                            'email' => $user_removed_info['email'],
                            'username' => $user_removed_info['username'],
                            'message' => strip_tags($request->input('description')),
                            'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                            'time' => date('h:i:s  a', strtotime($request->input('time'))),
                            'priority' => $taskCreated->priority->name,
                            'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                            'created_by' => 'Dream Home Guide System Administrator',
                            'id' => $taskCreated->id,
                            'sub_title' => '',
                            'submit_message' => 'You have been removed as a watcher on this task ticket.',
                            'type' => 'deleted_ticket',
                            'view_ticket' => 'view the ticket.',
                        ];
                        SendEmailJob::dispatch($removed_emails);
                    }
                }

                if (!empty($email_new_watchers)) {
                    foreach ($email_new_watchers as $new_add_watcher) {
                        $user_new_info = $this->task->getUser($new_add_watcher);
                        $removed_emails = [
                            'email' => $user_new_info['email'],
                            'username' => $user_new_info['username'],
                            'message' => strip_tags($request->input('description')),
                            'title' => '#'.str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                            'time' => date('h:i:s  a', strtotime($request->input('time'))),
                            'priority' => $taskCreated->priority->name,
                            'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                            'created_by' => 'Dream Home Guide System Administrator',
                            'id' => $taskCreated->id,
                            'sub_title' => '',
                            'submit_message' => auth()->user()->username.' has added you to watch this ticket.',
                            'type' => 'watched',
                            'view_ticket' => 'view the ticket.',
                        ];
                        SendEmailJob::dispatch($removed_emails);
                    }
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
                $priority_name = $this->task->getPriorityById($request->input('priority'))->name;
                if (!empty($email_removed_watchers)) {
                    foreach ($email_removed_watchers as $removed_watcher) {
                        $user_removed_info = $this->task->getUser($removed_watcher);
                        $removed_emails = [
                            'email' => $user_removed_info['email'],
                            'username' => $user_removed_info['username'],
                            'message' => strip_tags($request->input('description')),
                            'title' => '#'.str_pad($id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                            'time' => date('h:i:s  a', strtotime($request->input('time'))),
                            'priority' => $priority_name,
                            'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                            'created_by' => 'Dream Home Guide System Administrator',
                            'id' => $id,
                            'sub_title' => '',
                            'submit_message' => 'You have been removed as a watcher on this task ticket.',
                            'type' => 'deleted_ticket',
                            'view_ticket' => 'view the ticket.',
                        ];
                        SendEmailJob::dispatch($removed_emails);
                    }
                }

                if (!empty($email_new_watchers)) {
                    foreach ($email_new_watchers as $new_add_watcher) {
                        $user_new_info = $this->task->getUser($new_add_watcher);
                        $removed_emails = [
                            'email' => $user_new_info['email'],
                            'username' => $user_new_info['username'],
                            'message' => strip_tags($request->input('description')),
                            'title' => '#'.str_pad($id, 5, '0', STR_PAD_LEFT).' '.$request->input('title'),
                            'time' => date('h:i:s  a', strtotime($request->input('time'))),
                            'priority' => $priority_name,
                            'due_date' => date('F d, Y', strtotime($request->input('due_date'))),
                            'created_by' => 'Dream Home Guide System Administrator',
                            'id' => $id,
                            'sub_title' => '',
                            'submit_message' => auth()->user()->username.' has added you to watch this ticket.',
                            'type' => 'watched',
                            'view_ticket' => 'view the ticket.',
                        ];
                        SendEmailJob::dispatch($removed_emails);
                    }
                }

                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $id,
                        'request_status' => 'completed'
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
                        'title' => '#'.str_pad($task->id, 5, '0', STR_PAD_LEFT).' '.$task->title,
                        'time' => date('h:i:s  a', strtotime($task->time)),
                        'priority' => $task->priority->name,
                        'due_date' => date('F d, Y', strtotime($task->due_date)),
                        'created_by' => 'Dream Home Guide System Administrator',
                        'id' => $task->id,
                        'sub_title' => '',
                        'submit_message' => 'The ticket you are watching has been deleted by '.auth()->user()->username,
                        'type' => 'deleted_ticket',
                        'view_ticket' => 'view the ticket.',
                    ];
                    SendEmailJob::dispatch($watcher_emails);
                }
            }

            $user_email = '';
            $user_name = '';
            if (!empty($task->user->email)) {
                $user_email = $task->user->email;
                $user_name = $task->user->username;
            }

            $deleted_emails = [
                'email' => $user_email,
                'username' => $user_name,
                'message' => '',
                'title' => '#'.str_pad($task->id, 5, '0', STR_PAD_LEFT).' '.$task->title,
                'time' => date('h:i:s  a', strtotime($task->time)),
                'priority' => $task->priority->name,
                'due_date' => date('F d, Y', strtotime($task->due_date)),
                'created_by' => 'Dream Home Guide System Administrator',
                'id' => $task->id,
                'sub_title' => '',
                'submit_message' => 'Your task ticket has been deleted by '.auth()->user()->username,
                'type' => 'deleted_ticket',
                'view_ticket' => 'review the ticket.',
            ];
            if (!empty($task->user->email)) {
                SendEmailJob::dispatch($deleted_emails);
            }

            Watcher::where('task_id', $id)->delete();
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
        $watchers = Watcher::where('task_id', $id)
            ->where(function ($query) {
                $query->where('request_status', 'completed');
                $query->orWhere('request_status', 'remove');
                $query->orWhere('request_status', '');
            })->get();

        $data = [];
        foreach ($watchers as $watcher) {
            $data [] = $watcher['user_id'];
        }

        $find_watcher = $watchers = Watcher::where('task_id', $id)
            ->where('user_id', auth()->user()->id)->first();
            
        $users_watcher = User::whereIn('id', $data)->get();
        $users_data = [];
        $watcher_id = [];
        foreach ($users_watcher as $user) {
            $users_data [] = [
                'first_name' => $user['firstname'],
                'last_name' => $user['lastname'],
            ];

            $watcher_id [] = $user->id;
        }

        $users = User::all();

        $request = Watcher::where('task_id',$id)->whereNotIn('request_status', ['completed',''])->get();
        $count_request = count($request);

        // $task_checklist = TaskChecklist::select('id')->where('task_id', $id)->get();
        // $count_task = count($task_checklist);
        $task = Task::where('id',$id)->first();

        if ($task->privacy == 'on') {
            if (auth()->user()->hasRole('super admin')) {
                return view('pages.scrum.index',[
                    'task'  => $this->task->getTask($id),
                    'agents' => $this->task->getAgents($this->agents),
                    'watchers' => $users_data
                ],compact('users', 'watcher_id', 'count_request'));
            } else {
                if (
                    $task->created_by == auth()->user()->id || 
                    $task->assigned_to == auth()->user()->id ||
                    $find_watcher->user_id == auth()->user()->id
                ) {
                    return view('pages.scrum.index',[
                        'task'  => $this->task->getTask($id),
                        'agents' => $this->task->getAgents($this->agents),
                        'watchers' => $users_data
                    ],compact('users', 'watcher_id', 'count_request'));
                } else {
                    return redirect('/');
                }
            }
        } else {
            return view('pages.scrum.index',[
                'task'  => $this->task->getTask($id),
                'agents' => $this->task->getAgents($this->agents),
                'watchers' => $users_data
            ],compact('users', 'watcher_id', 'count_request'));
        }


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
            $user_email = '';
            $user_name = '';
            if (!empty($users_data['email'])) {
                $user_email = $users_data['email'];
                $user_name = $users_data['username'];    
            }

            $deleted_emails = [
                'email' => $user_email,
                'username' => $user_name,
                'message' => '',
                'title' => '#'.str_pad($request->task_id, 5, '0', STR_PAD_LEFT).' '.$task_ticket['task']['title'],
                'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                'priority' => $priority_name,
                'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                'created_by' => 'Dream Home Guide System Administrator',
                'id' => $request->task_id,
                'sub_title' => '',
                'submit_message' => auth()->user()->username. ' was assigned your task ticket to another staff.',
                'type' => 'deleted_ticket',
                'view_ticket' => 'review the ticket.',
            ];

            if (!empty($users_data['email'])) {
                SendEmailJob::dispatch($deleted_emails);
            }

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

            $assigned_user_email = '';
            $asssigned_user_name = '';
            if (!empty($assigned_user->email)) {
                $assigned_user_email = $assigned_user->email;
                $asssigned_user_name = $assigned_user;
            }

            $new_emails = [
                'email' => $assigned_user_email,
                'username' => $asssigned_user_name,
                'message' => strip_tags($task_ticket['task']['description']),
                'title' => '#'.str_pad($task_ticket['task']['id'], 5, '0', STR_PAD_LEFT).' '.$task_ticket['task']['title'],
                'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                'priority' => $priority_name,
                'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                'created_by' => 'Dream Home Guide System Administrator',
                'id' => $task_ticket['task']['id'],
                'sub_title' => 'Kindly review the assigned task ticket.',
                'submit_message' => auth()->user()->username.' has assigned the task ticket to you.',
                'type' => 'new_ticket',
                'view_ticket' => 'review the ticket.',
            ];

            if (!empty($assigned_user->email)) {
                SendEmailJob::dispatch($new_emails);
            }

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
                        'title' => '#'.str_pad($request->task_id, 5, '0', STR_PAD_LEFT).' '.$task_ticket['task']['title'],
                        'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                        'priority' => $priority_name,
                        'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                        'created_by' => 'Dream Home Guide System Administrator',
                        'id' => $request->task_id,
                        'sub_title' => '',
                        'submit_message' => auth()->user()->username.' has assigned the task ticket to '.$assigned_user->fullname,
                        'type' => 'watched',
                        'view_ticket' => 'view the ticket.',
                    ];
                    SendEmailJob::dispatch($watcher_emails);
                }
            }

            return response(['success' => true, 'message' => 'Assignee successfully updated!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!'],400);
    }

    public function updateWatcher(Request $request)
    {
        $task_ticket = $this->show($request->task_id);
        $watchers = $request->input('watchers');
        $get_priority_id = $this->show($request->task_id)['task']['priority_id'];
        $get_task = $this->show($request->task_id)['task'];
        $get_watchers = $this->show($request->task_id)['watcher'];
        $priority_name = $this->task->getPriorityById($get_priority_id)->name;

        $get_list_watchers = [];
        foreach ($get_watchers as $get_watcher) {
            $get_list_watchers [] = $get_watcher['user_id'];
        }

        $new_watchers = [];
        foreach ($watchers as $new_watch) {
            $new_watchers [] = $new_watch;
        }

        $email_new_watchers = array_diff($new_watchers, $get_list_watchers);
        $email_removed_watchers = array_diff($get_list_watchers, $new_watchers);

        $watcher = $this->delete_watcher($request->task_id);
        if (!empty($email_removed_watchers)) {
            foreach ($email_removed_watchers as $removed_watcher) {
                $user_removed_info = $this->task->getUser($removed_watcher);
                $removed_emails = [
                    'email' => $user_removed_info['email'],
                    'username' => $user_removed_info['username'],
                    'message' => '',
                    'title' => '#'.str_pad($request->task_id, 5, '0', STR_PAD_LEFT).' '.$get_task['title'],
                    'time' => date('h:i:s  a', strtotime($get_task['time'])),
                    'priority' => $priority_name,
                    'due_date' => date('F d, Y', strtotime($get_task['due_date'])),
                    'created_by' => 'Dream Home Guide System Administrator',
                    'id' => $request->task_id,
                    'sub_title' => '',
                    'submit_message' => 'You have been removed as a watcher on this task ticket.',
                    'type' => 'deleted_ticket',
                    'view_ticket' => 'view the ticket.',
                ];
                SendEmailJob::dispatch($removed_emails);
            }
        }

        if (!empty($email_new_watchers)) {
            foreach ($email_new_watchers as $new_add_watcher) {
                $user_new_info = $this->task->getUser($new_add_watcher);
                $removed_emails = [
                    'email' => $user_new_info['email'],
                    'username' => $user_new_info['username'],
                    'message' => strip_tags($get_task['description']),
                    'title' => '#'.str_pad($request->task_id, 5, '0', STR_PAD_LEFT).' '.$get_task['title'],
                    'time' => date('h:i:s  a', strtotime($get_task['time'])),
                    'priority' => $priority_name,
                    'due_date' => date('F d, Y', strtotime($get_task['due_date'])),
                    'created_by' => 'Dream Home Guide System Administrator',
                    'id' => $request->task_id,
                    'sub_title' => '',
                    'submit_message' => auth()->user()->username.' has added you to watch this ticket.',
                    'type' => 'watched',
                    'view_ticket' => 'view the ticket.',
                ];
                SendEmailJob::dispatch($removed_emails);
            }
        }

        foreach ($watchers as $watcher) {
            $watcher_data = [
                'user_id' => $watcher,
                'task_id' => $request->task_id,
                'request_status' => 'completed'
            ];

            Watcher::create($watcher_data);
        }

        return response(['success' => true, 'message' => 'Task watcher successfully updated!']);
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
        $users = User::whereHas("roles", function($q) {
            $q->whereIn("name", ["super admin", "account manager", "admin"]);
        })->get();

        $users_data = [];
        foreach ($users as $user) {
            $users_data [] = [
                'username' => $user->username,
                'email' => $user->email,
            ];
        }

        $task_ticket = $this->show($id);
        $priority_name = $this->task->getPriorityById($task_ticket['task']['priority_id'])->name;
        $assigned_users_data = $this->task->getUser($task_ticket['task']['assigned_to'])['username'];

        $task_checklist = TaskChecklist::all()->where('task_id', $id);
        $checklist_id = [];
        foreach ($task_checklist as $checklist_ids){
            $checklist_id [] = $this->getAction($checklist_ids->id);
        }
        
        $action_status = 'complete';
        if (in_array(false, $checklist_id)) {
            $action_status = 'incomplete';
        }
        
        $task = $this->task->getTask($id);
        if($task->assigned_to === auth()->user()->id)
        {
            $task->status = $this->setStatus($task->status);
            if (
                ($task->status == 'completed' && $action_status == 'complete') ||
                $task->status == 'pending' || $task->status == 'on-going'
            ) {
                if($task->save())
                {
                    event(new TaskEvent([
                        'assigned' => $task !== null ? $task->user->fullname : "nobody",
                        'title' => $task->title,
                        'priority' => $task->priority->name,
                        'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                        'action' => 'task updated'
                    ]));
    
                    $status_log =
                    [
                        'task_id' => "$task->id",
                        'description' => $task->user->username. ' Update the Task Ticket Status to '.$task->status,
                        'status' => $task->status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
    
                    activity('task')
                        ->causedBy(auth()->user()->id)
                        ->performedOn($task)
                        ->withProperties($status_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> updated the task status to '.$task->status);
    
                    if ($task->status == 'completed') {
                        foreach ($users_data as $user_data){
                            $complete_emails = [
                                'email' => $user_data['email'],
                                'username' => $user_data['username'],
                                'message' => strip_tags($task_ticket['task']['description']),
                                'title' => '#'.str_pad($task_ticket['task']['id'], 5, '0', STR_PAD_LEFT).' '.$task_ticket['task']['title'],
                                'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                                'priority' => $priority_name,
                                'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                                'created_by' => 'Dream Home Guide System Administrator',
                                'id' => $task_ticket['task']['id'],
                                'sub_title' => 'Kindly review the assigned task ticket.',
                                'submit_message' => $assigned_users_data.' has completed the task ticket.',
                                'type' => 'new_ticket',
                                'view_ticket' => 'review the ticket.',
                            ];
                            //SendEmailJob::dispatch($complete_emails);
                        }
    
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
                                    'title' => '#'.str_pad($task_ticket['task']['id'], 5, '0', STR_PAD_LEFT).' '.$task_ticket['task']['title'],
                                    'time' => date('h:i:s  a', strtotime($task_ticket['task']['time'])),
                                    'priority' => $priority_name,
                                    'due_date' => date('F d, Y', strtotime($task_ticket['task']['due_date'])),
                                    'created_by' => 'Dream Home Guide System Administrator',
                                    'id' => $task_ticket['task']['id'],
                                    'sub_title' => '',
                                    'submit_message' => $assigned_users_data.' has completed the task ticket you are watching.',
                                    'type' => 'watched',
                                    'view_ticket' => 'view the ticket.',
                                ];
                                //SendEmailJob::dispatch($watcher_emails);
                            }
                        }
                    }
    
                    return response([
                        'success' => true,
                        'message' => 'Task ' . $this->setStatus($task->status),
                        'status' => $task->status,
                        'actions' => $action_status
                    ]);
                } else {
                    return response(['success' => false, 'message' => 'An error occurred'],400);
                }
            } else {
                return response([
                    'success' => true,
                    'message' => 'Task action taken Incomplete',
                    'status' => $task->status,
                    'actions' => $action_status
                ]);
            }
        } else {
            return response(['success' => false, 'message' => 'You are not allowed to access this action'],403);
        }
    }

    public function getAction($id)
    {
        $action_taken = ActionTaken::where('task_checklist_id', $id)->get();

        if (count($action_taken) >= 1) {
            return true;
        } else {
            return false;
        }
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
                $task_assignee = $this->task->getTask($request->input('task_id'));
                
                $creator = 'no';
                if ($task_assignee->created_by == auth()->user()->id) {
                    $creator = 'yes';
                }

                $watchers = Watcher::where('task_id', $request->input('task_id'))->get();

                if (!empty($watchers)) {
                    foreach ($watchers as $watcher) {
                        $watchers_data = User::where('id', $watcher['user_id'])->get();
                        foreach ($watchers_data as $watcher_data) {
                            $watchers_fullname = $watcher_data['firstname'].' '.$watcher_data['lastname'];
                            $watchers_username = $watcher_data['username'];
                            $watchers_email = $watcher_data['email'];
                        }
    
                        $watcher_emails = [
                            'email' => $watchers_email,
                            'username' => $watchers_username,
                            'message' =>  strip_tags($request->input('remarks')),
                            'title' => '#'.str_pad($request->input('task_id'), 5, '0', STR_PAD_LEFT).' '.$task_assignee->title,
                            'time' => date('h:i:s  a', strtotime($task_assignee->time)),
                            'priority' => $task_assignee->priority->name,
                            'due_date' => date('F d, Y', strtotime($task_assignee->due_date)),
                            'created_by' => 'Dream Home Guide System Administrator',
                            'id' => $request->input('task_id'),
                            'sub_title' => '',
                            'submit_message' => 'A ticket on your watch has been re-opened!',
                            'type' => 'watched',
                            'view_ticket' => 'view the ticket.',
                        ];
    
                        SendEmailJob::dispatch($watcher_emails);
                    }
                }

                $task = $this->task->update($request->input('task_id'),['status' => 'pending']);

                $user_email = '';
                $user_name = '';
                if (!empty($task_assignee->user->email)) {
                    $user_email = $task_assignee->user->email;
                    $user_name = $task_assignee->user->username;
                }

                $reopen_task = [
                    'email' => $user_email,
                    'username' => $user_name,
                    'message' => strip_tags($request->input('remarks')),
                    'title' => '#'.str_pad($request->input('task_id'), 5, '0', STR_PAD_LEFT).' '.$task_assignee->title,
                    'time' => date('h:i:s  a', strtotime($task_assignee->time)),
                    'priority' => $task_assignee->priority->name,
                    'due_date' => date('F d, Y', strtotime($task_assignee->due_date)),
                    'created_by' => 'Dream Home Guide System Administrator',
                    'id' => $request->input('task_id'),
                    'sub_title' => 'Kindly review the assigned task ticket.',
                    'submit_message' => auth()->user()->username.' has re-opened your task ticket.',
                    'type' => 'new_ticket',
                    'view_ticket' => 'review the ticket.',
                ];

                if (!empty($task_assignee->user->email)) {
                    SendEmailJob::dispatch($reopen_task);
                }

                event(new TaskEvent([
                    'assigned' => $task !== null ? $task->user->fullname : "nobody",
                    'title' => $task->title,
                    'priority' => $task->priority->name,
                    'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT),
                    'action' => 'task updated'
                ]));

                $status_log =
                [
                    'task_id' => "$task->id",
                    'description' => $task->user->username. ' Re-open the task ticket',
                    'status' => $task->status,
                    'created_at' => now(),
                    'updated_at' 
                    => now(),
                ];

                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn($task)
                    ->withProperties($status_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> reopened the task ticket');
                return response(['success' => true, 'message' => 'Task Reopen', 'status' => $task->status, 'creator' => $creator]);
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
    }

    public function exportTasks($status, $type)
    {
        $date = date('Y-m-d_H:i:s_A');
        return Excel::download(new TaskExport($status, $type), 'tasks_'.$status.'-'.$date.'.xlsx');
    }

    public function exportMyTasks($status, $type)
    {
        $date = date('Y-m-d_H:i:s_A');
        return Excel::download(new TaskExport($status, $type), 'My_task_tickets_'.$status.'-'.$date.'.xlsx');
    }

    public function exportMyWatched($status, $type)
    {
        $date = date('Y-m-d_H:i:s_A');
        return Excel::download(new TaskExport($status, $type), 'My_watched_task_'.$status.'-'.$date.'.xlsx');
    }

    public function watchedAction($id, $action)
    {

        $task = Task::find($id);

        $action_taken = '';
        $status = '';
        if ($action == 'watch') {
            $action_taken = 'remove';
            $status = 'remove';
        } else if ($action == 'unwatch') {
            $action_taken = 'add';
            $status = 'pending';
        }

        $watcher_data = [
            'user_id' => auth()->user()->id,
            'task_id' => $id,
            'request_status' => $status
        ];

        if ($status == 'pending') {
            Watcher::create($watcher_data);
        } else if ($status == 'remove') {
            $watch = $this->task->getwatchedTicketByUserIdTaskId($id);

            Watcher::where('id', $watch->id)
                ->update(['request_status' => $status]);
        }

        $watcher_emails = [
            'email' => $task->creator->email,
            'username' => $task->creator->username,
            'message' => strip_tags($task->description),
            'title' => '#'.str_pad($task->id, 5, '0', STR_PAD_LEFT).' '.$task->title,
            'time' => date('h:i:s  a', strtotime($task->time)),
            'priority' => $task->priority->name,
            'due_date' => date('F d, Y', strtotime($task->due_date)),
            'created_by' => 'Dream Home Guide System Administrator',
            'id' => $task->id,
            'sub_title' => 'Kindly review the assigned task ticket.',
            'submit_message' => auth()->user()->username.' has requested to '.$action_taken.' this ticket under watched ticket.',
            'type' => 'new_ticket',
            'view_ticket' => 'view the ticket.',
        ];
        SendEmailJob::dispatch($watcher_emails);

        return response(['success' => true, 'message' => 'Request Successfully sent to '.$task->creator->username.'!']);
    }

    public function displayRequest($id)
    {
        return $this->task->displayRequest($id);
    }

    public function UpdateRequest(Request $request)
    {
        $id = $request->input('id');
        $task_id = $request->input('task_id');
        $watchers_user = $request->input('user_id');
        $watchers_type = $request->input('type');
        $watchers_request = $request->input('request');
        $watchers_fullname = $request->input('fullname');
        $watchers_username = $request->input('username');
        $watchers_email = $request->input('email');

        $task = Task::where('id', $task_id)->first();

        $task_remark = '';
        if ($watchers_request == 'pending') {
            $task_remark = 'watch';
            if ($watchers_type == 'approved') {
                Watcher::where('id', $id)->update(['request_status' => 'completed']);
            } else if ($watchers_type == 'rejected'){
                Watcher::where('id', $id)->delete();
            }
        } else if ($watchers_request == 'remove') {
            $task_remark = 'un-watched';
            if ($watchers_type == 'approved') {
                Watcher::where('id', $id)->delete();
            } else if ($watchers_type == 'rejected'){
                Watcher::where('id', $id)->update(['request_status' => 'completed']);
            }
        }

        $watcher_emails = [
            'email' => $watchers_email,
            'username' => $watchers_username,
            'message' => strip_tags($task->description),
            'title' => '#'.str_pad($task->id, 5, '0', STR_PAD_LEFT).' '.$task->title,
            'time' => date('h:i:s  a', strtotime($task->time)),
            'priority' => $task->priority->name,
            'due_date' => date('F d, Y', strtotime($task->due_date)),
            'created_by' => 'Dream Home Guide System Administrator',
            'id' => $task->id,
            'sub_title' => 'Kindly review the assigned task ticket.',
            'submit_message' => auth()->user()->username.' has '.$watchers_type.' your request to '.$task_remark.' the task ticket.',
            'type' => 'new_ticket',
            'view_ticket' => 'view the ticket.',
        ];

        SendEmailJob::dispatch($watcher_emails);

        $status_log = [
            'task_id' => "$task_id",
            'description' => auth()->user()->username. ' '.$watchers_type.' the request to '.$task_remark.' the task ticket',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        activity('task')
        ->causedBy(auth()->user()->id)
        ->performedOn(Task::find($task_id))
        ->withProperties($status_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> '.$watchers_type.' the request of '.$watchers_fullname.' to '.$task_remark.' the task ticket');

        return response(['success' => true, 'message' => 'Request Successfully '.$watchers_type.'!']);
    }

    public function getRequestCount($id)
    {
        $request = Watcher::where('task_id',$id)->whereNotIn('request_status', ['completed',''])->get();
        $count_request = count($request);

        return $count_request;
    }

    public function displayLog($id)
    {
        return $this->task->logs($id);
    }
}
