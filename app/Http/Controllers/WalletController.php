<?php

namespace App\Http\Controllers;

use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WalletController extends Controller
{
    public function index()
    {
        return view('pages.wallet.index');
    }

    public function total_wallet_amount()
    {
        $wallets = Wallet::where('user_id',auth()->user()->id)->get();

        return DataTables::of($wallets)
            ->editColumn('created_at',function($wallet){
                return $wallet->created_at->format('M d, Y h:i a');
            })
            ->editColumn('amount',function($wallet){
                return '<span class="text-success">&#8369; '.$wallet->amount.'</span>';
            })
            ->editColumn('category',function($wallet){
                return '<span class="text-primary">'.$wallet->category.'</span>';
            })
            ->addColumn('sender',function($wallet){
                $user = User::find($wallet->details->sender);
                return $user->fullname;
            })
            ->addColumn('description',function($wallet){
                return $wallet->details->description;
            })
            ->rawColumns(['amount','category'])
            ->make(true);
    }
}
