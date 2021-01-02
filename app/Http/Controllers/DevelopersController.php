<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevelopersController extends Controller
{

    public function index()
    {
        return view('developers.index');
    }
}
