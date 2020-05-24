<?php

namespace App\Http\Middleware;

use App\Threshold;
use Closure;

class RequestLid
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

        $requestId = $request->route()->parameters()['request'];

        $threshold = Threshold::findOrFail($requestId);

        if($threshold->user_id !== auth()->user()->id && $threshold->lid === 0)
        {
            //you dont have any access in this page
            return response("You must open the lid before you access the content!<br/><a href='".route('thresholds.index')."'>View all request</a>",401)->header('Content-Type', 'text/html');
        }

        return $response;
    }
}
