<?php

namespace App\Http\Controllers\CustomAuth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{
    use ThrottlesLogins;
    /**
     * Feb. 09, 2020
     * @author john kevin paunel
     * login form view
     * */
    public function login_form()
    {
        if(Auth::check())
        {
            return redirect(route('dashboard'));
        }
        return view('vendor.adminlte.login');
    }

    /**
     * Feb. 09, 2020
     * @author john kevin paunel
     * login validation
     * @param Request $request
     * @return mixed
     * */
    public function authenticate(Request $request)
    {
        $remember = ($request->remember == null) ? false : true;

        $request->validate([
            'username'      => 'required',
            'password'      => 'required'
        ]);
        $credential = $request->only('username','password');
        if(Auth::attempt($credential,$remember))
        {
            return redirect(route('dashboard'));
        }
        return back()->with(['success' => false, 'message' => 'Invalid Credential'])->withInput();
    }

    /**
     * Feb. 09, 2020
     * @author john kevin paunel
     * @param Request $request
     * @return mixed
     * */
    public function logout(Request $request)
    {
        activity()->causedBy(auth()->user()->id)->withProperties(['username' => auth()->user()->username])->log('user logged out');
        Auth::logout();

        return redirect(route('login'));
    }
}
