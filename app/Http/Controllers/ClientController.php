<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index()
    {
        return view('pages.clients.index');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'firstname'     => 'required',
            'lastname'      => 'required',
            'address'       => 'required',
            'username'      => 'required|unique:users,username',
            'password'      => 'required|confirmed'
        ]);

        if($validation->passes())
        {
            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->address = $request->address;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->assignRole('client');

            if($user->save())
            {
                $accessToken = User::find($user->id);
                $accessToken->api_token = $user->createToken('authToken')->accessToken;
                $accessToken->save();
                return response()->json(['success' => true,'message' => 'Client successfully added!']);
            }
        }
        return response()->json($validation->errors());
    }

    public function client_list()
    {
        $clients = User::role('client')->get();
        return DataTables::of($clients)
            ->addColumn('fullname',function($client){
                return $client->fullname;
            })
            ->addColumn('action', function ($client)
            {
                $action = "";
                if(auth()->user()->can('view client'))
                {
                    $action .= '<a href="'.route('client.show',['client' => $client->id]).'" class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-client-btn" id="'.$client->id.'" data-toggle="modal" data-target="#edit-client-modal"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete client'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$client->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i></a>';
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
