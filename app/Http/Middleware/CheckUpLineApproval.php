<?php

namespace App\Http\Middleware;

use App\Services\CommissionRequestService;
use Closure;

class CheckUpLineApproval
{
    private $commissionRequest;

    public function __construct(CommissionRequestService $commissionRequest)
    {
        $this->commissionRequest = $commissionRequest;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestId = $request->route()->parameters()['request'];
        $commRequest = $this->commissionRequest->getSpecifiedRequest($requestId);
        if($commRequest->status == "pending" && auth()->user()->hasRole('Finance Admin'))
        {
            abort(404);
        }

        return $next($request);
    }
}
