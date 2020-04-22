<?php

namespace App\Http\Controllers;

use App\Repositories\ThresholdRepository;
use App\Threshold;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RequestController extends Controller
{
    private $thresholdRepository;

    public function __construct(ThresholdRepository $thresholdRepository)
    {
        $this->thresholdRepository = $thresholdRepository;
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
                    $action .= '<button class="btn btn-xs btn-success view-request-btn" id="'.$threshold->id.'" data-toggle="modal" data-target="#view-request-details" title="View"><i class="fa fa-eye"></i></button>';
                }
                if(auth()->user()->can('edit request'))
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-request-btn" id="'.$threshold->id.'" data-toggle="modal" data-target="#edit-request-details" title="Approve"><i class="far fa-thumbs-up"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
