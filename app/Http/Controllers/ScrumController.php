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

            $watchers = $request->input('watchers');

            if($taskCreated = $this->task->create($task))
            {
                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $taskCreated->id
                    ];

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

            $watcher = $this->delete_watcher($id);
            if($taskCreated = $this->task->update($id, $data))
            {
                foreach ($watchers as $watcher) {
                    $watcher_data = [
                        'user_id' => $watcher,
                        'task_id' => $taskCreated->id
                    ];

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
        if($this->task->delete($id))
        {
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
        if($taskUpdated = $this->task->setAssignee($request->input('assigned_id'), $request->input('task_id')))
        {
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
