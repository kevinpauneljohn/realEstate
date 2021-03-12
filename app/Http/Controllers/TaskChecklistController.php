<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\TaskChecklistInterface;
use App\TaskChecklist;
use Illuminate\Http\Request;

class TaskChecklistController extends Controller
{

    private $taskChecklist;

    public function __construct(TaskChecklistInterface $taskChecklist)
    {
        $this->middleware('auth');
        $this->middleware('permission:add checklist')->only('store');
        $this->middleware('permission:view checklist')->only('displayChecklist');
        $this->middleware('permission:edit checklist')->only('updateChecklist');
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

        if($taskChecklist = $this->taskChecklist->create($checklist))
        {
            activity('task checklist')
                ->causedBy(auth()->user()->id)
                ->performedOn(new TaskChecklist())
                ->withProperties($checklist)->log('created');
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


    public function updateChecklist(Request $request, $id)
    {
        if(!empty($request->input('checklist')))
        {
            $checkList = TaskChecklist::find($id);
            $checkList->description = $request->input('checklist');
            if($checkList->isDirty() && $checkList->save()) {
                return response(['success' => true, 'message' => 'Checklist successfully updated!']);
            }
            return response(['success' => false, 'message' => 'No changes occurred!']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checklist = $this->taskChecklist->getChecklist($id)->first();
        if($this->taskChecklist->getChecklist($id)->delete())
        {
            activity('task checklist')
                ->causedBy(auth()->user()->id)
                ->performedOn(new TaskChecklist())
                ->withProperties($checklist)->log('deleted');
            return response(['success' => true, 'message' => 'Successfully deleted!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!'],400);
    }

    public function displayChecklist($task_id)
    {
        return $this->taskChecklist->checklists($task_id);
    }
}
