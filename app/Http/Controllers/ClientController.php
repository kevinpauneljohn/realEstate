<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\Traits\Labeler;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;

class ClientController extends Controller
{
    use Labeler;

    private $access_token,
            $client,
            $request,
            $credential;


    /**
     * ClientController constructor.
     * @param AccessTokenClientInterface $accessTokenClient
     * @param DhgClientInterFace $dhgClientInterFace
     * @param CheckCredentialInterface $checkCredential
     * @param $request
     *
     */
    public function __construct(
        AccessTokenClientInterface $accessTokenClient,
        DhgClientInterFace $dhgClientInterFace,
        CheckCredentialInterface $checkCredential,Request $request
    )
    {
        $this->access_token = $accessTokenClient;
        $this->client = $dhgClientInterFace;
        $this->request = $request;
        $this->credential = $checkCredential;
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
//        return $clients;
        return DataTables::of($clients)
            ->addColumn('fullname',function($client){

                $fullname = ucfirst($client['firstname']).' '.ucfirst($client['lastname']);
                return $fullname;
            })
            ->editColumn('roles',function($client){
                if(isset($client['roles'][0]))
                {
                    return $this->roleColor($client['roles'][0]['name']);
                }
                return "";
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
                    $action .= '<button type="button" value="delete-client" class="btn btn-xs btn-danger delete-client-btn" id="'.$collection['id'].'"><i class="fa fa-trash"></i></button>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<button type="button" value="edit-role" class="btn btn-xs bg-purple edit-role-btn" id="'.$collection['id'].'" data-toggle="modal" data-target="#edit-role-modal"><i class="fa fa-user-edit"></i></button>';
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

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        if($this->isAuthenticated() === true)
        {
            return $this->client->removeById($id);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized access', 'isAccess' => $this->request->password],401);
    }

    public function updateRole(Request $request,$id)
    {
        if($this->isAuthenticated() === true)
        {
            return $this->client->updateRoleById($request->all(),$id);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized access'],401);
    }


    /**
     * @return mixed
     */
    public function isAuthenticated()
    {
        return $this->credential->checkPassword(auth()->user()->username, $this->request->password);
    }

}
