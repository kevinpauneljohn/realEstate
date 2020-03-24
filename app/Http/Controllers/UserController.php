<?php

namespace App\Http\Controllers;

use App\Downline;
use App\Events\CreateNetworkEvent;
use App\Lead;
use App\ModelUnit;
use App\Project;
use App\Role;
use App\Rules\checkIfPasswordMatch;
use App\Sales;
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
        /*this will set the allowed role depending on the position the user has*/
        if(auth()->user()->hasRole('super admin'))
        {
            $roles = Role::where('name','!=','super admin')->get();
        }elseif (auth()->user()->hasRole('manager')){
            $roles = Role::where([
                ['name','!=','super admin'],
                ['name','!=','admin'],
                ['name','!=','manager'],
            ])->get();
        }elseif (auth()->user()->hasRole('agent')){
            $roles = Role::where([
                ['name','!=','super admin'],
                ['name','!=','admin'],
                ['name','!=','manager'],
                ['name','!=','agent'],
            ])->get();
        }
        return view('pages.users.index')->with([
            'roles' => $roles,
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
     * March 11, 2020
     * @author john kevin paunel
     * fetch all user sales
     * */
    public function user_sales_list($id)
    {
        $sales = Sales::where('user_id',$id)->get();
        return DataTables::of($sales)
            ->editColumn('total_contract_price',function($sale){
                return number_format($sale->total_contract_price);
            })
            ->editColumn('discount',function($sale){
                return number_format($sale->discount);
            })
            ->addColumn('full_name',function($sale){
                $lead = Lead::find($sale->lead_id);
                $firstname = $lead->firstname;
                $lastname = $lead->lastname;

                return ucfirst($firstname).' '.ucfirst($lastname);
            })
            ->addColumn('project',function($sale){
                $project = Project::find($sale->project_id);

                return $project->name;
            })
            ->addColumn('model_unit',function($sale){
                $modelUnit = ModelUnit::find($sale->model_unit_id);

                return $modelUnit->name;
            })
            ->addColumn('status',function($sale){

                return '';
            })
            ->addColumn('action', function ($sale)
            {
                $action = "";
                if(auth()->user()->can('view lead'))
                {
                    $action .= '<a href="'.route("leads.show",["lead" => $sale->id]).'" class="btn btn-xs btn-success view-btn" id="'.$sale->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('edit lead'))
                {
                    $action .= '<a href="'.route("leads.edit",["lead" => $sale->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$sale->id.'"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$sale->id.'" data-toggle="modal" data-target="#delete-lead-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * feb. 15, 2020
     * @author john kevin paunel
     * fetch user details
     * */
    public function userList()
    {
        if(auth()->user()->hasRole('super admin'))
        {
            $users = User::where('username','!=','kevinpauneljohn')->get();
        }else{
            $users = User::where('upline_id',auth()->user()->id)->get();
        }

        return DataTables::of($users)
            ->addColumn('fullname', function ($user){
                $fullname = ucfirst($user->firstname).' '.ucfirst($user->lastname);
                return $fullname;
            })
            ->addColumn('upline', function ($user){
                $upline = User::find($user->upline_id);
                return ucfirst($upline->firstname).' '.ucfirst($upline->lastname);
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
                if(auth()->user()->can('view user'))
                {
                    $action .= '<a href="'.route('users.profile',['user' => $user->id]).'" class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('edit user'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$user->id.'" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete user'))
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

            $user->upline_id = auth()->user()->id;
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

            $this->setRole($user,$request)->setDownline($user);

            event(new CreateNetworkEvent($user->id));

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
     * March 21, 2020
     * @author john kevin paunel
     * set user to downlines table
     * @param string $user
     * @return mixed
     * */
    public function setDownline($user)
    {
        $downline = new Downline();
        $downline->user_id = auth()->user()->id;
        $downline->downline_id = $user->id;
        $downline->save();

        return $this;
    }

    /**
     * March 09, 2020
     * @author john kevin paunel
     * view user profile
     * @param string $id
     * @return mixed
     * */
    public function profile($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.profile')->with([
            'user'  => $user,
            'upline' => User::findOrFail($user->upline_id)
        ]);
    }
    /**
     * March 11, 2020
     * @author john kevin paunel
     * get all user agents
     * @param string $id
     * @return mixed
     * */
    public function agents($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.agents')->with([
            'user'  => $user,
            'upline' => User::findOrFail($user->upline_id)
        ]);
    }
    /**
     * March 11, 2020
     * @author john kevin paunel
     * view user commission rate
     * @param string $id
     * @return mixed
     * */
    public function commissions($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.commissions')->with([
            'user'  => $user,
            'rate_limit' => Project::all()->max('commission_rate'),
            'upline' => User::findOrFail($user->upline_id)
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user, 'roles' => $user->getRoleNames()]);
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
        $validator = Validator::make($request->all(), [
            'edit_firstname'    => 'required',
            'edit_lastname'     => 'required',
            'edit_role'         => 'required'
        ],[
            'edit_firstname.required'   => 'First Name is required',
            'edit_lastname.required'   => 'Last Name is required',
            'edit_role.required'   => 'Role is required',
        ]);

        if($validator->passes())
        {
            $user = User::findOrFail($id);
            $user->firstname = $request->edit_firstname;
            $user->middlename = $request->edit_middlename;
            $user->lastname = $request->edit_lastname;
            $user->mobileNo = $request->edit_mobileNo;
            $user->address = $request->edit_address;
            $user->date_of_birth = $request->edit_date_of_birth;
            $user->email = $request->edit_email;
            $user->save();

            if($request->edit_role !== null)
            {
                $user->syncRoles($request->edit_role);
            }
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
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true]);
    }

    /**
     * March 25, 2020
     * @author john kevin paunel
     * Change current user password
     * */
    public function changePassword()
    {
        return view('pages.users.password');
    }

    /**
     * March 25, 2020
     * @author john kevin paunel
     * Update the user password
     * @param Request $request
     * @return mixed
     * */
    public function changePasswordValidate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'  => ['required',new checkIfPasswordMatch()],
            'password'  => ['required','confirmed']
        ]);

        if($validator->passes())
        {
            $user = User::find($request->user_id);
            $user->password = bcrypt($request->password);
            if($user->save())
            {
                return response()->json(['success' => true]);
            }
        }
        return response()->json($validator->errors());
    }
}
