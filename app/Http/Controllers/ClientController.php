<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use App\Repositories\RepositoryInterface\DhgClientInterFace;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
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
        //$clients = User::role('client')->get();
        $clients = $this->client->view();
        return DataTables::of($clients)
            ->addColumn('fullname',function($client){
                $collection = collect($client)->toArray();
                $fullname = ucfirst($collection['firstname']).' '.ucfirst($collection['lastname']);
                return $fullname;
            })
            ->addColumn('action', function ($client)
            {
                $collection = collect($client)->toArray();
                $action = "";
                if(auth()->user()->can('view client'))
                {
                    $action .= '<a  class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-client-btn" id="'.$collection['id'].'" data-toggle="modal" data-target="#edit-client-modal"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$collection['id'].'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function show($id)
    {
        return view('pages.clients.profile')->with([
            'client'    => User::find($id)
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
        $client = User::find($id);
        return $client;
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
        $validator = Validator::make($request->all(),[
            'edit_firstname'    => 'required',
            'edit_lastname'     => 'required',
            'edit_address'      => 'required',
        ],[
           'edit_firstname' => 'First name is required',
           'edit_lasstname' => 'Last name is required',
           'edit_address'   => 'Address is required',
        ]);

        if($validator->passes())
        {
            $client = User::find($id);
            $client->firstname = $request->edit_firstname;
            $client->middlename = $request->edit_middlename;
            $client->lastname = $request->edit_lastname;
            $client->address = $request->edit_address;

            if($client->isDirty())
            {
                $client->save();
                return response()->json(['success' => true,'message' => 'Client details successfully updated!']);
            }
            return response()->json(['success' => false,'message' => 'No changes occurred!']);
        }
        return response()->json($validator->errors());
    }
}
