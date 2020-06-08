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
            'model_name'   => 'required',
            'house_type'      => 'required',
            'floor_level'      => 'required',
            'lot_area'      => 'required',
            'floor_area'      => 'required',
            'photo_url'      => 'url',
            'remarks'      => 'max:8000',
        ]);

        if($validator->passes())
        {
            $project = new ModelUnit();
            $project->project_id = $request->project_id;
            $project->user_id = auth()->user()->id;
            $project->name = $request->model_name;
            $project->house_type = $request->house_type;
            $project->floor_level = $request->floor_level;
            $project->lot_area = $request->lot_area;
            $project->floor_area = $request->floor_area;
            $project->description = array(
                'description' => $request->remarks,
                'photo_url' => $request->photo_url,
            );

            if($project->save())
            {
                return response()->json(['success' => true,'message' => 'Model Unit successfully added']);
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

    public function project_model_unit_list($project_id)
    {
        $model_units = ModelUnit::where('project_id',$project_id)->get();
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
            ->addColumn('action', function ($model_unit)
            {
                $action = "";
                if(auth()->user()->can('view model unit'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-success view-btn" id="'.$model_unit->id.'" data-toggle="modal" data-target="#view-model-unit-modal"><i class="fa fa-eye"></i> </button>';
                }
                if(auth()->user()->can('edit model unit'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-primary update-btn" id="'.$model_unit->id.'" data-toggle="modal" data-target="#edit-model-modal"><i class="fa fa-edit"></i> </button>';
                }
                if(auth()->user()->can('delete model unit'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-danger delete-btn" id="'.$model_unit->id.'"><i class="fa fa-trash"></i> </button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * @since June 09, 2020
     * @author john kevin paunel
     * get all the model unit details
     * @param int $id
     * @return object
     * */
    public function getModelUnitDetails($id)
    {
        $modelUnit = ModelUnit::find($id);
        return $modelUnit;
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
        $validator = Validator::make($request->all(),[
            'edit_model_name'   => 'required',
            'edit_house_type'      => 'required',
            'edit_floor_level'      => 'required',
            'edit_lot_area'      => 'required',
            'edit_floor_area'      => 'required',
            'edit_photo_url'      => 'url',
            'edit_remarks'      => 'max:8000',
        ],[
            'edit_model_name.required'   => 'Model name is required',
            'edit_house_type.required'      => 'House type is required',
            'edit_floor_level.required'      => 'Floor level is required',
            'edit_lot_area.required'      => 'Lot area is required',
            'edit_floor_area.required'      => 'Floor area is required',
            'edit_photo_url.url'      => 'Must be a valid URL',
            'edit_remarks.max'      => 'Maximum of 8000 characters only',
        ]);

        if($validator->passes())
        {
            $project = ModelUnit::find($id);
            $project->project_id = $request->edit_project_id;
            $project->user_id = auth()->user()->id;
            $project->name = $request->edit_model_name;
            $project->house_type = $request->edit_house_type;
            $project->floor_level = $request->edit_floor_level;
            $project->lot_area = $request->edit_lot_area;
            $project->floor_area = $request->edit_floor_area;
            $project->description = array(
                'description' => $request->edit_remarks,
                'photo_url' => $request->edit_photo_url,
            );

            if($project->isDirty())
            {
                $project->save();
                return response()->json(['success' => true,'message' => 'Model Unit successfully added']);
            }else{
                return response()->json(['success' => false,'message' => 'No changes occurred']);
            }

        }
        return response()->json($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modeUnit = ModelUnit::find($id);
        $modeUnit->delete();
        return response()->json(['success' => true,'message' => 'Model Unit successfully deleted']);
    }
}
