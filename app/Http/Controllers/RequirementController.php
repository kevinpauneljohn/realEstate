<?php

namespace App\Http\Controllers;

use App\Lead;
use App\ModelUnit;
use App\Project;
use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.requirements.index')->with([
            'projects'   => Project::all(),
        ]);
    }

    public function requirements_list()
    {
        $requirements = Requirement::all();
        return DataTables::of($requirements)
            ->editColumn('project_id',function($requirement){
                $project = json_decode($requirement->project_id);
                $projects = "";
                foreach ($project as $id)
                {

                    $projects .= '<span class="right badge badge-info project-badge">'.Project::find($id)->name.'</span>';
                }
                return $projects;
            })
            ->editColumn('description',function($requirement){
                $description = "<ul>";

                    foreach(json_decode($requirement->description) as $desc)
                    {
                        $description .= '<li>'.$desc.'</li>';
                    }
                    $description .= '</ul>';

                    return $description;
            })
            ->addColumn('action', function ($requirement)
            {
                $action = "";
                if(auth()->user()->can('view requirements'))
                {
                    $action .= '<button class="btn btn-xs btn-success view-sales-btn" id="'.$requirement->id.'" data-toggle="modal" data-target="#view-sales-details"><i class="fa fa-eye"></i> View</button>';
                }
                if(auth()->user()->can('edit requirements'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$requirement->id.'" data-toggle="modal" data-target="#edit-requirement-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete requirements'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-requirements-btn" id="'.$requirement->id.'" data-toggle="modal" data-target="#delete-requirements-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','project_id','description'])
            ->make(true);
    }

    public function getRequirements(Request $request)
    {
        $requirements = Requirement::findOrFail($request->id);
        $project = json_decode($requirements->project_id);
        $description = json_decode($requirements->description);
        return response()->json(['requirements' => $requirements, 'project' => $project, 'description' => $description]);
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
            'project'   => ['required'],
            'financing_type'    => ['required']
        ]);

        if($validator->passes())
        {
            $requirements = new Requirement();
            $requirements->title = $request->title;
            $requirements->project_id = json_encode($request->project);
            $requirements->description = json_encode($request->description);
            $requirements->type = $request->financing_type;
            if($requirements->save())
            {
                return response()->json(['success' => true]);
            }
        }
        return response()->json($validator->errors());
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
        $validator = Validator::make($request->all(),[
            'edit_project'   => ['required'],
            'edit_financing_type'    => ['required']
        ],[
           'edit_project.required' => 'Project field is required',
           'edit_financing_type.required' => 'Financing field is required',
        ]);

        if($validator->passes())
        {
            $requirements = Requirement::findOrFail($id);
            $requirements->title = $request->edit_title;
            $requirements->project_id = json_encode($request->edit_project);
            $requirements->description = json_encode($request->edit_description);
            $requirements->type = $request->edit_financing_type;
            if($requirements->save())
            {
                return response()->json(['success' => true]);
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
        $requirements = Requirement::findOrFail($id);
        if($requirements->delete())
        {
            return response()->json(['success' => true]);
        }
    }
}
