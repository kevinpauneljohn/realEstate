<?php

namespace App\Http\Controllers;

use App\AmountWithdrawalRequest;
use App\CashRequest;
use App\Events\CashRequestEvent;
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
            'cash_advances' => Wallet::where([
                ['user_id','=',auth()->user()->id],
                ['status','!=','completed'],
                ['category','=','cash advance']
            ])->sum('amount')
        ]);
    }

    public function total_wallet_amount()
    {
        $wallets = Wallet::where([
            ['user_id','=',auth()->user()->id],
            ['amount','!=',0]
        ])->get();

        return DataTables::of($wallets)
            ->setRowClass(function ($wallet){
                $amount_request = Wallet::find($wallet->id)->AmountWithdrawalRequests->where('status','=','pending');

                if($amount_request->count() > 0)
                {
                    return 'request-pending';
                }

            })
            ->addColumn('select',function($wallet){
                $amount_request = Wallet::find($wallet->id)->AmountWithdrawalRequests->where('status','=','pending');

                $checkbox = '<input type="checkbox" name="source" class="source" value="'.$wallet->id.'">';
                if($wallet->status === 'available' && $amount_request->count() < 1)
                {return $checkbox;}
            })
            ->editColumn('created_at',function($wallet){
                return $wallet->created_at->format('M d, Y h:i a');
            })
            ->editColumn('amount',function($wallet){
                return '<span class="text-success">&#8369; '.number_format($wallet->amount,2).'</span>';
            })
            ->editColumn('category',function($wallet){
                return '<span class="text-primary">'.$wallet->category.'</span>';
            })
            ->addColumn('sender',function($wallet){
                $user = User::find($wallet->details->sender);
                //return $user->fullname;
                return ($user->hasRole('super admin')) ? "System"  : $user->fullname;
            })
            ->addColumn('description',function($wallet){
                return $wallet->details->description;
            })
            ->addColumn('cash_request',function($wallet){
                $cash_request = Wallet::find($wallet->id)->AmountWithdrawalRequests->where('status','=','pending');
                if($cash_request->count() > 0)
                {
                    $request = str_pad($cash_request->first()->cash_request_id, 5, '0', STR_PAD_LEFT);
                    return '<a href="#">#'.$request.'</a>';
                }
            })
            ->addColumn('action',function($wallet){
                $action = '<button type="button" class="btn btn-xs btn-info" title="Withdrawal History" id="'.$wallet->id.'"><i class="fas fa-history"></i></button>';
                return $action;
            })
            ->rawColumns(['amount','category','select','action','cash_request'])
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

    /**
     * @since June 02, 2020
     * @author john kevin paunel
     * withdraw moeny from wallet
     * @param Request $request
     * @return mixed
     * */
    public function withdrawMoney(Request $request)
    {
        //combined the keys and value into pairs
        $collection = collect($request->id);
        $combined = $collection->combine($request->custom_amount);

        $error = array();
        $ctr = 0;
        //validate the field
        foreach ($combined as $key => $value)
        {
            $wallet = Wallet::find($key);
            if($value > $wallet->amount)
            {
                $error[$key] = 'The custom amount must not be higher than the original amount';
                $ctr++;
            }elseif ($value === null){
                $error[$key] = 'The custom amount field is required';
                $ctr++;
            }
        }

        if($ctr > 0)
        {
            //return the error validation
            return response()->json($error);
        }else{
            //save the request if there are no validation errors

            //create a cash request number first
            $cashRequest = new CashRequest();
            $cashRequest->user_id = auth()->user()->id;
            $cashRequest->status = 'pending';
            $cashRequest->save();

            foreach ($combined as $key => $value)
            {
                $wallet = Wallet::find($key);

                $amountWithdrawalRequest = new AmountWithdrawalRequest();
                $amountWithdrawalRequest->cash_request_id = $cashRequest->id;
                $amountWithdrawalRequest->wallet_id = $key;
                $amountWithdrawalRequest->original_amount = $wallet->amount;
                $amountWithdrawalRequest->requested_amount = $value;
                $amountWithdrawalRequest->status = 'pending';
                $amountWithdrawalRequest->save();
            }

            //notify the super admin that there are cash requests
            event(new CashRequestEvent($cashRequest));
            return response()->json(['success' => true, 'message' => 'Request successfully submitted']);
        }
    }
}
