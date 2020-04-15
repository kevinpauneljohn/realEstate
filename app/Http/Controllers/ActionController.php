<?php

namespace App\Http\Controllers;

use App\Action;
use App\Priority;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ActionController extends Controller
{
    public function index()
    {
        return view('pages.actions.index')->with([
            'priorities' => Priority::all(),
        ]);
    }

    public function actionList()
    {
        $actions = Action::all();
        return DataTables::of($actions)
            ->editColumn('priority_id', function($actionModel){
                $priority = '<span class="badge" style="background-color:'.$actionModel->priority->color.'">'.$actionModel->priority->name.'</span>';
                return $priority;
            })
            ->addColumn('action', function ($actionModel)
            {
                $action = "";
                if(auth()->user()->can('edit action'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-action-btn" id="'.$actionModel->id.'" data-target="#edit-action-modal" data-toggle="modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete action'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-action-btn" id="'.$actionModel->id.'" data-toggle="modal" data-target="#delete-action-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','priority_id'])
            ->make(true);
    }


    /**
     * @since April 14, 2020
     * @author john kevin paunel
     * save new action
     * @param Request $request
     * @return mixed
     * */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'action' => 'required',
            'description' => 'required',
            'priority' => 'required'
        ]);

        if($validator->passes())
        {
            $action = new Action();
            $action->name = $request->action;
            $action->description = $request->description;
            $action->priority_id = $request->priority;

            if($action->save())
            {
                return response()->json(['success' => true, 'message' => 'New Action Successfully Created']);
            }else{
                return response()->json(['success' => false, 'message' => 'An Error Occurred!']);
            }
        }
        return response()->json($validator->errors());
    }

    /**
     * @since april 15, 2020
     * @author john kevin paunel
     * update action modal
     * @param Request $request
     * @param int $id
     * @return mixed
     * */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'edit_action'   => 'required',
            'edit_description' => 'required',
            'edit_priority' => 'required'
        ]);

        if($validator->passes()){
            $action = Action::find($id);
            $action->name = $request->edit_action;
            $action->description = $request->edit_description;
            $action->priority_id = $request->edit_priority;

            if($action->isDirty())
            {
                $action->save();
                return response()->json(['success' => true, 'message' => 'Action Successfully Edited']);
            }
            return response()->json(['success' => false, 'message' => 'No Changes Occurred']);
        }
        return response()->json($validator->errors());
    }

    /**
     * @since April 14, 2020
     * @author john kevin paunel
     * get the action object
     * @param int $id
     * @return object
     * */
    public function getAction($id)
    {
        return Action::findOrFail($id);
    }

    /**
     * @since April 15, 2020
     * @author john kevin paunel
     * delete action model
     * @param int $id
     * @return Response
     * */
    public function destroy($id)
    {
        $action = Action::find($id);
        $action->delete();

        return response()->json(['success' => true, 'message' => 'Action Successfully Removed!']);
    }
}
