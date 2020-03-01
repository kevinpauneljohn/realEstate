<?php

namespace App\Http\Controllers;

use App\Lead;
use App\LeadActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.schedules.index');
    }

    /**
     * Mar. 03, 2020
     * @author john kevin paunel
     * fetch all schedules
     * */
    public function schedule_list()
    {
        $schedules = LeadActivity::where('user_id',auth()->user()->id)->get();
        return DataTables::of($schedules)
            ->addColumn('full_name', function ($schedule){
                $lead = Lead::findOrFail($schedule->lead_id);
                return ucfirst($lead->firstname).' '.ucfirst($lead->lastname);
            })
            ->addColumn('action', function ($schedule)
            {
                $action = "";
                if(auth()->user()->can('view project'))
                {
                    $action .= '<a href="'.route('leads.show',['lead' => $schedule->lead_id]).'" class="btn btn-xs btn-success view-project-btn" id="'.$schedule->id.'"><i class="fa fa-eye"></i> View</a>';
                }
                return $action;
            })
            ->editColumn('status',function($schedule){
                $checked = "";
                if($schedule->status !== 'pending')
                {
                    $checked = "checked";
                }
                return '<div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="customSwitch'.$schedule->id.'" value="'.$schedule->id.'" '.$checked.'>
                      <label class="custom-control-label" for="customSwitch'.$schedule->id.'"></label>
                    </div>';
                //return LeadActivityController::status_label($schedule->status);
            })
            ->rawColumns(['status','details','action'])
            ->make(true);
    }

    /**
     * Mar. 02, 2020
     * @author john kevin paunel
     * update schedule status
     * */
    public function updateStatus(Request $request)
    {
        $schedule = LeadActivity::findOrFail($request->id);
        $schedule->status = $request->status == "true" ? 'completed' : 'pending';

        if($schedule->save())
        {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
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
        //
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
