<?php

namespace App\Http\Controllers;

use App\AmountWithdrawalRequest;
use App\CashRequest;
use App\Events\AmountWithdrawalRequestEvent;
use App\Events\SaveRequestExtraFieldEvent;
use App\Events\UpdateCashRequestStatusEvent;
use Illuminate\Http\Request;
use ParagonIE\Sodium\Core\ChaCha20\Ctx;
use Yajra\DataTables\DataTables;

class CashRequestController extends Controller
{
    public function index()
    {
        return view('pages.cash_request.index');
    }

    public function cashRequestList()
    {
        $cashRequests = CashRequest::all();
        return DataTables::of($cashRequests)
            ->editColumn('status',function($cashRequest){
                return '<span class="text-primary">'.ucfirst($cashRequest->status).'</span>';
            })
            ->editColumn('id',function($cashRequest){
                return '<span class="text-primary">#'.str_pad($cashRequest->id, 5, '0', STR_PAD_LEFT).'</span>';
            })
            ->editColumn('created_at',function($cashRequest){
                return $cashRequest->created_at->format('M d, Y h:i a');
            })
            ->editColumn('user_id',function($cashRequest){
                return $cashRequest->user->fullname;
            })
            ->addColumn('action',function($cashRequest){
                $action = '<a href="'.route('withdrawal.show',['id' => $cashRequest->id]).'" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>';
                return $action;
            })
            ->rawColumns(['id','action','status'])
            ->make(true);
    }


    /**
     *
     * */
    public function cash_approval(Request $request)
    {
        ///approve or reject the amount requested
        $action = $request->action;

        /// this is the ID of the the specific amount request in amount_withdrawal_requests table
        $amount_withdrawal_id = $request->amount_withdrawal_id;

        //Cash request number where the amount withdrawal id belongs
        $cash_request_number = $request->cash_request_id;


        //this condition validates if the action field was set to approve or reject
        if($action !== null)
        {
            $description= collect($request->extra_description);//array collection of extra description field
            $extra_amount = collect($request->extra_amount); // array collection of extra amount field

            $reference = count($extra_amount); // count the total submitted arrays
            $data = array(); //instantiate the data array as amount and description extra field collection
            for ($ctr = 0; $ctr < $reference; $ctr++)
            {
                //check if the amount or description is not null then will input the value
                if($extra_amount[$ctr] !== null && $description !== null)
                {
                    $data[$ctr] = array(
                        'amount' => $extra_amount[$ctr],
                        'description' => $description[$ctr]
                    );
                }
            }

            //update the specific amount withdrawal requests by id
            event(new AmountWithdrawalRequestEvent($amount_withdrawal_id,$action,$request->remarks));
            //event for saving the extra fields or the withdrawal requests
            event(new SaveRequestExtraFieldEvent($amount_withdrawal_id, $data));
            //if all the amount withdrawal under the cash request has been updated
            //it will update all the amount from the user's wallet
            event(new UpdateCashRequestStatusEvent($cash_request_number));
            return response()->json(['success' => true, 'message' => 'Wallet amount has been successfully updated!']);
        }else{
            return response()->json(['success' => false, 'error' => 'action-'.$amount_withdrawal_id]);
        }
    }

    public function show($id)
    {
        $amount_withdrawal_request = AmountWithdrawalRequest::where('cash_request_id',$id)->get();
        return view('pages.cash_request.show',compact('amount_withdrawal_request'));
    }

}
