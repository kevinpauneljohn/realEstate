<?php

namespace App\Http\Controllers;

use App\Priority;
use App\Task;
use App\User;
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
}
