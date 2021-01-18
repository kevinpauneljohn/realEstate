<?php

namespace App\Http\Controllers;

use App\ClientPayment;
use App\Repositories\RepositoryInterface\CheckCredentialInterface;
use App\Repositories\RepositoryInterface\PaymentInterFace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClientPaymentController extends Controller
{

    private $credential,
            $payment,
            $request;

    public function __construct(
        CheckCredentialInterface $checkCredential,
        PaymentInterFace $paymentInterFace,
        Request $request
    )
    {
        $this->credential = $checkCredential;
        $this->payment = $paymentInterFace;
        $this->request = $request;
    }


    private function validation($request)
    {
        $validator = Validator::make($request->all(),[
            'date_received'     => 'required|date',
            'amount'            => 'required',
        ]);
        return $validator;
    }

    public function store(Request $request)
    {
        return $this->payment->create($request->all());
    }

    public function update(Request $request, $id)
    {
        if($this->isAuthenticated() === true)
        {
            return $this->payment->updateById($this->request->all(), $id);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized access'],401);
    }

    public function destroy($id)
    {
        return $this->payment->removeById($id);
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
        $client_payments = $this->payment->viewAll($id);
        return DataTables::of($client_payments)
            ->editColumn('date_received', function ($client_payment){
                return date('Y-m-d', strtotime($client_payment['date_received']));;
            })
            ->editColumn('amount', function ($client_payment){
                return number_format($client_payment['amount'],2);
            })
            ->addColumn('action', function ($client_payment)
            {
                $action = "";
                if(auth()->user()->can('view dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-success view-details" id="'.$client_payment['id'].'" title="View Details"><i class="fa fa-eye"></i> </a>';
                }
                if(auth()->user()->can('edit dhg project'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" id="'.$client_payment['id'].'" data-toggle="modal" data-target="#edit-payment-modal" title="Edit Project"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete dhg project'))
                {
                    $action .= '<button type="button" class="btn btn-xs btn-danger delete-payment-btn" id="'.$client_payment['id'].'" title="Delete Project"><i class="fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['id','user_id','action'])
            ->make(true);
    }

    public function isAuthenticated()
    {
        return $this->credential->checkPassword(auth()->user()->username, $this->request->password);
    }

    /**
     * Dec. 26, 2020
     * @author john kevin paunel
     * this will call the edit payment modal after the admin credential passes
     * @param int $id
     * @return mixed
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
            $path = base_path() . '/resources/views/pages/clients/edit.blade.php';
            return response()->json([$this->credential->checkPassword(auth()->user()->username, $request->password,''),
                'test' => \Illuminate\Support\Facades\File::get($path)]);
        }
        return response()->json($validation->errors());
    }

    public function edit($id)
    {
        return $this->payment->viewById($id);
    }

    public function checkCredentialForDelete()
    {
        if($this->isAuthenticated() === true)
        {
            return response()->json(['success' => true, 'access' => $this->request->password],201);
        }
        return response()->json(['success' => false],401);
    }

}
