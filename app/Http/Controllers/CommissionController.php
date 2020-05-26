<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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
                if(auth()->user()->can('edit commission'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$commission->id.'" data-toggle="modal" data-target="#edit-user-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete commission'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-user-btn" id="'.$commission->id.'" data-toggle="modal" data-target="#delete-user-modal"><i class="fa fa-trash"></i> Delete</a>';
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
            /*if the user is a a super admin*/
            $project = Project::find($project_id);

            /*commission will be based on project rate*/
            if($project->commission_rate > 4)
            {
                /*the maximum commission rate for down lines is only 4%*/
                $commission = 4;
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
