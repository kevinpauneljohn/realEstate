<?php

namespace App\Http\Controllers;

use App\CommissionRequest;
use App\Repositories\SalesRepository;
use App\Services\CommissionRequestService;
use App\Services\CommissionService;
use App\Services\DownLineService;
use App\Services\PaymentReminderService;
use App\Services\UpLineService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

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
                'status'  => User::find(auth()->user()->upline_id)->hasRole('super admin') ? 'for review' : 'pending', //this will check if the requester is direct to the super admin
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
        $myRequests = CommissionRequest::where('user_id',auth()->user()->id)->get();
        return $this->commissionRequest->commissionRequestTable($myRequests);
    }

    /**
     * @param $id
     * @param PaymentReminderService $paymentReminderService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forReview($id, PaymentReminderService $paymentReminderService): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $commissionRequest = $this->commissionRequest->getSpecifiedRequest($id);
//        return $this->commissionRequest->check_by_pass($id);
        return view('pages.commissionRequests.forReview')->with([
            'commissionRequest' => $commissionRequest,
            'rateGiven' => $commissionRequest->user->commissions()->where('project_id',$commissionRequest->sales->project_id)->count() > 0
                ? $commissionRequest->user->commissions()->where('project_id',$commissionRequest->sales->project_id)->first()->commission_rate
                : $commissionRequest->user->commissions()->where('project_id',null)->first()->commission_rate,
            'askingRate' => $commissionRequest->commission,
            'lastDueDate' =>  collect($commissionRequest->sales->paymentReminders)->count() > 0 && $commissionRequest->sales->status !== "cancelled"
                ? $commissionRequest->sales->paymentReminders->last()->schedule
                : collect($paymentReminderService->scheduleFormatter($commissionRequest->sales_id,$commissionRequest->sales->reservation_date))->last(),
            'byPass' => $this->commissionRequest->check_by_pass($id),
            'estimatedAmount' => $this->commissionRequest->getAmountRelease($id,null),
            'approvedEstimatedAmount' => $this->commissionRequest->getAmountRelease($id,$commissionRequest->approved_rate),
        ]);
//        return collect($commissionRequest->sales->clientRequirements)->first()->drive_link;
//        return collect($commissionRequest->sales->clientRequirements)->count() > 0 ? collect($commissionRequest->sales->clientRequirements)->first()->drive_link : "";
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

    public function setAdminAction($requestId, Request $request)
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
    public function checkByPassForAllRequest()
    {
        Artisan::call('bypass:approval');
    }
}
