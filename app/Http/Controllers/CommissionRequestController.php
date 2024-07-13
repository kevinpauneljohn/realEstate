<?php

namespace App\Http\Controllers;

use App\CommissionRequest;
use App\CommissionVoucher;
use App\Repositories\SalesRepository;
use App\Sales;
use App\Services\CommissionRequestService;
use App\Services\CommissionService;
use App\Services\CommissionVoucherService;
use App\Services\DownLineService;
use App\Services\PaymentReminderService;
use App\Services\UpLineService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Rmunate\Utilities\SpellNumber;

class CommissionRequestController extends Controller
{
    public $downLineService,
        $commissionService,
        $upLine,
        $salesRepository,
        $commissionRequest;

    public function __construct(
        DownLineService $downLineService,
        CommissionService $commissionService,
        UpLineService $upLineService,
        SalesRepository $salesRepository,
        CommissionRequestService $commissionRequestService
    )
    {
        $this->middleware('permission:view commission request')->only(['forApproval','getForApproval','forReview','approveRequest']);
        $this->middleware('role:Finance Admin')->only(['setAdminAction']);
        $this->middleware('permission:edit commission request')->only(['updateStatus']);
        $this->downLineService = $downLineService;
        $this->commissionService = $commissionService;
        $this->upLine = $upLineService;
        $this->salesRepository = $salesRepository;
        $this->commissionRequest = $commissionRequestService;
    }


    public function index()
    {
        return view('pages.commissionRequests.myRequest');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * this will check if the requester is the super admin or direct to the super admin
     * @return string
     */
    private function salesStatus(): string
    {
        //if the user is the super admin or direct to the super admin this will automatically
        // set the sales status as "for review"
        $user = auth()->user();
        if($user->hasRole('super admin'))
        {
            return 'for review';
        }
        return User::find(auth()->user()->upline_id)->hasRole('super admin') ? 'for review' : 'pending';

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $commission = $this->commissionService->getAllowedCommission($request->input('sales_id'), auth()->user()->id);
        //check if there is current commission request pending
        if(CommissionRequest::where('user_id',auth()->user()->id)->where('sales_id',$request->input('sales_id'))->whereIn('status',['pending'])->count() === 0)
        {
            //there are no current pending request on the same sales
            if(CommissionRequest::create([
                'sales_id' => $request->input('sales_id'),
                'user_id'  => auth()->user()->id,
                'commission' => $commission['commission'],
                'status'  => $this->salesStatus(),
                'remarks' => [
                    'request_to_developer' => null,
                    'for_release' => null,
                    'rejected' => null,
                    'completed' => null
                ],
                'approval' => collect($commission)->count() > 0 ? $commission['upLines'] : null,
            ])){
                return response()->json(['success' => true, 'message']);
            }
            return response()->json(['success' => false, 'message' => 'An error occurred'],400);
        }

        return response()->json(['success' => false, 'message' => 'There is a pending request already'],400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forApproval()
    {
        return view('pages.commissionRequests.index');
    }


    public function getForApproval()
    {
        return $this->commissionRequest->forApprovalDataTable();
    }

    public function myRequest()
    {
        if(auth()->user()->hasRole(['super admin','Finance Admin']))
        {
            $myRequests = CommissionRequest::all();
        }else{
            $userIds = collect($this->downLineService->extractDownLines(auth()->user()->id)->concat([collect(auth()->user())->toArray()]))->pluck('id');
            $myRequests = CommissionRequest::whereIn('user_id',$userIds)->get();
        }
        return $this->commissionRequest->commissionRequestTable($myRequests);
    }

    /**
     * @param $id
     * @param PaymentReminderService $paymentReminderService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forReview($id, PaymentReminderService $paymentReminderService)
    {
        $commissionRequest = $this->commissionRequest->getSpecifiedRequest($id);
////        return $this->commissionRequest->check_by_pass($id);
        return view('pages.commissionRequests.forReview')->with([
            'commissionRequest' => $commissionRequest,
            'rateGiven' => $commissionRequest->user->commissions()->where('project_id', $commissionRequest->sales->project_id)->count() > 0 ?
                $commissionRequest->user->commissions()->where('project_id', $commissionRequest->sales->project_id)->first()->commission_rate :
                (auth()->user()->hasRole(['super admin','Finance Admin']) ? $commissionRequest->commission : $commissionRequest->user->commissions()->where('project_id', null)->first()->commission_rate),
            'askingRate' => $commissionRequest->commission,
            'lastDueDate' =>  collect($commissionRequest->sales->paymentReminders)->count() > 0 && $commissionRequest->sales->status !== "cancelled"
                ? $commissionRequest->sales->paymentReminders->last()->schedule
                : collect($paymentReminderService->scheduleFormatter($commissionRequest->sales_id,$commissionRequest->sales->reservation_date))->last(),
            'byPass' => $this->commissionRequest->check_by_pass($id),
            'estimatedAmount' => $this->commissionRequest->getAmountRelease($id,null),
            'approvedEstimatedAmount' => $this->commissionRequest->getAmountRelease($id,$commissionRequest->approved_rate),
            'commissionVoucher' => $commissionVoucher = CommissionVoucher::where('commission_request_id',$id),
            'net_commission_in_words' => $commissionVoucher->count() > 0 ? SpellNumber::value($commissionVoucher->first()->net_commission_less_deductions)->locale('en')->currency('Pesos')->toMoney() : '',
            'remaining_request' => 100 - $this->totalCommissionReleased($commissionRequest->sales->id, $commissionRequest->user_id),
            'status' => $this->commissionRequest->status_badge($commissionRequest->status),
            'related_requests' => $this->commissionRequest->related_requests($commissionRequest->sales->id, $commissionRequest->user_id)
        ]);
//        return $this->totalCommissionReleased($commissionRequest->sales->id, $commissionRequest->user_id);
    }

    private function totalCommissionReleased($sales_id, $requester_id)
    {
        $sale = Sales::find($sales_id);
        return CommissionVoucher::whereIn('commission_request_id',collect($sale->commissionRequests->where('user_id',$requester_id))->pluck('id'))->sum('percentage_released');
    }



    /**
     * approve or reject a commission request
     * @param $requestId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setApprovalStatus($requestId, Request $request)
    {
        $commissionRequest = CommissionRequest::find($requestId);
        $commissionRequest->approval = $this->commissionRequest->updateApprovalStatus($commissionRequest->approval, $request->input('remarks'), $request->status,true);
        if($request->status == "rejected")
            $commissionRequest->status = "rejected";
        if($commissionRequest->save())
        {
            if($this->commissionRequest->check_if_all_upLine_permission_was_approved($requestId))
            {
                $commissionRequest->status = 'for review';
                $commissionRequest->save();
            }
            return response()->json(['success' => true, 'message' => 'Commission '.$request->status, $commissionRequest]);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred'],400);
    }

    public function setAdminAction($requestId, Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = Validator::make($request->all(),[
            'action' => 'required'
        ]);

        if($validation->passes())
        {
            $commissionRequest = CommissionRequest::where('id',$requestId);

            if(CommissionRequest::where('id',$requestId)->where('status','rejected')->count() === 0)
            {
                if($request->input('action') == "request to developer")
                {
                    $status = "requested to developer";
                    $commissionRequest->update(['status' => 'requested to developer','remarks->request_to_developer' => $request->input('remarks')]);
                }elseif ($request->input('action') == "for release"){
                    $status = "for release";
                    $commissionRequest->update(['status' => 'for release','remarks->for_release' => $request->input('remarks')]);
                }elseif ($request->input('action') == "reject"){
                    $status = "rejected";
                    $commissionRequest->update(['status' => 'rejected','remarks->rejected' => $request->input('remarks')]);
                }elseif ($request->input('action') == "completed"){
                    $status = "completed";
                    $commissionRequest->update(['status' => 'completed','remarks->completed' => $request->input('remarks')]);
                }


                return response()->json(['success' => true, 'message' => 'Status Updated','request_status' => $status]);
            }
            return response()->json(['success' => false, 'message' => 'Request already rejected']);
        }
        return response()->json($validation->errors());
    }

    public function approval($requestId)
    {
        return $this->commissionRequest->specifiedApprovalDataTable($requestId);
    }


    /**
     *this will check if there are bypass approval request
     */
    public function checkByPassForAllRequest(): void
    {
        Artisan::call('bypass:approval');
    }
    public function updateStatus(CommissionRequest $commissionRequest, Request $request): \Illuminate\Http\JsonResponse
    {
        $commissionRequest->status = $request->status;
        return $commissionRequest->save() ?
            response()->json(['success' => true, 'message' => 'Status updated!']) :
            response()->json(['success' => false, 'message' => 'An error occurred!']) ;
    }


    public function previewVoucher(Request $request, CommissionVoucherService $commissionVoucherService):array
    {
        return $commissionVoucherService->voucherPreview($request);
    }

    public function saveVoucher(Request $request, CommissionVoucherService $commissionVoucherService): \Illuminate\Http\JsonResponse
    {
        return $commissionVoucherService->save($request) ?
            response()->json(['success' => true, 'message' => 'Voucher successfully saved!']):
            response()->json(['success' => false, 'message' => 'No voucher saved!']);
    }

    public function approveVoucher($id): \Illuminate\Http\JsonResponse
    {
        $voucher = CommissionVoucher::findOrFail($id);
        $voucher->status = 'approved';
        if($voucher->save())
        {
            $comm_request = CommissionRequest::find($voucher->commission_request_id);
            $comm_request->status = 'completed';
            $comm_request->save();
            return response()->json(['success' => true, 'message' => 'Voucher approved!']);
        }
        return response()->json(['success' => false, 'message' => 'an error occurred!']);
    }

    public function updateSalesTotalPrice(Request $request, $sales_id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'total_contract_price' => ['required'],
        ]);

        $sales = Sales::findOrFail($sales_id);
        $sales->total_contract_price = $request->total_contract_price;
        if($sales->isDirty())
        {
            $sales->save();
            return response()->json(['success' => true, 'message' => 'Total contract price successfully updated!']);
        }
        return response()->json(['success' => false, 'message' =>  'No changes made!']);
    }
}
