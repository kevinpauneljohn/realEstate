<?php

namespace App\Http\Controllers\CustomAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login_form()
    {
        if(Auth::check())
        {
            return redirect(route('dashboard'));
        }
        return view('vendor.adminlte.login');
    }
}
