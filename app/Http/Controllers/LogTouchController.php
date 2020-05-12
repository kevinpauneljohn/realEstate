<?php

namespace App\Http\Controllers;

use App\LogTouch;
use App\Repositories\LogTouchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogTouchController extends Controller
{
    public $logTouchRepository;

    public function __construct(LogTouchRepository $logTouchRepository)
    {
        $this->logTouchRepository = $logTouchRepository;
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'medium'          => 'required',
            'date'            => 'required',
            'time'            => 'required',
            'resolution'      => 'required',
            'description'      => 'max:3000',
        ]);

        if($validation->passes())
        {
            $data = array(
                'lead_id'       => $request->lead_id,
                'medium'        => $request->medium,
                'date'          => $request->date,
                'time'          => $request->time,
                'resolution'    => $request->resolution,
                'description'   => nl2br($request->description),
            );

            //create a log touches activity
            $response = $this->logTouchRepository->add_log_touches($data);
            return response()->json($response);
        }
        return response()->json($validation->errors());
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'edit_medium'          => 'required',
            'edit_date'            => 'required',
            'edit_time'            => 'required',
            'edit_resolution'      => 'required',
            'edit_description'      => 'max:3000',
        ],[
            'edit_medium.required'          => 'Medium field is required',
            'edit_date.required'            => 'Date field field is required',
            'edit_time.required'            => 'Time field is required',
            'edit_resolution.required'      => 'Resolution field is required',
            'edit_description.max'          => '3000 characters allowed only',
        ]);

        if($validation->passes())
        {
            //check if the lead id matches the Logs id
            $countLogs = LogTouch::where([['lead_id','=',$request->lead_id],['id','=',$id]])->count();
            if($countLogs > 0)
            {
                //return true if exist
                $logs = LogTouch::find($id);
                $logs->medium = $request->edit_medium;
                $logs->date = $request->edit_date;
                $logs->time = $request->edit_time;
                $logs->resolution = $request->edit_resolution;
                $logs->description = $request->edit_description;
                    if($logs->isDirty())
                    {
                        $logs->save();
                        return response()->json(['success' => true, 'message' => 'Activity logs successfully updated!']);
                    }
                    return response()->json(['success' => false, 'message' => 'No changes occurred']);
            }else{
                return response()->json(['success' => false, 'message' => 'An error occurred<br/>Initiating reload', 'reload' => true]);
            }
        }
        return response()->json($validation->errors());
    }

    public function show($id)
    {
        if($logs = LogTouch::find($id))
        {
            return response()->json(['success' => true,'logs' => $logs,
                'timelineDate' => $logs->date->format('Y-m-d')]);
        }else{
            return response()->json(['success' => false, 'message' => 'Error occurred! <br/> Initiate browser reload']);
        }

    }

    public function destroy($id)
    {
        $logs = LogTouch::find($id);
        $logs->delete();
        return response()->json(['success' => true,'message' => 'Activity Logs successfully deleted!']);
    }
}
