<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\BuilderInterface;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\Repositories\RepositoryInterface\DhgClientProjectInterface;
use App\Repositories\RepositoryInterface\PaymentInterFace;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientProjectController extends Controller
{
    private $project, $client, $builder, $credential, $request, $payment;

    public function __construct(
        DhgClientProjectInterface $project,
        DhgClientInterFace $dhgClientInterFace,
        BuilderInterface $builder,
        CheckCredentialInterface $checkCredential,
        PaymentInterFace $paymentInterFace,
        Request $request
    ){

        $this->client = $dhgClientInterFace;
        $this->builder = $builder;
        $this->project = $project;
        $this->credential = $checkCredential;
        $this->request = $request;
        $this->payment = $paymentInterFace;
    }

    public function index()
    {
//        $builder = $this->project->viewAll();
//        foreach ($builder as $key => $value)
//        {
//            echo $value['builder']['name'];
//        }
//        //return $builder;
        # this variable retrieve all user whose role is NOT A CLIENT
        $agents = User::whereHas('roles', function ($query) {
            return $query->where('name','!=', 'client');
        })->get();
//        return $this->client->viewByRole('client');
//
        return view('pages.clientProjects.index')->with([
            'clients'    => $this->client->viewByRole('client'),
            'builders'   => $this->builder->viewAll(),
            'agents'     => $agents,
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
        $data = collect(['created_by' => auth()->user()->id]);
        $merged = $data->merge($request->all());
        return $this->project->create($merged->all());
    }

    public function show($id)
    {
        $project = $this->project->viewById($id);
        return view('pages.clientProjects.profile')->with([
            'project'    => $project,
            'project_code'      => $this->project->setCode($id),
            'payments'      => $this->payment->viewAll($id)
        ]);
    }

    public function edit($id)
    {
        return $this->project->viewById($id);
    }

    public function isAuthenticated()
    {
        return $this->credential->checkPassword(auth()->user()->username, $this->request->password);
    }

    /**
     * Dec. 14, 2020
     * @author john kevin paunel
     * update the client project model
     *
     * @param int $id
     * @return mixed
    */
    public function update($id)
    {
        if($this->isAuthenticated() === true)
        {
            return $this->project->updateById($this->request->all(),$id);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized access'],401);
    }

    public function destroy($id)
    {
        if($this->isAuthenticated() === true)
        {
            return $this->project->removeById($id);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized access', 'isAccess' => $this->request->password],401);
    }

    public function checkCredentialForDelete()
    {
        if($this->isAuthenticated() === true)
        {
            return response()->json(['success' => true, 'access' => $this->request->password],201);
        }
        return response()->json(['success' => false],401);
    }


    /**
     * Dec. 13, 2020
     * @author john kevin paunel
     * display all the DHG projects created
    */
    public function dhgProjectList()
    {
        $dhg_projects = $this->project->viewAll();
        //return $dhg_projects;
        return DataTables::of($dhg_projects)
            ->editColumn('id', function($dhg_project){
                return '<a href="#">'.$this->project->setCode($dhg_project['id']).'</a>';
                //return $dhg_project['id'];
            })
            ->editColumn('date_started', function($dhg_project){
                return $dhg_project['date_created'];
            })
            ->editColumn('user_id', function ($dhg_project){
                return $dhg_project['user']['firstname'].' '.$dhg_project['user']['lastname'];
            })
            ->editColumn('agent_id', function ($dhg_project){

                return "";
            })
            ->editColumn('builder_id', function ($dhg_project){
                return $dhg_project['builder']['name'];
            })
            ->addColumn('action', function ($dhg_project)
            {
                $action = "";
                if(auth()->user()->can('view dhg project'))
                {
                    $action .= '<a href="'.route("dhg.project.show",["project" => $dhg_project['id']]).'" class="btn btn-xs btn-success view-details" id="'.$dhg_project['id'].'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('edit dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn edit-project" id="'.$dhg_project['id'].'" data-toggle="modal" data-target="#edit-project-modal" title="Edit Project"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" id="'.$dhg_project['id'].'" title="Delete Project"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['id','user_id','action'])
            ->make(true);
    }

}
