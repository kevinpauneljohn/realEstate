<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.projects.index');
    }

    /**
     * Feb. 27, 2020
     * @author john kevin paunel
     * fetch all projects
     * */
    public function project_list()
    {
        $projects = Project::all();
        return DataTables::of($projects)
            ->addColumn('model_units', function (){
                return 'model units';
            })
            ->addColumn('action', function ($project)
            {
                $action = "";
                if(auth()->user()->can('view project'))
                {
                    $action .= '<a href="'.route('projects.profile',['project' => $project->id]).'" class="btn btn-xs btn-success view-project-btn" id="'.$project->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('edit project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-project-btn" id="'.$project->id.'" data-toggle="modal" data-target="#edit-project-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-project-btn" id="'.$project->id.'" data-toggle="modal" data-target="#delete-project-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['model_units','action'])
            ->make(true);
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
        $validator = Validator::make($request->all(), [
            'name'      => 'required|unique:projects,name',
            'address'   => 'required'
        ],[
            'name.required' => 'Project name is required'
        ]);

        if($validator->passes())
        {
            $project = new Project();
            $project->name = $request->name;
            $project->address = $request->address;
            $project->remarks = $request->remarks;

            if($project->save())
            {
                return response()->json(['success' => true]);
            }
        }
        return response()->json($validator->errors());
    }

    /**
     * March 01, 2020
     * @author john kevin paunel
     * view project profile
     * @param string $id
     * @return mixed
     * */
    public function profile($id)
    {
        return view('pages.projects.profile')->with([
            'project'  => Project::findOrFail($id)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return $project;
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
        $validator = Validator::make($request->all(), [
            'edit_name'      => 'required',
            'edit_address'   => 'required'
        ],[
            'edit_name.required' => 'Project name is required'
        ]);

        if($validator->passes())
        {
            $project = Project::findOrFail($id);
            $project->name = $request->edit_name;
            $project->address = $request->edit_address;
            $project->remarks = $request->edit_remarks;

            if($project->save())
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
        $project = Project::findOrFail($id);

        if($project->delete())
        {
            return response()->json(['success' => true]);
        }
    }
}
