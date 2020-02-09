<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        if (Auth::check())
        {
            return redirect(route('dashboard'));
        }
        return redirect(route('login'));
    }
}
