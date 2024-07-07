<?php


namespace App\Services;


use App\CommissionRequest;
use App\CommissionVoucher;
use App\Project;
use App\User;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class CommissionRequestService
{
    public $paymentReminder,
            $downLineService;
    public function __construct(
        PaymentReminderService $paymentReminderService,
        DownLineService $downLineService
    )
    {
        $this->paymentReminder = $paymentReminderService;
        $this->downLineService = $downLineService;
    }

    public function getCommissionRequests()
    {
        return CommissionRequest::where('status','pending')->get();
    }

    public function getSpecifiedRequest($id)
    {
        return CommissionRequest::findOrFail($id);
    }

    /**
     * update the approval columns and status
     * @param $requestId
     * @param $approvals
     */
    public function updateCommissionRequest($requestId, $approvals)
    {
        $commissionRequest = $this->getSpecifiedRequest($requestId);
        $commissionRequest->approval = $approvals;

         if($commissionRequest->save())
             if($this->check_if_all_upLine_permission_was_approved($requestId))
             {
                 $commissionRequest->status = 'for review';
                 $commissionRequest->save();
             }
    }

    /**
     * this will get the amount requested
     * @param $requestId
     * @param $rate
     * @return float|int
     */
    public function getAmountRelease($requestId, $rate)
    {
        $request = $this->getSpecifiedRequest($requestId);
        $sales = $request->sales;
        $netTCP = $sales->total_contract_price - $sales->discount;
        $rate = $rate == null ? $request->commission : $rate;
//        return $netTCP * ($request->commission / 100);
        return $netTCP * ($rate / 100);
    }

    /**
     * fetch for approval commission requests
     * @return \Illuminate\Support\Collection
     */
    public function forUpLinesApproval()
    {
        if(auth()->user()->hasRole(['Finance Admin']))
        {
            return CommissionRequest::where('status','for review')->get();
        }

        return collect($this->getCommissionRequests())->filter(function($value, $key){

            if(collect($value->approval)->where('id',auth()->user()->id)->whereNull('approval')->count() > 0)
                return $value->approval;
        });
    }

    /**
     * display all commission requests approval
     * @return mixed
     * @throws \Exception
     */
    public function forApprovalDataTable()
    {
        return $this->commissionRequestTable($this->forUpLinesApproval());
    }


    public function commissionRequestTable($request)
    {
        return DataTables::of($request)
            ->addColumn('requestNo',function($commissionRequest){
                $request = str_pad($commissionRequest->id, 6, '0', STR_PAD_LEFT);
                return '<a href="'.route('tasks.overview',$commissionRequest->id).'"><span style="color:#007bff">#'.$request.'</span></a>';
            })
            ->addColumn('dateRequested',function($commissionRequest){
                return $commissionRequest->created_at->format('F-d-Y');
            })
            ->addColumn('project',function($commissionRequest){
                return $commissionRequest->sales->project->name;
            })
            ->addColumn('client',function($commissionRequest){
                return $commissionRequest->sales->lead->fullname;
            })
            ->addColumn('tcp',function($commissionRequest){
                return number_format($commissionRequest->sales->total_contract_price,2);
            })
            ->addColumn('discount',function($commissionRequest){
                return number_format($commissionRequest->sales->discount,2);
            })
            ->addColumn('agent',function($commissionRequest){
                return $commissionRequest->user->fullname;
            })
            ->addColumn('upLine',function($commissionRequest){
                return auth()->user()->hasRole('super admin') ? '' : User::find($commissionRequest->user->upline_id)->fullname;
            })
            ->addColumn('rate',function($commissionRequest){
                if(auth()->user()->hasRole('super admin'))
                {
                    $rate = $commissionRequest->sales->commission_rate;
                }
                else{
                    $rate = $commissionRequest->user->commissions()->where('project_id',$commissionRequest->sales->project_id)->count() > 0
                        ? $commissionRequest->user->commissions()->where('project_id',$commissionRequest->sales->project_id)->first()->commission_rate
                        : $commissionRequest->user->commissions()->where('project_id',null)->first()->commission_rate;
                }
                return $rate.'%';
            })
            ->addColumn('rateRequested',function($commissionRequest){
                return $commissionRequest->commission.'%';
            })
            ->addColumn('lastDueDate',function($commissionRequest){
                $dueDate = collect($commissionRequest->sales->paymentReminders)->count() > 0 && $commissionRequest->sales->status !== "cancelled"
                    ? $commissionRequest->sales->paymentReminders->last()->schedule
                    : collect($this->paymentReminder->scheduleFormatter($commissionRequest->sales_id,$commissionRequest->sales->reservation_date))->last();
                return Carbon::create($dueDate)->format('F-d-Y');
            })
            ->addColumn('status',function($commissionRequest){
                return $this->status_badge($commissionRequest->status);
            })
            ->addColumn('percentage_released', function($commissionRequest){
                $voucher = CommissionVoucher::where('commission_request_id',$commissionRequest->id);
                return $voucher->count() > 0 ? '<span class="text-bold text-primary">'.$voucher->first()->percentage_released.'%</span>' : '';
            })
            ->addColumn('amount_released', function($commissionRequest){
                $voucher = CommissionVoucher::where('commission_request_id',$commissionRequest->id);
                return $voucher->count() > 0 ? '<span class="text-bold text-primary">'.number_format($voucher->first()->net_commission_less_deductions,2).'</span>' : '';
            })
            ->addColumn('action',function($commissionRequest){
                $action = '';

                if(auth()->user()->can('view commission request'))
                {
                    $action .= '<a href="'.route("commission.request.review",["request" => $commissionRequest->id]).'" class="btn btn-default btn-xs">View</a>';
                }
                return $action;
            })
            ->rawColumns(['requestNo','action','status','percentage_released','amount_released'])
            ->make(true);
    }

    private function status_badge($status)
    {
        return match ($status) {
            'pending' => '<span class="badge badge-warning">' . $status . '</span>',
            'for review' => '<span class="badge badge-info">' . $status . '</span>',
            'requested' => '<span class="badge bg-purple">' . $status . '</span>',
            'for release' => '<span class="badge bg-pink">' . $status . '</span>',
            'completed' => '<span class="badge badge-success">' . $status . '</span>',
            'rejected' => '<span class="badge badge-danger">' . $status . '</span>',
            default => '',
        };
    }


    public function specifiedApprovalDataTable($requestId)
    {
//        return DataTables::of(collect($this->getSpecifiedRequest($requestId)->approval)->where('id','!=',auth()->user()->id)->all())
        return DataTables::of(collect($this->getSpecifiedRequest($requestId)->approval)->where('id','!=',auth()->user()->id)->all())
            ->editColumn('id',function($request){
                return User::find($request['id'])->fullname;
            })
            ->editColumn('approval',function($request){
                if($request['approval'] === null)
                {
                    $approval = '<span class="text-info text-bold">Pending</span>';
                }elseif($request['approval'] === "approved" && $request['is_by_passed'] === true && $request['action'] == null){
                    $approval = '<span class="text-success text-bold">Approved</span>';
                }elseif($request['approval'] === "approved" && $request['is_by_passed'] === true && $request['action'] != null){
                    $approval = '<span class="text-purple text-bold">Bypassed</span>';
                }else{
                    $approval = '<span class="text-danger text-bold">Rejected</span>';
                }
                return $approval;
            })
            ->rawColumns(['approval'])
            ->make(true);
    }

    /**
     * display all commission requests
     * @return \Illuminate\Support\Collection
     */
    public static function commissionRequest()
    {
        return collect(CommissionRequest::where('status','pending')->get())->filter(function($value, $key){

            if(collect($value->approval)->where('id',auth()->user()->id)->whereNull('approval')->count() > 0)
                return $value->approval;
        });
    }


    /**
     * set the commission request remarks and status to approved or rejected
     * @param $approvals
     * @param $remarks
     * @param $status
     * @param $isByPassed
     * @return array
     */
    public function updateApprovalStatus($approvals, $remarks, $status, $isByPassed)
    {
        $data = [];

        foreach ($approvals as $key => $value)
        {
            if($value['id'] === auth()->user()->id)
            {
                $value['remarks'] = $remarks;
                $value['approval'] = $status;
                $value['byPass'] = now();
                $value['is_by_passed'] = $isByPassed;
            }
            $data[$key] = $value;

        }
        return $data;
    }

    /**
     * this will check if all up line approved the commission request
     * @param $requestId
     * @return bool
     */
    public function check_if_all_upLine_permission_was_approved($requestId)
    {
        $totalUpLines = collect($this->getSpecifiedRequest($requestId)->approval)->count();
        $totalApproved = collect($this->getSpecifiedRequest($requestId)->approval)->where('approval','approved')->count();
        return $totalUpLines === $totalApproved;
    }

    private function daysPasses($dateByPass)
    {
        return today()->diffInDays($dateByPass,false);
    }

    private function byPassConsent($daysPasses, $daysByPass, $previousApproval, $byPassDate, $hierarchyApproval)
    {
        return today()->diffInDays($daysPasses,false) > $daysByPass
            && $previousApproval == "approved" && $byPassDate == null
            && $hierarchyApproval == true;
    }

    /**
     * this will check if the up line will be bypass
     * @param $requestId
     * @return array
     */
    public function check_by_pass($requestId)
    {
        $commissionRequest = $this->getSpecifiedRequest($requestId);
        $byPass = [];
        if(collect($commissionRequest->approval)->count() > 0)
        $daysComparison = 0;

        foreach ($commissionRequest->approval as $key => $approval)
        {
            $daysByPass = -7;


            $byPassDate = $commissionRequest->user->upline_id == $approval['id'] ? $commissionRequest->created_at : $approval['byPass'];

            $byPassDateReference = $commissionRequest->user->upline_id == $approval['id'] ? $byPassDate : $byPass[$key-1]['byPassApprovalDate'];

            $daysPasses = $this->daysPasses($byPassDateReference);
            $upLine = User::find($approval['id']);
            $daysByPass = !$upLine->hasRole('team leader') ? $daysByPass : -30;

            $hierarchyApproval = $this->hierarchy($commissionRequest, $approval, $byPass, $key);

            $byPassConsent = $this->byPassConsent($byPassDateReference, $daysByPass,
                collect($byPass)->count() > 0 ? $byPass[$key-1]['approval']:"approved",
                $commissionRequest->user->upline_id == $approval['id'] ? null :$approval['byPass'], $hierarchyApproval);


            $byPass[$key] = [
                'byPassApprovalDate' => $approval['byPass'],
                'byPassDate' => $byPassDateReference,
                'daysByPass' => $daysByPass,
                'daysPasses' => $daysPasses,
                'byPassConsent' => $byPassConsent,
                'upLine_id' => $approval['id'],
                'upLineName' => $upLine->fullname,
                'comparison' => $daysComparison,
                'AllowByPassApproveAndReject' => $byPassConsent, ///$byPassApproval,
                'approval' => $approval['approval'],
                'approvalHierarchyConsent' => $hierarchyApproval,
                'finalConsent' => $this->consent($byPassConsent, $hierarchyApproval),
                'is_by_passed' => $approval['is_by_passed'],
                'request_id' => $requestId,
            ];
        }
        return $byPass;
    }

    /**
     * this is also use for check_by_pass method
     * @param $byPassApproval
     * @param $hierarchyApproval
     * @return bool
     */
    private function consent($byPassApproval, $hierarchyApproval)
    {
        if($hierarchyApproval == true && $byPassApproval == true){
            return true;
        }

        if($hierarchyApproval == false && $byPassApproval == false)
        {
            return false;
        }

        if($hierarchyApproval == true && $byPassApproval == false)
        {
            return false;
        }

        return true;
    }

    /**
     * organize the up line approval by hierarchy
     * @param $commissionRequest
     * @param $approval
     * @param $byPassArray
     * @param $key
     * @return bool
     */
    public function hierarchy($commissionRequest, $approval, $byPassArray, $key)
    {
        $previousData = collect($byPassArray)->count() > 0 ? $byPassArray[$key-1]['approval'] : null;
        $consent = true;
            if($approval['id'] == $commissionRequest->user->upline_id && $approval['approval'] === null && $commissionRequest->status != "rejected")
            {
                return $consent;
            }
                $consent = false;

                if($previousData == "approved" && $approval['approval'] === null && $commissionRequest->status != "rejected")
                {
                    $consent = true;
                }

//        return collect($byPassArray)->count() > 0 ? $byPassArray[$key-1]['approval'] : null;
        return $consent;
    }

    /**
     * this will check if the request was in hierarchy
     * @param array $commissionRequestApproval
     * @return bool|mixed
     */
    public static function commissionRequestHierarchyApproval($commissionRequestApproval): mixed
    {
        $consent = true;
        foreach ($commissionRequestApproval as $approval){
            if($approval['id'] === auth()->user()->id)
            {
                break;

            }
            if($approval['approval'] === null){
                $consent = false;
            }else{
                $consent = true;
            }

        }
        return $consent;
    }

}
