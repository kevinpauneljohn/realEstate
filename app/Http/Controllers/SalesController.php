<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'leads' => Lead::where('user_id',auth()->user()->id)->get()
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
            $sales->total_contract_price = $request->total_contract_price;
            $sales->discount = $request->discount;
            $sales->reservation_fee = $request->reservation_fee;
            $sales->equity = $request->equity;
            $sales->loanable_amount = $request->loanable_amount;
            $sales->financing = $request->financing;
            $sales->terms = $request->terms;
            $sales->details = $request->terms;
            $sales->commission_rate = $request->commission_rate;

            if($sales->save())
            {
                return response()->json(['success' => true]);
            }
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
