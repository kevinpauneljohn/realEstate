<?php

namespace App\Http\Controllers;

use App\AmountWithdrawalRequest;
use Illuminate\Http\Request;

class AmountWithdrawalRequestController extends Controller
{
    public function show($id)
    {

        $cashRequest = AmountWithdrawalRequest::where('cash_request_id',$id);
        return view('pages.amount_withdrawal.show')->with([
            'cashRequests'  => $cashRequest,
            'cashRequestId' => $id
        ]);
    }
}
