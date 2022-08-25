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
                        'description' => nl2br($value),
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                $checklist_log =
                    [
                        'task_id' => $request->input('task_id'),
                        'description' => nl2br($value),
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn(new TaskChecklist())
                    ->withProperties($checklist_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> Created a checklist');
            }
        }

        if($taskChecklist = $this->taskChecklist->create($checklist))
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


    public function updateChecklist(Request $request, $id)
    {
        if(!empty($request->input('checklist')))
        {
            $checkList = TaskChecklist::find($id);
            //$checkList->description = nl2br($request->input('checklist'));
            $checkList->description = $request->input('checklist');

            $checklist_log =
            [
                'task_id' => "$checkList->task_id",
                'id' => $checkList->id,
                'description' => $checkList->description,
                'status' => $checkList->status,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if($checkList->isDirty() && $checkList->save()) {
                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn($checkList)
                    ->withProperties($checklist_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> updated a checklist');
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
        $checklist_log =
        [
            'task_id' => "$checklist->task_id",
            'id' => $checklist->id,
            'description' => $checklist->description,
            'status' => $checklist->status,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if($this->taskChecklist->getChecklist($id)->delete())
        {
            activity('task')
                ->causedBy(auth()->user()->id)
                ->performedOn(new TaskChecklist())
                ->withProperties($checklist_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> deleted a checklist');
            return response(['success' => true, 'message' => 'Successfully deleted!']);
        }
        return response(['success' => false, 'message' => 'An error occurred!'],400);
    }

    public function displayChecklist($task_id)
    {
        return $this->taskChecklist->checklists($task_id);
    }

    public function displayLog($id)
    {
        return $this->taskChecklist->logs($id);
    }
}
