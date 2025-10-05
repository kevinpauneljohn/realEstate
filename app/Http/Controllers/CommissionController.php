<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Events\UserCommissionRequestEvent;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('pages.commissions.index')->with([
            'user'  => User::findOrFail($id)
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'commission_rate'   => 'required',
        ],[
            'commission_rate.required'  => 'Commission Rate is required'
        ]);

        if($validator->passes())
        {
            $commission = new Commission();
            $commission->user_id = $request->user_id;
            $commission->commission_rate = $request->commission_rate;
            $commission->project_id = $request->project;

            if($commission->save())
            {
                return response()->json(['success' => true, 'message' => 'Commission successfully saved']);
            }
        }
        return response()->json($validator->errors());
    }

    /**
     * March 12, 2020
     * @author john kevin paunel
     * @param string $id
     * @return mixed
     * */
    public function commission_list($id)
    {
        $commissions = Commission::where('user_id',$id)->get();
        return DataTables::of($commissions)
            ->editColumn('created_at',function($commission){
                return $commission->created_at->format('M d, Y');
            })
            ->editColumn('commission_rate',function($commission){
                $rate = "";
                if($commission->commission_rate != null)
                {
                    $rate = $commission->commission_rate.'%';
                }
                return $rate;
            })
            ->editColumn('project_id',function($commission){
                if($commission->project_id != null)
                {
                    return $commission->project->name;
                }
            })
            ->addColumn('action', function ($commission)
            {
                $action = "";
                if(auth()->user()->can('edit commissions'))
                {
                    $action .= '<a href="#" data-rate="'.$commission->commission_rate.'" class="btn btn-xs btn-primary edit-commission-btn" id="'.$commission->id.'" data-toggle="modal" data-target="#edit-commission-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete commissions'))
                {
                    if (auth()->user()->hasRole(["super admin"])) {
                        $action .= '<a href="#" class="btn btn-xs btn-danger delete-commission-btn-admin" id="'.$commission->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i> Delete</a>';
                    } else {
                        $action .= '<a href="#" class="btn btn-xs btn-danger delete-commission-btn" id="'.$commission->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i> Delete</a>';
                    }
                }
                return $action;
            })
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commissions = Commission::where('id',$id)->first();
        $data = [
            'id' => $commissions->id,
            'rate' => intval($commissions->commission_rate*100.0)/100,
            'project_id' => $commissions->project_id
        ];

        return $data;
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
    public function getCommission($id)
    {
        return Commission::findOrFail($id);
    }

    public function updates($id, array $data)
    {
        $commission = $this->getCommission($id);
        $commission->fill($data);
        if($commission->isDirty())
        {
            $commission->forceFill(array('project_id' => $data['project_id']));
            $commission->save();
            return $commission;
        }
        return false;

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'commission_rate'   => 'required',
        ],[
            'commission_rate.required'  => 'Commission Rate is required'
        ]);

        if($validator->passes())
        {
            $data = [
                'commission_rate' => $request->input('commission_rate'),
                'project_id' => $request->project,
            ];

            if(auth()->user()->hasRole('super admin'))
            {
                if ($commissions = $this->updates($id, $data)) {
                    return response(['success' => true, 'message' => 'Commission successfully updated!', $commissions]);
                }
            } else {
                $get_request =[
                    'commission_id' => $request->id,
                    '_token' => $request->_token,
                    'user_id' => $request->user_id,
                    'project' => $request->project,
                    'commission_rate' => $request->commission_rate,
                    'project_name' => $this->getProject($request->project),
                    'reason' => $request->commission_remark,
                    'action' => 'update'
                ];
                $result = event(new UserCommissionRequestEvent($get_request));
                return response()->json(['success' => true,'message' => 'Update User Commission successfully submitted<br/><strong>Please wait for the admin approval</strong>']);
            }

        } else {
            return response(['success' => false, 'message' => 'An error occurred!']);
        }

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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
;
    }

    public function delete($id): bool
    {
        $commission = Commission::where('id','=',$id);
        if($commission->count() > 0)
        {
            return $commission->delete();
        }
        return false;
    }

    /**
     * March 04,2020
     * @author john kevin paunel
     * will set the maximum commission rate depending on the project and up line commission rate
     * @param int $project_id
     * @return mixed
     * */
    public function getUpLineCommissionOnAProject($project_id)
    {
        $user = auth()->user();

        if($user->hasRole('super admin'))
        {
            /*if the user is a super admin*/
            $project = Project::find($project_id);

            /*commission will be based on project rate*/
            if($project->commission_rate > 4)
            {
                /*the maximum commission rate for down lines is only 4%*/
                $commission = 5;
            }else{
                $commission = $project->commission_rate - 0.5;
            }

        }else{
            /*if the user is not a super admin*/

            /*commission will be based on up line rate per project*/
            $rate = $user->commissions()->where('project_id','=',$project_id);

            if($rate->count() > 0)
            {
                $commission = $rate->first()->commission_rate;
            }else{
                $commission = $user->commissions()->where('project_id','=',null)->first()->commission_rate;
            }
            $commission = $commission - 0.5;
        }

        /*this will be used as a drop down for commission rate found on commission.js file*/
        $option = array();
        for($ctr = 0; $commission > 0; $ctr++)
        {
            $option[$ctr] = $commission - 0;
            $commission = $commission - 0.5;
        }

        return $option;
    }
}
