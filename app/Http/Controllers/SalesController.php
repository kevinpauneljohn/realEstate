<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Lead;
use App\ModelUnit;
use App\Network;
use App\Project;
use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.sales.index')->with([
            'leads' => Lead::where('user_id',auth()->user()->id)->get(),
            'projects'   => Project::all(),
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
        $validator = Validator::make($request->all(), [
            'reservation_date'  => 'required',
            'buyer'  => 'required',
        ],[
            'reservation_date.required' => 'Reservation Date is required'
        ]);

        if($validator->passes())
        {
            $sales = new Sales();
            $sales->reservation_date = $request->reservation_date;
            $sales->user_id = auth()->user()->id;
            $sales->lead_id = $request->buyer;
            $sales->project_id = $request->project;
            $sales->model_unit_id = $request->model_unit;
            $sales->total_contract_price = $request->total_contract_price;
            $sales->discount = $request->discount;
            $sales->reservation_fee = $request->reservation_fee;
            $sales->equity = $request->equity;
            $sales->loanable_amount = $request->loanable_amount;
            $sales->financing = $request->financing;
            $sales->terms = $request->terms;
            $sales->details = $request->terms;
            $sales->commission_rate = $this->setCommissionRate($request->project);
            $sales->status = 'reserved';

            if($sales->save())
            {
                return response()->json(['success' => true]);
            }
        }
        return response()->json($validator->errors());
    }


    public function get_upline_rate($project_id)
    {
        //we need to get all upline IDs
    }


    /**
     * @author john kevin paunel
     * set the agents commission rate
     * @param int $project_id
     * @return mixed
     * */
    public function setCommissionRate($project_id)
    {
        $upline = Commission::where('user_id',auth()->user()->id)->first();
        $upline_id = $upline->upline_id;
        
    }


    /**
     * March 06, 2020
     * @author john kevin paunel
     * fetch all sales
     * */
    public function sales_list()
    {
        $sales = Sales::where('user_id',auth()->user()->id)->get();
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
            ->addColumn('action', function ($sale)
            {
                $action = "";
                if(auth()->user()->can('view sales'))
                {
                    $action .= '<a href="'.route("leads.show",["lead" => $sale->id]).'" class="btn btn-xs btn-success view-btn" id="'.$sale->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->can('edit sales'))
                {
                    $action .= '<a href="'.route("leads.edit",["lead" => $sale->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$sale->id.'"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete sales'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$sale->id.'" data-toggle="modal" data-target="#delete-lead-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
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
