<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\ActionTakenInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionTakenController extends Controller
{
    private $actionTaken;

    public function __construct(ActionTakenInterface $actionTaken)
    {
        $this->middleware('auth');

        $this->actionTaken = $actionTaken;
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
                'action' => nl2br($request->input('action'))
            ];
            if($this->actionTaken->create($action))
            {
                return response(['success' => true, 'message' => 'Action successfully created!']);
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
        if(!empty($request->input('action_taken')))
        {
            if($actionContent =$this->actionTaken->update($request->input('action_taken'),$id))
            {
                return response(['success' =>true, 'message' => 'Action taken successfully updated', 'actionContent' => nl2br($request->input('action_taken'))]);
            }
            return response(['success' =>false, 'message' => 'No changes occurred!']);
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
        //
    }

    public function actionTakenList($checklist_id)
    {
        return $this->actionTaken->getActionTakenByChecklist($checklist_id)->get();
    }
}
