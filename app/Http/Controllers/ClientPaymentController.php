<?php

namespace App\Http\Controllers;

use App\ClientPayment;
use App\Repositories\CheckPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClientPaymentController extends Controller
{

    private $check_password;

    public function __construct(CheckPassword $checkPassword)
    {
        $this->check_password = $checkPassword;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date_received'     => 'required|date',
            'amount'            => 'required',
        ]);

        if($validator->passes())
        {
            $client_payment = new ClientPayment();
            $client_payment->client_project_id = $request->dhg_project_id;
            $client_payment->date_received = $request->date_received;
            $client_payment->amount = $request->amount;
            $client_payment->details = $request->description;
            $client_payment->remarks = $request->remarks;

            if($client_payment->save()){
                return response()->json(['success' => true, 'message' => 'Payment successfully added!']);
            }
            return response()->json(['success' => false, 'message'],201);

        }

        return response()->json($validator->errors());
    }

    public function update(Request $request, $id)
    {

    }

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
            ->editColumn('date_received', function ($client_payment){
                return $client_payment->date_received->format('M d, Y');
            })
            ->editColumn('amount', function ($client_payment){
                return number_format($client_payment->amount,2);
            })
            ->addColumn('action', function ($client_payment)
            {
                $action = "";
                if(auth()->user()->can('view dhg project'))
                {
                    $action .= '<a href="'.route("dhg.project.show",["project" => $client_payment->id]).'" class="btn btn-xs btn-success view-details" id="'.$client_payment->id.'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('edit dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$client_payment->id.'" data-toggle="modal" data-target="#check-admin-credential-modal" title="Edit Project"><i class="fa fa-edit"></i></a>';
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

    /**
     * Dec. 26, 2020
     * @author john kevin paunel
     * this will call the edit payment modal after the admin credential passes
     * */
    public function paymentModal($id)
    {
        $client_payment = ClientPayment::findOrFail($id);
        return view('layouts.paymentModal')->with([
            'client_payment' => $client_payment,
            'date'  => $client_payment->date_received->format('Y-m-d')
        ]);
    }

    //check admin credential before updating the client payment
    public function adminCredential(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'password' => 'required',
        ]);

        if($validation->passes())
        {
            return response()->json($this->check_password->checkPassword(auth()->user()->username, $request->password));
        }
        return response()->json($validation->errors());
    }
}
