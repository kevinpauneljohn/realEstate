<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
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
            ->addColumn('action', function ($project)
            {
                $action = "";
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
            ->rawColumns(['action'])
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
        //
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
