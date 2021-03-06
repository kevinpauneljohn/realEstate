<?php

namespace App\Http\Controllers;

use App\ChildTask;
use App\User;
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

    public function show($id)
    {
        $childTask = ChildTask::find($id);
        $data = collect($childTask);
        $filtered = $data->map(function ($item, $key){
            if($key === 'user_id')
            {
                $id = $item;
                $item = User::find($id)->fullname;
            }
            if($key === 'created_at')
            {
                $item = date('M d, Y', strtotime($item));
            }
            return $item;
        });
        return $filtered;
    }
}
