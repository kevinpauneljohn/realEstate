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

    public function destroy($id)
    {
        $logs = LogTouch::find($id);
        $logs->delete();
        return response()->json(['success' => true,'message' => 'Activity Logs successfully deleted!']);
    }
}
