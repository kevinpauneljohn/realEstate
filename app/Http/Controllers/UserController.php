<?php

namespace App\Http\Controllers;

use App\Downline;
use App\Events\CreateNetworkEvent;
use App\Events\SendMoneyEvent;
use App\Events\UserRankPointsEvent;
use App\Events\UserRequestEvent;
use App\Http\Middleware\checkUserAuth;
use App\Lead;
use App\ModelUnit;
use App\Project;
use App\Repositories\ThresholdRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Role;
use App\Rules\checkIfPasswordMatch;
use App\Sales;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public $thresholdRepository, $userRepository, $walletRepository;

    public function __construct(ThresholdRepository $thresholdRepository,
                                UserRepository $userRepository,
                                WalletRepository $walletRepository)
    {
        $this->thresholdRepository = $thresholdRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
    }

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
        }elseif (auth()->user()->hasRole('team leader')){
            $roles = Role::where([
                ['name','!=','super admin'],
                ['name','!=','admin'],
                ['name','!=','manager'],
                ['name','!=','team leader'],
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
                return $lead->fullname;
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
                return $user->fullname;
            })
            ->addColumn('upline', function ($user){
                if($user->upline_id != null)
                {
                    $upline = User::find($user->upline_id);
                    return $upline->fullname;
                }
                return "";
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
                    $action .= '<a href="'.route('users.profile',['user' => $user->id]).'" class="btn btn-xs btn-success edit-user-btn"><i class="fa fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit user'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$user->id.'" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete user'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$user->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['fullname','roles','action'])
            ->make(true);
    }

    public function downLines($user_id)
    {
        $downLines = User::where('upline_id',$user_id)->get();

        return DataTables::of($downLines)
            ->addColumn('fullname', function ($downLine){
                $profile = '<a href="'.route('users.profile',['user' => $downLine->id]).'">'.$downLine->fullname.'</a>';
                return $profile;
            })
            ->addColumn('roles', function ($downLine){

                $roles = "";
                foreach ($downLine->getRoleNames() as $role)
                {
                    $roles .= '<span class="badge badge-info right role-badge">'.$role.'</span>';
                }
                return $roles;
            })
            ->rawColumns(['fullname','roles'])
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

            if(auth()->user()->hasRole('super admin'))
            {
                ///save directly to users table if the user is a super admin
                $user->save();

                //assign role to a user
                $this->setRole($user,$request);

                //connect the user to its up line
                //event(new CreateNetworkEvent($user->id));

                //set the user rank based on his points
                event(new UserRankPointsEvent($user,0,0));

                //send initial 500 dream coins on the user's dream wallet
                $this->walletRepository->setMoney(
                    $user->id,
                    User::where('username','kevinpauneljohn')->first()->id,
                    500,'Initial incentives can be cashed out if there is a reservation',
                    true,false,'incentive','for-approval'
                );

                return response()->json(['success' => true]);
            }else{

                //save the user request to threshold for approval
                $result = event(new UserRequestEvent($request));
                return response()->json(['success' => true,'message' => 'User Create successfully submitted<br/><strong>Please wait for the admin approval</strong>']);
            }
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

//    /**
//     * March 21, 2020
//     * @author john kevin paunel
//     * set user to downlines table
//     * @param string $user
//     * @return mixed
//     * */
//    public function setDownline($user)
//    {
//        $downline = new Downline();
//        $downline->user_id = auth()->user()->id;
//        $downline->downline_id = $user->id;
//        $downline->save();
//
//        return $this;
//    }

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

        $rate_limit = 4;/* 4% is the user's max commission rate which is only the super admin can give*/
        /*if the use is not a super admin the commission rate that can be given will be based on the up line max rate*/
        if(!User::find($user->upline_id)->hasRole('super admin'))
        {
            /*this will check if the up line has already commission rate set on the system*/
            $rate_limit = User::findOrFail($user->upline_id)->commissions()->first();

            if($rate_limit !== null)
            {
                /*if the up line commission rate is not null the rate given for the down line is one step lower*/
                $rate_limit = $rate_limit->commission_rate-1;
            }
        }

        $user = User::findOrFail($id);
        return view('pages.users.profile')->with([
            'user'  => $user,
            'upline' => User::findOrFail($user->upline_id),
            'rate_limit' => $rate_limit,
            'projects'  => Project::all(),
            'total_leads' => Lead::where('user_id',$id)->count(),
            'cold_leads' => Lead::where([['user_id','=',$id],['lead_status','=','Cold']])->count(),
            'tripping_leads' => Lead::where([['user_id','=',$id],['lead_status','=','For tripping']])->count(),
            'reserved_leads' => Lead::where([['user_id','=',$id],['lead_status','=','Reserved']])->count(),
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
            $user = User::find(auth()->user()->id);
            $user->password = bcrypt($request->password);
            if($user->save())
            {
                return response()->json(['success' => true]);
            }
        }
        return response()->json($validator->errors());
    }
}
