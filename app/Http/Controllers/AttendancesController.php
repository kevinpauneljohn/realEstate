<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    public function index()
    {
        return view('pages.attendances.index');
    }

    public function timesheet()
    {
        return view('pages.attendances.timesheet');
    }
}
