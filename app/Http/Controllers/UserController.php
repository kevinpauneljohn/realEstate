<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Downline;
use App\Events\CreateNetworkEvent;
use App\Events\SendMoneyEvent;
use App\Events\UserRankPointsEvent;
use App\Events\UserRequestEvent;
use App\Http\Middleware\checkUserAuth;
use App\Lead;
use App\ModelUnit;
use App\Permission;
use App\Project;
use App\Repositories\ThresholdRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Role;
use App\Rules\checkIfPasswordMatch;
use App\Sales;
use App\Services\AccountManagerService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;
use App\Events\UserCommissionRequestEvent;

class UserController extends Controller
{
    public $thresholdRepository,
        $userRepository,
        $walletRepository,
        $accountmanagement;

    public function __construct(ThresholdRepository $thresholdRepository,
                                UserRepository $userRepository,
                                WalletRepository $walletRepository,
                                AccountManagerService $accountManagerService)
    {
        $this->thresholdRepository = $thresholdRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
        $this->accountmanagement = $accountManagerService;
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /*this will set the allowed role depending on the position the user has*/
        if(auth()->user()->hasRole('super admin'))
        {
            $roles = Role::where('name','!=','super admin')->get();
        }elseif (auth()->user()->hasRole(['manager','admin'])){
            $roles = Role::where([
                ['name','!=','super admin'],
                ['name','!=','admin'],
                ['name','!=','manager'],
                ['name','!=','client'],
                ['name','!=','builder member'],
                ['name','!=','builder admin'],
                ['name','!=','architect'],
                ['name','!=','account manager'],
                ['name','!=','online warrior'],
            ])->get();
        }elseif (auth()->user()->hasRole('agent')){
            $roles = Role::where([
                ['name','!=','super admin'],
                ['name','!=','admin'],
                ['name','!=','manager'],
//                ['name','!=','agent'],
                ['name','!=','client'],
                ['name','!=','builder member'],
                ['name','!=','builder admin'],
                ['name','!=','architect'],
                ['name','!=','account manager'],
                ['name','!=','team leader'],
                ['name','!=','online warrior'],
            ])->get();
        }elseif (auth()->user()->hasRole('team leader')){
            $roles = Role::where([
                ['name','!=','super admin'],
                ['name','!=','admin'],
                ['name','!=','manager'],
                ['name','!=','team leader'],
                ['name','!=','client'],
                ['name','!=','builder member'],
                ['name','!=','builder admin'],
                ['name','!=','architect'],
                ['name','!=','account manager'],
                ['name','!=','online warrior'],
            ])->get();
        }
        return view('pages.users.index')->with([
            'roles' => auth()->user()->hasRole('account manager')? "" : $roles,
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
            ->addColumn('permissions',function($user){
                return 'permissions';
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
                if(auth()->user()->can('change password'))
                {
                    $action .= '<a href="#" class="btn btn-xs bg-warning change-password-btn" id="'.$user->id.'" data-toggle="modal" data-target="#change-password-modal"><i class="fa fa-key"></i></a>';
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

            if($this->checkIfBuilder($request) === true)
            {
                return $this->saveBuilder($user);
            }elseif ($this->checkIfClient($request) === true){

            }else{
                return $this->agentFunction($user, $request);
            }
        }
        return response()->json($validator->errors());
    }

    //this will do another action if the user was detected as agent
    private function agentFunction($user, $request)
    {
        if(auth()->user()->hasRole('super admin'))
        {
            ///save directly to users table if the user is a super admin
            $user->save();

            //assign role to a user
            $this->setRole($user,$request);

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

    //this will check if the user role is builder
    private function checkIfBuilder($request)
    {
        if($this->checkRole($request) === true)
        {

            foreach ($request->role as $role)
            {
                if($role === 'builder admin' || $role === 'builder member')
                {
                    return true;
                }
            }
        }
        return false;
    }

    //this will check if the user role is builder
    private function checkIfClient($request)
    {
        if($this->checkRole($request) === true)
        {

            foreach ($request->role as $role)
            {
                if($role === 'client')
                {
                    return true;
                }
            }
        }
        return false;
    }

    ///save the client to dhg.dream-homeseller.com
    private function saveClient($client)
    {

    }

    ///save the user if its a builder
    private function saveBuilder($user)
    {
        $user->save();
        return response()->json(['success' => true]);
    }

    ///this will check if the role was not empty
    private function checkRole($request)
    {
        if($request->role !== null)
        {
            return true;
        }
        return false;
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
        if($this->checkRole($request) === true)
        {
            foreach ($request->role as $role)
            {
                $user->assignRole($role);
            }
        }
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
//        return Sales::whereIn('lead_id',collect(Lead::where('online_warrior_id',$id)->get())->pluck('id'))->count();
        $user = User::findOrFail($id);
        $onlineWarriorLeads = Lead::where('online_warrior_id',$id);

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
            'onlineWarrior' => $onlineWarriorLeads,
            'onlineWarriorSales' => Sales::whereIn('lead_id',collect($onlineWarriorLeads->get())->pluck('id')),
            'activities' => Activity::where('causer_id',$id),
            'permissions' => Permission::all(),
        ]);
    }

    public function employee($userId)
    {
        return $this->userRepository->onlineWarriorActivities($userId);
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

    public function userChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'change_password'  => ['required','confirmed'],
        ]);

        if($validator->passes())
        {
            $user = User::find($request->input('userId'));
            $user->password = bcrypt($request->input('change_password'));
            if($user->save())
            {
                return response(['success' => true, 'message' => 'Password has changed!']);
            }
            return response(['success' => false, 'message' => 'An error occurred!']);
        }
        return response($validator->errors());
    }

    public function deleteCommission(Request $request)
    {
        $commission = Commission::find($request->id);
        if(auth()->user()->hasRole('super admin'))
        {
            if($this->deleteUserCommission($request->id))
            {
                return response(['success' => true, 'message' => 'Commission successfully deleted!']);
            }
        } else {
            $get_commission = $this->getUserCommission($request->commission_id);
            $get_request =[
                'commission_id' => $request->commission_id,
                '_token' => $request->_token,
                'user_id' => $request->user_id,
                'project' => $get_commission['project_id'],
                'commission_rate' => $get_commission['commission_rate'],
                'project_name' => $this->getProject($get_commission['project_id']),
                'reason' => $request->commission_remark,
                'action' => 'delete'
            ];

            $result = event(new UserCommissionRequestEvent($get_request));
            return response()->json(['success' => true,'message' => 'Delete User Commission successfully submitted<br/><strong>Please wait for the admin approval</strong>']);
        }
        return response(['success' => false, 'message' => 'You are not allowed to delete this Commission!']);
    }

    public function deleteUserCommission($id): bool
    {
        $commission = Commission::where('id','=',$id);
        if($commission->count() > 0)
        {
            return $commission->delete();
        }
        return false;
    }

    public function getProject($id)
    {
        $project = Project::where('id', $id)->first();

        $project_name = 'No Project Selected';
        if (!empty($project)) {
            $project_name = $project->name;
        }
        return $project_name;
    }

    public function getUserCommission($id)
    {
        $commission = Commission::where('id', $id)->first();

        $data = [
            'project_id' => $commission->project_id,
            'commission_rate' => $commission->commission_rate
        ];

        return $data;
    }

    public function assignPermissionToUser(Request $request): bool
    {
        $request->validate([
            'permissions' => 'required'
        ]);
        return $this->userRepository->assignPermission($request->userId, $request->permissions);
    }

    public function userPermissions($userId)
    {
        return $this->userRepository->getUserPermissions($userId);
    }

    public function removePermission($userId, $permissionId): \Illuminate\Http\JsonResponse
    {
        $permission = Permission::findOrFail($permissionId);
        return $this->userRepository->removeUserPermission($userId, $permission->name) ?
        response()->json(['success' => true, 'message' => 'Permission successfully removed']) :
            response()->json(['success' => false, 'message' => 'An error occurred']);
    }
}
