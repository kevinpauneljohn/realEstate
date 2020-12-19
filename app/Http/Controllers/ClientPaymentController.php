<?php

namespace App\Http\Controllers;

use App\ClientPayment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientPaymentController extends Controller
{

    /**
     * Dec. 12, 2020
     * @author john kevin paunel
     * Display the client payment history by project id
     * @param int $id
     * @return mixed
     * */
    public function clientPaymentList($id)
    {
        $client_payments = ClientPayment::where('client_project_id',$id)->get();
        return DataTables::of($client_payments)
            ->addColumn('action', function ($client_payment)
            {
                $action = "";
                if(auth()->user()->can('view dhg project'))
                {
                    $action .= '<a href="'.route("dhg.project.show",["project" => $client_payment->id]).'" class="btn btn-xs btn-success view-details" id="'.$client_payment->id.'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('edit dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$client_payment->id.'" data-toggle="modal" data-target="#edit-project-modal" title="Edit Project"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" id="'.$client_payment->id.'" title="Delete Project"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['id','user_id','action'])
            ->make(true);
    }
}
