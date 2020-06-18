<?php

namespace App\Http\Controllers;

use App\ChildTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChildTaskController extends Controller
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'title'         => 'required',
            'description'   => 'required',
            'priority'      => 'required',
            'assignee'      => 'required'
        ]);

        if($validation->passes())
        {
            $childTasks = new ChildTask();
            $childTasks->task_id = $request->taskId;
            $childTasks->title = $request->title;
            $childTasks->description = nl2br($request->description);
            $childTasks->user_id = auth()->user()->id;
            $childTasks->assignee_id = $request->assignee;
            $childTasks->status = 'new';
            $childTasks->save();

            return response()->json(['success' => true, 'message' => 'Child task successfully added']);
        }
        return response()->json($validation->errors());
    }
}
