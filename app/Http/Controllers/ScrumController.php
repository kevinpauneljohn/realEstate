<?php

namespace App\Http\Controllers;

use App\ChildTask;
use App\Events\TaskEvent;
use App\Priority;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
use App\TaskChecklist;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('pages.scrum.task',compact('priorities','users','agents','priorities'));
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
                'status'    => 'pending',
                'time'    => $request->input('time'),
                'assigned_to'    => $request->input('assign_to'),
                'priority_id'    => $request->input('priority'),
            ];

            if($taskCreated = $this->task->create($task))
            {
                event(new TaskEvent([
                    'assigned' => $taskCreated->user !== null ? $taskCreated->user->fullname : "nobody",
                    'title' => $taskCreated->title,
                    'priority' => $taskCreated->priority->name,
                    'ticket' => str_pad($taskCreated->id, 5, '0', STR_PAD_LEFT)
                ]));
                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn(Task::find($taskCreated->id))
                    ->withProperties($task)->log('created');
                return response()->json(['success' => true, 'message' => 'Task successfully added!']);
            }
            return response()->json(['success' => false, 'message' => 'An error occurred'],400);
        }
        return response()->json($validation->errors());
    }


    public function task_list()
    {
        $tasks = Task::all();
        return $this->task->displayTasks($tasks);
    }

    public function myTaskList()
    {
        $tasks = $this->task->getAssignedTasks(auth()->user()->id);
        return $this->task->displayTasks($tasks);
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
        return $this->task->getTask($id);
    }

    public function update(Request $request, $id)
    {

        $validation = Validator::make($request->all(),[
            'edit_title'         => 'required',
            'edit_description'   => 'required|max:10000',
            'edit_priority'      => 'required',
            'edit_collaborator'  => 'required'
        ],[
            'edit_title.required'                => 'Title field is required',
            'edit_description.required'          => 'Description field is required',
            'edit_priority.required'             => 'Priority field is required',
            'edit_collaborator.required'         => 'Collaborator field is required'
        ]);

        if($validation->passes())
        {
            $changeCtr = 0;
            $task = Task::find($id);
            $task->name = $request->edit_title;
            $task->description = $request->edit_description;
            $task->priority_id = $request->edit_priority;
            if($task->isDirty())
            {
                $task->save();
                $changeCtr = 1;
            }

            ///update the task user table
            DB::table('task_user')->where('task_id','=',$task->id)->delete();
                $collaborator = collect($request->edit_collaborator);
                if($collaborator->count() > 0)
                {
                    foreach ($request->edit_collaborator as $value)
                    {
                        DB::table('task_user')->insert([
                            ['task_id' => $task->id, 'user_id' => $value]
                        ]);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Task successfully updated!']);

        }
        return response()->json($validation->errors());
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview($id)
    {
        return view('pages.scrum.index',[
            'task'  => $this->task->getTask($id),
            'agents' => $this->task->getAgents($this->agents)
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
            $task = Task::where([
                ['id','=',$request->input('task_id')],
                ['assigned_to','=',$request->input('assigned_id')],
            ])->first();
            event(new TaskEvent([
                'assigned' => $task->user !== null ? $task->user->fullname : "nobody",
                'title' => $task->title,
                'priority' => $task->priority->name,
                'ticket' => str_pad($task->id, 5, '0', STR_PAD_LEFT)
            ]));
            activity('task agent')
                ->causedBy(auth()->user()->id)
                ->performedOn(Task::find($request->input('task_id')))
                ->withProperties([
                    'assigned_id' => $request->input('assigned_id'),
                    'task_id' => $request->input('task_id'),
                ])->log('updated the agent');
            return response(['success' => true, 'message' => 'Assignee successfully updated!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!'],400);
    }

    public function myTasks()
    {
        $priorities = Priority::all();
        $users = User::all();
        $agents = $this->task->getAgents($this->agents);
        return view('pages.scrum.mytask',compact('priorities','users','agents','priorities'));
    }
}
