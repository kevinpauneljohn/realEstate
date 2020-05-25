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
            ->addColumn('action', function ($computation)
            {
                $action = "";
                if(auth()->user()->can('edit computation'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-computation-btn" id="'.$computation->id.'" data-toggle="modal" data-target="#edit-computation-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete computation'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-computation-btn" id="'.$computation->id.'"><i class="fa fa-trash"></i> Delete</a>';
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

    public function show(Request $request,$id)
    {
        $computation = Computation::find($id);
        $modelUnit = ModelUnit::where('project_id',$computation->project_id)->get();
        return response()->json(['details' => $computation, 'modelUnit' => $modelUnit]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'project'   => 'required',
            'model_unit'   => 'required',
            'financing'   => 'required',
            'computation'   => 'required'
        ]);

        if($validation->passes())
        {
            $computation = Computation::find($id);
            $computation->project_id = $request->project;
            $computation->model_unit_id = $request->model_unit;
            $computation->location_type = $request->unit_type;
            $computation->financing = $request->financing;
            $computation->computation = nl2br($request->computation);

            if($computation->isDirty())
            {
                $computation->save();
                return response()->json(['success' => true, 'message' => 'Computation successfully updated']);
            }
            return response()->json(['success' => false, 'message' => 'No changes occurred']);
        }
        return response()->json($validation->errors());
    }

}
