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
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$client->id.'" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-edit"></i></a>';
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

}
