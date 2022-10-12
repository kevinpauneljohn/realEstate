<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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
