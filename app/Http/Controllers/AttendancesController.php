<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    public function attendances()
    {
        return view('pages.attendances.index');
    }
}
