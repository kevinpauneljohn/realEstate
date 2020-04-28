<?php

namespace App\Http\Controllers;

use App\Repositories\SalesRepository;
use App\Repositories\ThresholdRepository;
use App\Threshold;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class RequestController extends Controller
{
    private $thresholdRepository,
            $salesRepository;


    public function __construct(ThresholdRepository $thresholdRepository, SalesRepository $salesRepository)
    {
        $this->thresholdRepository = $thresholdRepository;
        $this->salesRepository = $salesRepository;
    }

    public function index()
    {
        return view('pages.thresholds.index');
    }

    /**
     * @since April 12, 2020
     * @author john kevin paunel
     * request datatables
     * */
    public function requestList()
    {
        if(Session::has('statusRequests'))
        {
            $thresholds = $this->thresholdRepository->getThresholdStatus(Session::get('statusRequests'));
        }else{
            $thresholds = $this->thresholdRepository->getAllThreshold();
        }

        return DataTables::of($thresholds)
            ->setRowClass(function ($threshold){
                if($threshold->status === 'approved')
                {
                    return 'approved';
                }
                elseif($threshold->status === 'rejected')
                {
                    return 'rejected';
                }
                else{
                    return $threshold->approved_by === null ? "pending" : "";
                }

            })
            ->editColumn('id',function ($threshold){
                $request = str_pad($threshold->id, 5, '0', STR_PAD_LEFT);
                return '<a href="'.route('requests.show',['request' => $threshold->id]).'"><span style="color:#007bff">'.$request.'</span></a>';
            })
            ->editColumn('description', function ($threshold){
                return $threshold->extra_data->action;
            })
            ->addColumn('request',function($threshold){
                return $threshold->request;
            })
            ->addColumn('recent_time',function($threshold){
                return $threshold->created_at->diffForHumans();
            })
            ->editColumn('user_id', function ($threshold){
                $username = User::find($threshold->user_id)->username;
                return $username;
            })
            ->editColumn('approved_by', function ($threshold){
                $username = "";
                if($threshold->approved_by !== null)
                {
                    $username = User::find($threshold->approved_by)->username;
                }
                return $username;
            })
            ->addColumn('action', function ($threshold)
            {
                $action = "";
                if(auth()->user()->can('view request'))
                {
                    $action .= '<a href="'.route('requests.show',['request' => $threshold->id]).'" class="btn btn-xs btn-success view-request-btn" id="'.$threshold->id.'" title="View"><i class="fa fa-eye"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action','id','description'])
            ->make(true);
    }

    /**
     * @since april 22, 2020
     * @author john kevin paunel
     * fetch the sales data
     * @param int $id
     * @return object
     * */
    public function show($id)
    {
        //return $this->thresholdRepository->getThresholdDetails($id);
        return view('pages.thresholds.view')->with($this->thresholdRepository->getThresholdDetails($id));
    }

    /**
     * @since April 27, 2020
     * @author john kevin paunel
     * approved or reject the user's request
     * @param Request $request
     * @param int $id
     * @return Response
     * */
    public function update(Request $request, $id)
    {
        $this->thresholdRepository->updateThreshold($id,$request->action,auth()->user()->id,null,$request->reason);
        return response()->json(['success' => true, 'message' => 'Request successfully updated!']);
         //return (array)$threshold->data;
    }

    /**
     * @since April 28, 2020
     * @author john kevin paunel
     * set the request display by status
     * @param Request $request
     * @return Response
     * */
    public function setRequestStatus(Request $request)
    {
        if($request->status !== 'all')
        {
            $request->session()->put('statusRequests', $request->status);
        }
        else{
            $request->session()->forget('statusRequests');
        }
        return response()->json(['success' => true]);
    }

    /**
     * @since April 28, 2020
     * @author john kevin paunel
     * get ticket objects
     * @param Request $request
     * @return object
     * */
    public function getRequestNumber(Request $request)
    {
        return $this->thresholdRepository->getAllRequestByStorageId('sales',$request->id);
    }
}
