<?php

namespace App\Http\Controllers;

use App\Permissions;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.permissions.index')->with([
            'roles'     => Role::where('name','!=','super admin')->get()
        ]);
    }

    /**
     * Feb. 14, 2020
     * @author john kevin paunel
     * display all the permissions
     * */
    public function permissions_list()
    {
        $permissions = Permissions::all();

        return DataTables::of($permissions)
            ->addColumn('role',function($permission){

                $role_permissions = Permission::whereName($permission->name)->first()->roles;
                $role = "";
                foreach ($role_permissions as $roles)
                {
                    $role .= '<span class="badge badge-info right role-badge">'.$roles->name.'</span>';
                }

                return $role;
            })
            ->addColumn('action', function ($permission)
            {
                $action = "";
                if(auth()->user()->can('edit permission'))
                {
                $action .= '<a href="#" class="btn btn-xs btn-primary edit-permission-btn" id="'.$permission->id.'" data-toggle="modal" data-target="#edit-permission-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete permission'))
                {
                $action .= '<a href="#" class="btn btn-xs btn-danger delete-permission-btn" id="'.$permission->id.'" data-toggle="modal" data-target="#delete-permission-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['role','action'])
            ->make(true);
    }

    /**
     * 02/15/2020
     * @author john kevin paunel
     * fetch all permission's roles
     * @param Request $request
     * @return object
     * */
    public function getPermissionRoles(Request $request)
    {
        $roles = Permission::whereName($request->name)->first()->roles->pluck('name');
        return $roles;
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
            'permission'    => 'required|unique:permissions,name',
        ]);

        if($validator->passes())
        {
            $permission = Permission::create(['name' => $request->permission]);
//            $permission->assignRole('super admin');
            if($request->roles !== null)
            {
                $permission->assignRole($request->roles);
            }

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
            'edit_permission'   => 'required'
        ],[
            'edit_permission.required'  => 'Permission name is required'
        ]);

        if($validator->passes())
        {


            $permission = Permission::findById($id);
            $permission->name = $request->edit_permission;
            $permission->save();

            $roles = Permission::whereName($permission->name)->first()->roles->pluck('name');
            foreach ($roles as $role)
            {
                $permission->removeRole($role);
            }

            $permission->assignRole($request->edit_roles);

            return response()->json(['success' => true]);
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
        return $id;
    }
}
