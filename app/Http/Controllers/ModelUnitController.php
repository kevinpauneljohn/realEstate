<?php

namespace App\Http\Controllers;

use App\ModelUnit;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ModelUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.modelUnits.index')->with([
            'projects'  => Project::all(),
        ]);
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
        $validator = Validator::make($request->all(),[
            'project'   => 'required',
            'name'      => 'required|unique:model_units,name',
        ]);

        if($validator->passes())
        {
            $project = new ModelUnit();
            $project->project_id = $request->project;
            $project->user_id = auth()->user()->id;
            $project->name = $request->name;
            $project->house_type = $request->house_type;
            $project->floor_level = $request->floor_level;
            $project->lot_area = $request->lot_area;
            $project->floor_area = $request->floor_area;
            $project->description = $request->description;

            if($project->save())
            {
                return response()->json(['success' => true]);
            }

        }
        return response()->json($validator->errors());
    }

    /**
     * Feb. 18, 2020
     * @author john kevin paunel
     * display all leads
     * */
    public function model_unit_list()
    {
        $model_units = ModelUnit::all();
        return DataTables::of($model_units)
            ->editColumn('lot_area',function($model_unit){
                $lot_area = "";
                if($model_unit->lot_area != null)
                {
                    $lot_area = $model_unit->lot_area.' sqm';
                }
                return $lot_area;
            })
            ->editColumn('floor_area',function($model_unit){
                $floor_area = "";
                if($model_unit->floor_area != null)
                {
                    $floor_area = $model_unit->floor_area.' sqm';
                }
                return $floor_area;
            })
            ->addColumn('project_name',function($model_unit){
                return Project::find($model_unit->project_id)->name;
            })
            ->addColumn('action', function ($model_unit)
            {
                $action = "";
                if(auth()->user()->can('view model unit'))
                {
                    $action .= '<a href="'.route("leads.show",["lead" => $model_unit->id]).'" class="btn btn-xs btn-success view-btn" id="'.$model_unit->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('edit model unit'))
                {
                    $action .= '<a href="'.route("leads.edit",["lead" => $model_unit->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$model_unit->id.'"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete model unit'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$model_unit->id.'" data-toggle="modal" data-target="#delete-lead-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','description'])
            ->make(true);
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
     * March 08, 2020
     * @author john kevin paunel
     * fetch all model units by project
     * @param string $project_id
     * @return object
     * */
    public function project_model_unit($project_id)
    {
        return ModelUnit::where('project_id',$project_id)->get();
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
        //
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
}
