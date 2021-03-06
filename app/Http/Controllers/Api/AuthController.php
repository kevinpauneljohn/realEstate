<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * July 31, 2020
     * @author john kevin paunel
     * Authenticate the client's login
     * api route: login
     * @param Request $request
     * @return mixed
     * */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email'      => 'required',
            'password'      => 'required',
        ]);

        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = \auth()->user();
            return response()->json(['user' => $user, 'roles' => $user->getRoleNames()]);

        }else{
            return response()->json(['message' => 'invalid credentials', 'success' => false]);
        }

    }
}
