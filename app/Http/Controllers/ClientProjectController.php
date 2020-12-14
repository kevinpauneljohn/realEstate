<?php

namespace App\Http\Controllers;

use App\Builder;
use App\ClientProjects;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClientProjectController extends Controller
{
    public function index()
    {
        # this variable retrieve all user whose role is NOT A CLIENT
        $agents = User::whereHas('roles', function ($query) {
            return $query->where('name','!=', 'client');
        })->get();

        return view('pages.clientProjects.index')->with([
            'clients'    => User::role('client')->orderBy('firstname')->get(),
            'builders'   => Builder::all(),
            'agents'     => $agents,
            'architects'  => User::role('architect')->orderBy('firstname')->get()
        ]);
    }


    /**
     * Dec. 13, 2020
     * @author john kevin paunel
     * Store the Dream Home Guide Project in the Client Projects Table
     * @param Request $request
     * @return mixed
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'client'        => 'required',
            'agent'         => 'required',
            'address'       => 'required',
            'description'   => 'required'
        ]);

        if($validator->passes())
        {
            $project = new ClientProjects();
            $project->created_by = auth()->user()->id;
            $project->user_id = $request->client;
            $project->agent_id = $request->agent;
            $project->address = $request->address;
            $project->date_started = Carbon::now();
            $project->lot_price = $request->lot_price;
            $project->house_price = $request->house_price;
            $project->description = $request->description;
            $project->architect_id = $request->architect;
            $project->builder_id = $request->builder;
            $project->status = "pending";

            if($project->save()){
                return response()->json(['success' => true, 'message' => 'Project successfully added!']);
            }
        }
        return response()->json($validator->errors());
    }

    public function edit($id)
    {
        return ClientProjects::findOrFail($id);
    }

    /**
     * Dec. 14, 2020
     * @author john kevin paunel
     * update the client project model
     * @param Request $request
     * @param int $id
     * @return mixed
    */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'edit_client'        => 'required',
            'edit_agent'         => 'required',
            'edit_address'       => 'required',
            'edit_description'   => 'required'
        ],[
          'edit_client.required' => 'Client field is required',
          'edit_agent.required' => 'Agent field is required',
          'edit_address.required' => 'Address field is required',
          'edit_description.required' => 'Description field is required',
        ]);

        if($validator->passes())
        {
            $project = ClientProjects::findOrFail($id);
            $project->created_by = auth()->user()->id;
            $project->user_id = $request->edit_client;
            $project->agent_id = $request->edit_agent;
            $project->address = $request->edit_address;
            $project->lot_price = $request->edit_lot_price;
            $project->house_price = $request->edit_house_price;
            $project->description = $request->edit_description;
            $project->architect_id = $request->edit_architect;
            $project->builder_id = $request->edit_builder;

            if($project->isDirty())
            {
                $project->save();
                return response()->json(['success' => true, 'message' => 'Project successfully updated!']);
            }else{
                return response()->json(['success' => false,'change' => false, 'message' => 'No changes occurred!']);
            }
        }
        return response()->json($validator->errors());
    }


    /**
     * Dec. 13, 2020
     * @author john kevin paunel
     * display all the DHG projects created
    */
    public function dhgProjectList()
    {
        $dhg_projects = ClientProjects::all();
        return DataTables::of($dhg_projects)
            ->editColumn('id', function($dhg_project){

                $num_padded = sprintf("%05d", $dhg_project->id);
                return '<a href="#">dhg-'.$num_padded.'</a>';
            })
            ->editColumn('date_started', function($dhg_project){
                return $dhg_project->date_started->format('M d, Y');
            })
            ->editColumn('created_by', function ($dhg_project){
                return $dhg_project->creator->fullName;
            })
            ->editColumn('user_id', function ($dhg_project){
                return '<a href="#">'.$dhg_project->client->fullName.'</a>';
            })
            ->editColumn('agent_id', function ($dhg_project){
                $agent = $dhg_project->agent;
                return $agent ? $agent->fullName : "";
            })
            ->editColumn('architect_id', function ($dhg_project){
                $architect = $dhg_project->architect;
                return $architect ? $architect->fullName : '';
            })
            ->editColumn('builder_id', function ($dhg_project){
                $builder = $dhg_project->builder;
                return $builder ? $builder->name : '';
            })
            ->addColumn('action', function ($dhg_project)
            {
                $action = "";
                if(auth()->user()->can('view dhg project'))
                {
                    $action .= '<button class="btn btn-xs btn-info view-details" id="'.$dhg_project->id.'" data-toggle="modal" data-target="#lead-details" title="View Details"><i class="fa fa-info-circle"></i> </button>';
                }
                if(auth()->user()->can('edit dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$dhg_project->id.'" data-toggle="modal" data-target="#edit-project-modal" title="Edit Project"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" id="'.$dhg_project->id.'" title="Delete Project"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['id','user_id','action'])
            ->make(true);
    }
}
