<?php

namespace App\Http\Controllers;

use App\Commission;
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

            if($commission->save())
            {
                return response()->json(['success' => true]);
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
}
