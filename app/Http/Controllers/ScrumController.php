<?php

namespace App\Http\Controllers;

use App\Priority;
use App\Task;
use App\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScrumController extends Controller
{
    public function index()
    {
        $priorities = Priority::all();
        $users = User::all();
        return view('pages.scrum.task',compact('priorities','users'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'title'         => 'required',
            'description'   => 'required|max:10000',
            'priority'      => 'required',
            'collaborator'  => 'required'
        ]);

        if($validation->passes())
        {
            $task = new Task();
            $task->name = $request->title;
            $task->description = $request->description;
            $task->priority_id = $request->priority;
            $task->user_id = auth()->user()->id;
            $task->save();

            foreach ($request->collaborator as $value)
            {
                DB::table('task_user')->insert([
                    ['task_id' => $task->id, 'user_id' => $value]
                ]);
            }


            return response()->json(['success' => true, 'message' => 'Task successfully added!']);
        }
        return response()->json($validation->errors());
    }


    public function task_list()
    {
        $tasks = Task::all();
        return DataTables::of($tasks)
            ->editColumn('priority_id',function($task){
                $priority = Priority::find($task->priority_id);
                return '<span class="badge" style="background-color:'.$priority->color.'">'.$priority->name.'</span>';
            })
            ->editColumn('id',function($task){
                $request = str_pad($task->id, 5, '0', STR_PAD_LEFT);
                return '<a href="'.route('requests.show',['request' => $task->id]).'"><span style="color:#007bff">#'.$request.'</span></a>';
            })
            ->editColumn('user_id',function($task){
                $creator = User::find($task->user_id);
                return $creator->fullname;
            })
            ->editColumn('created_at',function($task){
                return $task->created_at->format('M d, Y g:i A');
            })
            ->addColumn('action',function($task){
                $action = "";

                if(auth()->user()->can('view contest'))
                {
                    $action .= '<a href="'.route('tasks.overview',['id' => $task->id]).'" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit contest'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-primary edit-task-btn" title="Edit" id="'.$task->id.'" data-toggle="modal" data-target="#edit-task-modal"><i class="fas fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete contest'))
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

    public function overview($id)
    {
        $users = Task::find($id)->users;
        $priorities = Priority::all();
        return view('pages.scrum.index',compact('priorities','users'));
    }
}