<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\Traits\Labeler;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    use Labeler;

    private $access_token,
            $client;


    public function __construct(
        AccessTokenClientInterface $accessTokenClient,
        DhgClientInterFace $dhgClientInterFace
    )
    {
        $this->access_token = $accessTokenClient;
        $this->client = $dhgClientInterFace;
    }

    public function index(Request $request)
    {
        return view('pages.clients.index');
    }

    public function store(Request $request)
    {
        return $this->client->create($request->all());
    }

    public function client_list()
    {
        $clients = $this->client->viewAll();
        return DataTables::of($clients)
            ->addColumn('fullname',function($client){

                $fullname = ucfirst($client['firstname']).' '.ucfirst($client['lastname']);
                return $fullname;
            })
            ->editColumn('roles',function($client){
                return $this->roleColor($client['roles'][0]['name']);
            })
            ->addColumn('action', function ($client)
            {
                $collection = collect($client)->toArray();
                $action = "";
                if(auth()->user()->can('view client'))
                {
                    $action .= '<a href="'.route('client.show',['client' => $collection['id']]).'" class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-client-btn" id="'.$collection['id'].'" data-toggle="modal" data-target="#edit-client-modal"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete client'))
                {
                    $action .= '<button type="button" value="delete-client" class="btn btn-xs btn-danger delete-client-btn" id="'.$collection['id'].'" onclick="showSignInPassword(this.id,this.value)"><i class="fa fa-trash"></i></button>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<button type="button" value="edit-role" class="btn btn-xs bg-purple edit-role-btn" id="'.$collection['id'].'" onclick="showSignInPassword(this.id,this.value)"><i class="fa fa-user-edit"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['roles','action'])
            ->make(true);
    }


    public function show($id)
    {
        $client = collect($this->client->viewById($id))->toArray();

        return view('pages.clients.profile')->with([
            'client'    => $client
        ]);
    }


    /**
     * September 22, 2020
     * @author john kevin paunel
     * get the client data by ID once the button was clicked on the table
     * @param string $id
     * @return object
     * */
    public function edit($id)
    {
        return $this->client->viewById($id);
    }

    /**
     * September 23, 2020
     * @author john kevin paunel
     * Update the client details
     * @param Request $request
     * @param string $id
     * @return mixed
     * */
    public function update(Request $request,$id)
    {
        return $this->client->updateById($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->client->removeById($id);
    }

    public function updateRole(Request $request,$id)
    {
        return $this->client->updateRoleById($request->all(),$id);
    }
}
