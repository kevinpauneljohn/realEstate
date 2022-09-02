<?php

namespace App\Http\Controllers;

use App\ActionTaken;
use App\TaskChecklist;
use App\Repositories\RepositoryInterface\ActionTakenInterface;
use App\Repositories\RepositoryInterface\TaskChecklistInterface;
use App\Repositories\RepositoryInterface\TaskInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionTakenController extends Controller
{
    private $actionTaken;
    private $task;
    private $taskChecklist;

    public function __construct(
        ActionTakenInterface $actionTaken,
        TaskInterface $task,
        TaskChecklistInterface $taskChecklist
    )
    {
        $this->middleware('auth');

        $this->actionTaken = $actionTaken;
        $this->task = $task;
        $this->taskChecklist = $taskChecklist;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'action' => 'required'
        ]);
        if($validation->passes())
        {
            $action = [
                'task_checklist_id' => $request->input('checklist_id'),
                'action' => nl2br($request->input('action')),
                'user_id' => auth()->user()->id
            ];

            if($action = $this->actionTaken->create($action))
            {
                $action_log = [
                    'task_id' => $request->input('task_id'),
                    'id' => $action->id,
                    'task_checklist_id' => $request->input('checklist_id'),
                    'action' => nl2br($request->input('action')),
                    'user_id' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $this->taskChecklist->update($request->input('checklist_id'));
                activity('task')
                    ->causedBy(auth()->user()->id)
                    ->performedOn($action)
                    ->withProperties($action_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> added an action taken');

                return response(['success' => true, 'message' => 'Action successfully created!',
                    'action' => $action,
                    'creator' => User::find($action->user_id)->fullname
                ]);
            }
            return response(['success' => false, 'message' => 'An error occurred!'],400);
        }
        return response($validation->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $action = ActionTaken::find($id);
        $date_today = date('Y-m-d H:i:s');
        $due_date = date('Y-m-d H:i:s', strtotime($action->created_at. '+ 3 days'));
        $action_log = [
            'task_id' => $request->input('task_id'),
            'id' => $id,
            'task_checklist_id' => $action->task_checklist_id,
            'action' => $request->input('action_taken'),
            'user_id' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        if(!empty($request->input('action_taken')))
        {
            // if($actionContent =$this->actionTaken->update($request->input('action_taken'),$id))
            // {
            //     activity('task')
            //         ->causedBy(auth()->user()->id)
            //         ->performedOn(ActionTaken::find($id))
            //         ->withProperties($action_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> edited the action taken');
            //     return response(['success' =>true, 'message' => 'Action taken successfully updated', 'actionContent' => nl2br($request->input('action_taken'))]);
            // }
            // return response(['success' =>false, 'message' => 'No changes occurred!']);
            if ($date_today <= $due_date) {
                if($actionContent =$this->actionTaken->update($request->input('action_taken'),$id))
                {
                    activity('task')
                        ->causedBy(auth()->user()->id)
                        ->performedOn(ActionTaken::find($id))
                        ->withProperties($action_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> edited the action taken');
                        return response(['success' =>true, 'message' => 'Action taken successfully updated', 'actionContent' => nl2br($request->input('action_taken'))]);
                }
                return response(['success' =>false, 'message' => 'No changes occurred!']);
            } else {
                if (auth()->user()->hasRole(["super admin"])) {
                    if($actionContent =$this->actionTaken->update($request->input('action_taken'),$id))
                    {
                        activity('task')
                            ->causedBy(auth()->user()->id)
                            ->performedOn(ActionTaken::find($id))
                            ->withProperties($action_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> edited the action taken');
                            return response(['success' =>true, 'message' => 'Action taken successfully updated', 'actionContent' => nl2br($request->input('action_taken'))]);
                    }
                    return response(['success' =>false, 'message' => 'No changes occurred!']);
                }
                return response(['success' =>false, 'message' => 'Action taken is more than 3 days. Please contact system administrator to update your action taken.']);
            }
        }
        return response(['success' => false, 'message' => 'Empty value is not allowed'],403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $action = ActionTaken::find($id);
        $checklist = TaskChecklist::find($action->task_checklist_id);

        $action_log = [
            'task_id' => "$checklist->task_id",
            'id' => $action->id,
            'task_checklist_id' => $action->task_checklist_id,
            'action' => $action->action,
            'user_id' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        if($this->actionTaken->destroy($id))
        {
            activity('task')
                ->causedBy(auth()->user()->id)
                ->performedOn($action)
                ->withProperties($action_log)->log('<span class="text-info">'.auth()->user()->fullname.'</span> deleted an action taken');
            return response(['success' => true, 'message' => 'Action taken successfully deleted!']);
        }
        return response(['success' => false, 'message' => 'You area not allowed to delete this action!'],400);
    }

    public function actionTakenList($checklist_id)
    {
        $actions = array();
        foreach ($this->actionTaken->getActionTakenByChecklist($checklist_id)->get() as $key => $value)
        {
            $collection = collect($value);

            $merged = $collection->merge(['is_creator' => $value['user_id'] === auth()->user()->getAuthIdentifier(),
                'creator' => User::find($value['user_id'])->fullname,
                'user_id' => $value['user_id'],
                'expired_at' => date('m-d-Y', strtotime($value['created_at']. ' + 3 days')),
                'created_at_format' => date('Y-m-d', strtotime($value['created_at'])),
                'today' => date('m-d-Y')
            ]);

            $actions[$key] = $merged->all();
        }
        return $actions;
    }
}
