<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.roles.index');
    }

    public function roles_list()
    {
        $roles = Role::where('name','!=','super admin')->get();
        return DataTables::of($roles)
            ->addColumn('action', function ($role)
            {
                $action = "";
//                if(auth()->user()->hasPermissionTo('edit role'))
//                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-role-btn" id="'.$role->id.'" data-toggle="modal" data-target="#edit-role-modal"><i class="fa fa-edit"></i> Edit</a>';
//                }
//                if(auth()->user()->hasPermissionTo('delete role'))
//                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-role-btn" id="'.$role->id.'" data-toggle="modal" data-target="#delete-role-modal"><i class="fa fa-trash"></i> Delete</a>';
//                }
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
        $validator = Validator::make($request->all(), [
            'role'      => ['required','unique:roles,name']
        ]);

        if($validator->passes())
        {
            \Spatie\Permission\Models\Role::create(['name' => $request->role]);

            return response()->json(['success' => true]);
        }

        return response()->json($validator->errors());
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
        $validator = Validator::make($request->all(),[
            'edit_role'     => ['required','unique:roles,name']
        ],[
            'edit_role.required'  => 'Role name is required',
            'edit_role.unique'  => 'Role name was already taken'
        ]);

        if($validator->passes())
        {
            $role = Role::findOrFail($id);
            $role->name = $request->edit_role;

            if($role->save())
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
        //
    }
}
