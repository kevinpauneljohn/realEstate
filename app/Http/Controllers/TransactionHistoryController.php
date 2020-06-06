<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        return view('pages.transaction_history.show');
    }

    public function transaction_list()
    {
        $transactions = Transaction::where('user_id',auth()->user()->id)->get();
        return DataTables::of($transactions)
            ->editColumn('cash_request_id',function($transaction){
                $request = str_pad($transaction->cash_request_id, 5, '0', STR_PAD_LEFT);
                return '<a href="#">#'.$request.'</a>';
            })
            ->editColumn('created_at',function($transaction){
                return $transaction->created_at->format('M d, Y - h:i a');
            })
            ->editColumn('category',function($transaction){
                return '<span class="text-success">'.$transaction->category.'</span>';
            })
            ->editColumn('status',function($transaction){
                return '<span class="text-primary">'.$transaction->status.'</span>';
            })
            ->rawColumns(['details','cash_request_id','category','status'])
            ->make(true);
    }
}
