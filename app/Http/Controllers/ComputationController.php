<?php

namespace App\Http\Controllers;

use App\Computation;
use App\ModelUnit;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ComputationController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('pages.computations.index')->with([
            'projects'  => $projects
        ]);
    }

    public function computation_list()
    {
        $computations = Computation::all();

        return DataTables::of($computations)
            ->editColumn('project_id',function($computation){
                return $computation->project->name;
            })
            ->editColumn('model_unit_id',function($computation){
                return ModelUnit::find($computation->model_unit_id)->name;
            })
            ->addColumn('action', function ($role)
            {
                $action = "";
                if(auth()->user()->can('edit role'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-role-btn" id="'.$role->id.'" data-toggle="modal" data-target="#edit-role-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete role'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-role-btn" id="'.$role->id.'" data-toggle="modal" data-target="#delete-role-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['notification','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'project'   => 'required',
            'model_unit'   => 'required',
            'financing'   => 'required',
            'computation'   => 'required'
        ]);

        if($validation->passes())
        {
            $computation = new Computation();
            $computation->project_id = $request->project;
            $computation->model_unit_id = $request->model_unit;
            $computation->location_type = $request->unit_type;
            $computation->financing = $request->financing;
            $computation->computation = nl2br($request->computation);
            $computation->user_id = auth()->user()->id;

            $computation->save();
            return response()->json(['success' => true, 'message' => 'Computation successfully added!']);
        }
        return response()->json($validation->errors());
    }

}
