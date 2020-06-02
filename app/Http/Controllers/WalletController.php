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
        $remaining_balance = Wallet::where([
            ['user_id','=',auth()->user()->id],
            ['category','!=','cash advance'],
            ['status','!=','completed'],
        ])->sum('amount');
        return view('pages.wallet.index')->with([
            'current_balance' => Wallet::where([['user_id','=',auth()->user()->id],['status','!=','completed']])->sum('amount'),
            'remaining_balance' => $remaining_balance,
        ]);
    }

    public function total_wallet_amount()
    {
        $wallets = Wallet::where('user_id',auth()->user()->id)->get();

        return DataTables::of($wallets)
            ->addColumn('select',function($wallet){
                $checkbox = '<input type="checkbox" name="source" class="source" value="'.$wallet->id.'">';
                if($wallet->status === 'available')
                {return $checkbox;}
            })
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
            ->addColumn('action',function($wallet){
                $action = '<button type="button" class="btn btn-xs btn-info" title="Withdrawal History"><i class="fas fa-history"></i></button>';
                return $action;
            })
            ->rawColumns(['amount','category','select','action'])
            ->make(true);
    }

    /**
     * @since June 02, 2020
     * @author john kevin paunel
     * get the data of all selected source
     * @param Request $request
     * @return object
     * */
    public function source(Request $request)
    {
        $wallet = Wallet::whereIn('id',$request->id)->get();
        return $wallet;
    }

    public function withdrawMoney(Request $request)
    {
        return $request->all();
    }
}
