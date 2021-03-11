<?php

namespace App\Http\Middleware;

use App\Lead;
use Closure;

class OnlyAssignedLeads
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
        $lead_id = $request->segment(2);
        if(Lead::where('id',$lead_id)->where('online_warrior_id',auth()->user()->id)->count() === 0 && auth()->user()->hasRole(['online warrior']))
        {
            return response("You are not allowed to access this leads<br/><a href='".url()->previous()."'>Back</a>",401)
                ->header('Content-Type', 'text/html');
        }
        return $next($request);
    }
}
