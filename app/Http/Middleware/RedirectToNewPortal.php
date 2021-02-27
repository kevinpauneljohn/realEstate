<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToNewPortal
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

        if(request()->getHost() === "crm.dream-homeseller.com")
        {
            redirect('http://portal.dream-homeseller.com');
        }

        return $response;
    }
}
