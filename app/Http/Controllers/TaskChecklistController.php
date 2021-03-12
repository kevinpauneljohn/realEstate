<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\TaskChecklistInterface;
use Illuminate\Http\Request;

class TaskChecklistController extends Controller
{

    private $taskChecklist;

    public function __construct(TaskChecklistInterface $taskChecklist)
    {
        $this->middleware('auth');
        $this->middleware('permission:add checklist')->only('store');
        $this->middleware('permission:view checklist')->only('displayChecklist');
        $this->taskChecklist = $taskChecklist;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checklist = [];
        foreach ($request->input('checklist') as $key => $value)
        {
            if(!empty($value))
            {
                $checklist[$key] =
                    [
                        'task_id' => $request->input('task_id'),
                        'description' => $value,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
            }
        }

        if($this->taskChecklist->create($checklist))
        {
            return response(['success' => true,'message' => 'Checklist successfully created!']);
        }
        return response(['success' => false,'message' => 'An error occurred'],400);
    }

    /**
     * update a specified checklist
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        if($this->taskChecklist->update($id))
        {
            return response(['success' => true, 'message' => 'Checklist successfully updated!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!']);
    }

    public function displayChecklist($task_id)
    {
        return $this->taskChecklist->checklists($task_id);
    }
}
