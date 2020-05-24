<?php

namespace App\Http\Middleware;

use App\Commission;
use Closure;

class CheckCommission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $response = $next($request);

        $user = auth()->user();
        $commission = Commission::where('user_id',$user->id)->count();
        if($commission == 0 && !$user->hasRole('super admin'))
        {
            return response("You must request for a commission rate before you can add sales <br/><a href='".url()->previous()."'>Back</a>",401)->header('Content-Type', 'text/html');
        }
        return $response;
    }
}
