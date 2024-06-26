<?php

namespace App\Http\Controllers\CustomAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function maxAttempts()
    {
        return 3;
    }

    public function decayMinutes()
    {
        return 0.52;
    }
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

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            //reset login attempt counter
            $request->session()->forget('attempts');

            return $this->sendLockoutResponse($request);
        }

        $credential = $request->only('username','password');
        if(Auth::attempt($credential,$remember))
        {
            activity()->causedBy(\auth()->user()->id)->withProperties(['username' => auth()->user()->username])->log('user logged in');
            $request->session()->forget('attempts');
            return redirect(route('dashboard'));
        }
        //login failed counter
        $request->session()->put('attempts', $request->session()->get('attempts') + 1);

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out
        $this->incrementLoginAttempts($request);

        return back()->with(
            [
                'success' => false,
                'message' => 'Invalid Credential',
            ])->withInput($request->all());
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
        $request->session()->forget('rate');
        $request->session()->forget('user_rate');

        return redirect(route('login'));
    }
}
