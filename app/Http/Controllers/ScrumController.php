<?php

namespace App\Http\Controllers;

use App\ChildTask;
use App\Priority;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\Task;
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
        $this->agents = ['admin','account manager','online warrior'];
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
            'title'         => 'required',
            'description'   => 'required|max:10000',
            'due_date'   => 'required|date',
            'priority'   => 'required',
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

    /**
     * @since June 18, 2020
     * @author john kevin paunel
     * fetch the tasks data
     * @param int $id
     * @return object
     * */
    public function show($id)
    {
        $task = Task::find($id);
        $collection = collect($task);

        $merged = $collection->merge(['collaborator' => $task->users->pluck('id')]);

        return $merged->all();
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
        if($this->task->setAssignee($request->input('assigned_id'), $request->input('task_id')))
        {
            return response(['success' => true, 'message' => 'Assignee successfully updated!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!'],400);
    }
}
