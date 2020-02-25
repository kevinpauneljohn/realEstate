<?php

namespace App\Http\Controllers;

use App\Lead;
use App\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LeadActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Feb. 23, 2020
     * @author john kevin paunel
     * fetch all lead activity details
     * */
    public function lead_activity_list()
    {
        $leadActivities = LeadActivity::where('user_id',auth()->user()->id)->get();
        return DataTables::of($leadActivities)
            ->addColumn('action', function ($leadActivity)
            {
                $action = "";
//                if(auth()->user()->can('view lead'))
//                {
//                    $action .= '<a href="'.route("leads.show",["lead" => $leadActivity->id]).'" class="btn btn-xs btn-success view-btn" id="'.$leadActivity->id.'"><i class="fa fa-eye"></i> View</a>';
//                }
                if(auth()->user()->can('edit lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-schedule-btn" id="'.$leadActivity->id.'" data-toggle="modal" data-target="#edit-schedule-modal"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->can('delete lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$leadActivity->id.'" data-toggle="modal" data-target="#delete-lead-modal"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->editColumn('status',function($leadActivity){
                return $this->status_label($leadActivity->status);
            })
            ->rawColumns(['action','details','status'])
            ->make(true);
    }

    /**
     * Feb 25, 2020
     * @author john kevin paunel
     * set the status label
     * @param string $status
     * @return mixed
     * */
    public function status_label($status)
    {
        switch ($status) {
            case 'pending':
                return '<small class="badge badge-warning">'.$status.'</small>';
                break;
            case 'on-going':
                return '<small class="badge badge-info">'.$status.'</small>';
                break;
            case 'completed':
                return '<small class="badge badge-success">'.$status.'</small>';
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * Feb 26, 2020
     * @author john kevin paunel
     * check the date if there is already schedule set
     * @param string $date
     * @return object
     * */
    public function checkSchedule($date)
    {
        $schedule = LeadActivity::where([
            ['schedule','=',$date],
            ['user_id','=',auth()->user()->id],
        ])->get();

        return $schedule;
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
            'schedule'      => 'required',
            'category'      => 'required',
        ]);

        if($validator->passes())
        {
            $leadActivity = new LeadActivity();
            $leadActivity->user_id = auth()->user()->id;
            $leadActivity->lead_id = $request->leadId;
            $leadActivity->details = $request->remarks;
            $leadActivity->schedule = $request->schedule;
            $leadActivity->start_date = $request->start_time;
            $leadActivity->end_date = $request->end_time;
            $leadActivity->category = $request->category;
            $leadActivity->status = "pending";

            if($leadActivity->save())
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
        $leadsActivity = LeadActivity::findOrFail($id);
        return $leadsActivity;
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
            'edit_schedule'      => 'required',
            'edit_category'      => 'required',
        ]);

        if($validator->passes())
        {
            $leadActivity = LeadActivity::findOrFail($id);
            $leadActivity->details = $request->edit_remarks;
            $leadActivity->schedule = $request->edit_schedule;
            $leadActivity->start_date = $request->edit_start_time;
            $leadActivity->end_date = $request->edit_end_time;
            $leadActivity->category = $request->edit_category;

            if($leadActivity->save())
            {
                return response()->json(['success' => true]);
            }
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
        //
    }
}
