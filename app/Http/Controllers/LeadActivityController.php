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
    public function lead_activity_list($id)
    {
        $leadActivities = LeadActivity::where([
            ['user_id','=',auth()->user()->id],
            ['lead_id','=',$id],
        ])->get();
        return DataTables::of($leadActivities)
            ->editColumn('schedule', function($leadActivity){
                $hidden = '<input type="hidden" id="hidden-value-'.$leadActivity->id.'" value="'.$leadActivity->details.'">';
                $hidden .= '<input type="hidden" id="hidden-client-'.$leadActivity->id.'" value="'.$leadActivity->lead->fullname.'">';
                return $hidden.$leadActivity->schedule->format('M d, Y').' <span style="color: #256cef;">at</span> '.$leadActivity->start_date;
            })
            ->addColumn('recent',function ($leadActivity){
                return $leadActivity->schedule->diffForHumans();
            })
            ->addColumn('action', function ($leadActivity)
            {
                $action = "";
                if(auth()->user()->can('view lead'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-success view-btn" id="'.$leadActivity->id.'"><i class="fa fa-eye"></i></button>';
                }
                if(auth()->user()->can('edit lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-reminder-btn" id="'.$leadActivity->id.'" data-toggle="modal" data-target="#edit-reminder"><i class="fa fa-edit"></i> </a>';
                }
                if(auth()->user()->can('delete lead'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-schedule-btn" id="'.$leadActivity->id.'" data-toggle="modal" data-target="#delete-schedule-modal"><i class="fa fa-trash"></i> </a>';
                }
                return $action;
            })
            ->editColumn('status',function($leadActivity){
                $checked = "";
                if($leadActivity->status !== 'pending')
                {
                    $checked = "checked";
                }
                return '<div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="customSwitch'.$leadActivity->id.'" value="'.$leadActivity->id.'" '.$checked.'>
                      <label class="custom-control-label" for="customSwitch'.$leadActivity->id.'"></label>
                    </div>';
            })
            ->rawColumns(['action','details','status','schedule'])
            ->make(true);
    }

    /**
     * Feb 25, 2020
     * @author john kevin paunel
     * set the status label
     * @param string $status
     * @return mixed
     * */
    public static function status_label($status)
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
            'reminder_date'      => 'required',
            'reminder_time'      => 'required',
            'reminder_category'      => 'required',
            'reminder_details'      => 'required',
        ]);

        if($validator->passes())
        {
            $leadActivity = new LeadActivity();
            $leadActivity->user_id = auth()->user()->id;
            $leadActivity->lead_id = $request->lead_id;
            $leadActivity->details = $request->reminder_details;
            $leadActivity->schedule = $request->reminder_date;
            $leadActivity->start_date = $request->reminder_time;
            $leadActivity->category = $request->reminder_category;
            $leadActivity->status = "pending";

            if($leadActivity->save())
            {
                return response()->json(['success' => true,'message' => 'Reminder successfully saved!',
                    'leadActivity' => $leadActivity, 'recent' => $leadActivity->schedule->diffForHumans(),
                    'schedule' => $leadActivity->schedule->format('M d, Y').' <span style="color: #256cef;">at</span> '.$leadActivity->start_date]);
            }
            return response()->json(['success' => false, 'message' => 'Reminder was not saved']);
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
        return response()->json(['activity' => $leadsActivity,'date_scheduled' => $leadsActivity->schedule->format('Y-m-d')]);
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
        $leadActivity = LeadActivity::findOrFail($id);
        if($leadActivity->delete())
        {
            return response()->json(['success' => true]);
        }
    }
}
