<?php

namespace App\Http\Controllers;

use App\Computation;
use App\ModelUnit;
use App\Project;
use Carbon\Carbon;
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
            ->editColumn('user_id', function($computation){
                return $computation->user->fullname;
            })
            ->editColumn('updated_at', function($computation){
                return $computation->updated_at->format('M d, Y');
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
            $computation->user_id = auth()->user()->id;

            if($computation->isDirty())
            {
                $computation->save();
                return response()->json(['success' => true, 'message' => 'Computation successfully updated']);
            }
            return response()->json(['success' => false, 'message' => 'No changes occurred']);
        }
        return response()->json($validation->errors());
    }

    public function destroy($id)
    {
        $computation = Computation::find($id);
        $computation->delete();
        return response()->json(['success' => true,'message' => 'Computation successfully deleted']);
    }

    /**
     * @since May 26, 2020
     * @author john kevin paunel
     * get the sample computation of a specific project
     * @param Request $request
     * @return mixed
     * */
    public function sampleComputations(Request $request)
    {
        $computation = Computation::where('project_id','=',$request->project_label)
            ->where('model_unit_id','=',$request->model_unit_label)
            ->get();

        $data = collect($computation);
        $filtered = $data->map(function($item, $key){
            $value = $item;
            if($item->location_type === null)
            {
                $item->location_type = "\n";
            }else{
                $location = $item->location_type;
                $item->location_type = 'Unit Location: '.$location."\n\n";
            }
            if($item->project_id)
            {
                $id = $item->project_id;
                $item->project_id = Project::find($id)->name."\n";
            }
            if($item->model_unit_id)
            {
                $id = $item->model_unit_id;
                $item->model_unit_id = ModelUnit::find($id)->name."\n";
            }
            if($item->financing)
            {
                $financing = $item->financing;
                $item->financing = $financing." Sample Computation\n";
            }
            return $value;
        });

        return $filtered;
    }
}
