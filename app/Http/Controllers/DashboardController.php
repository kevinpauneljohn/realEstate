<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Feb. 09, 2020
     * @author john kevin paunel
     * display dashboard page
     * */
    public function dashboard()
    {
        return view('pages.dashboard');
    }
}
