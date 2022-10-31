<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\attendance;

class AttendancesController extends Controller
{
    public function index()
    {
        $users = DB::select('select * from attendances');
        return view('pages.attendances.index',['users'=>$users]);
    }

    public function timesheet()
    {
        return view('pages.attendances.timesheet');
    }
    public function timeIn(Request $request,$id)
    {   
        $users = DB::find($id);
        $users->timein = $request->input('btnTimeIn');
        $users->update();
        return view('pages.attendances.index',['users'=>$users]);    
    }
}
