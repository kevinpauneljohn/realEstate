<?php

namespace App\Http\Controllers;

use App\Repositories\SalesRepository;
use App\Repositories\ThresholdRepository;
use App\Threshold;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
        $thresholds = $this->thresholdRepository->getAllThreshold();
        return DataTables::of($thresholds)
            ->addColumn('request',function($threshold){
                return $threshold->request;
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
            ->rawColumns(['action'])
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

    public function update(Request $request, $id)
    {
        $this->thresholdRepository->updateThreshold($id,'approved',auth()->user()->id,null,$request->reason);
        return response()->json(['success' => true, 'message' => 'Request successfully updated!']);
         //return (array)$threshold->data;
    }
}
