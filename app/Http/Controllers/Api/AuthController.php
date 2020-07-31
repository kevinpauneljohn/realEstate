<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'username'      => 'required',
            'password'      => 'required',
        ]);

        $credential = $request->only('username','password');

        if(Auth::attempt($credential))
        {
            return response()->json([
                'user'  => \auth()->user(),
                'roles' => \auth()->user()->getRoleNames(),
                'access_token'  => \auth()->user()->api_token,
                'success'   => true,
            ]);
        }

        return response()->json(['message' => 'Invalid Credentials','success' => false]);
    }
}
