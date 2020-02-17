<?php

namespace App\Http\Controllers;

use App\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.leads.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.leads.addLeads');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_inquired' => ['date','required'],
            'firstname'     => ['required'],
            'lastname'     => ['required'],
        ]);

        $lead = new Lead();
        $lead->user_id = auth()->user()->id;
        $lead->date_inquired = $request->date_inquired;
        $lead->firstname = $request->firstname;
        $lead->middlename = $request->middlename;
        $lead->lastname = $request->lastname;
        $lead->address = $request->address;
        $lead->landline = $request->landline;
        $lead->mobileNo = $request->mobileNo;
        $lead->email = $request->email;
        $lead->status = $request->status;
        $lead->income_range = $request->income_range;
        $lead->point_of_contact = $request->point_of_contact;
        $lead->remarks = $request->remarks;

        if($lead->save())
        {
            return redirect();
        }
        return back()->withErrors()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pages.leads.edit')->with([
            'lead'  => Lead::findOrFail($id)
        ]);
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
