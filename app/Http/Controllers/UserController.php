<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.users.index')->with([
            'roles' => Role::where('name','!=','super admin')->get()
        ]);
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
     * feb. 15, 2020
     * @author john kevin paunel
     * fetch user details
     * */
    public function userList()
    {
        $users = User::all();
        return DataTables::of($users)
            ->addColumn('fullname', function ($user){
                $fullname = ucfirst($user->firstname).' '.ucfirst($user->lastname);
                return $fullname;
            })
            ->addColumn('roles', function ($user){

                $roles = "";
                foreach ($user->getRoleNames() as $role)
                {
                    $roles .= '<span class="badge badge-info right role-badge">'.$role.'</span>';
                }
                return $roles;
            })
            ->addColumn('action', function ($user)
            {
                $action = "";
                if(auth()->user()->can('edit role'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$user->id.'" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete role'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$user->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['fullname','roles','action'])
            ->make(true);
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
            'firstname'     => 'required',
            'lastname'     => 'required',
            'username'     => 'required|unique:users,username',
            'password'      => 'required|confirmed',
            'role'          => 'required'
        ]);

        if($validator->passes())
        {
            $user = new User();

            $user->firstname = $request->firstname;
            $user->middlename = $request->middlename;
            $user->lastname = $request->lastname;
            $user->mobileNo = $request->mobileNo;
            $user->address = $request->address;
            $user->date_of_birth = $request->date_of_birth;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->save();

            $this->setRole($user,$request);

            return response()->json(['success' => true]);
        }

        return response()->json($validator->errors());
    }

    /**
     * Feb 15, 2020
     * @author john kevin paunel
     * set role
     * @param object $user
     * @param object $request
     * @return mixed
     * */
    protected function setRole($user, $request)
    {
        if($request->role !== null)
        {
            foreach ($request->role as $role)
            {
                $user->assignRole($role);
            }
        }

        return $this;
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
