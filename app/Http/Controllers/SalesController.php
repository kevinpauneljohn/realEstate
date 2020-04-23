<?php

namespace App\Http\Controllers;

use App\Lead;
use App\ModelUnit;
use App\Project;
use App\Repositories\SalesRepository;
use App\Repositories\ThresholdRepository;
use App\Requirement;
use App\SaleRequirement;
use App\Sales;
use App\Template;
use App\Threshold;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class SalesController extends Controller
{
    private $thresholdRepository, $salesRepository;

    public function __construct(ThresholdRepository $thresholdRepository, SalesRepository $salesRepository)
    {
        $this->thresholdRepository = $thresholdRepository;
        $this->salesRepository = $salesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('pages.sales.index')->with([
            'leads' => Lead::where('user_id',auth()->user()->id)->get(),
            'projects'   => Project::all(),
            'total_units_sold' => User::findOrFail(auth()->user()->id)->sales()->where('status','!=','cancelled')->count(),
            'total_sales'   => $this->getTotalSales(auth()->user()->id),
            'templates' => Template::all(),
        ]);
    }

    /**
     * April 10, 2020
     * @author john kevin paunel
     * sales requirements page
     * @param integer $sales_id
     * @return mixed
     * */
    public function requirements($sales_id)
    {
        $sales = Sales::findOrFail($sales_id);
        $requirements = ($sales->template_id != null) ? Requirement::where('template_id',$sales->template_id)->get() : null;
        return view('pages.sales.requirements')->with([
            'sales'         => $sales,
            'lead'          => $sales->lead,
            'project'       => $sales->project,
            'modelUnit'     => $sales->modelUnit,
            'templates'     => Template::all(),
            'requirements'  => $requirements,
            'lists'          => SaleRequirement::where('sale_id',$sales_id),
        ]);
    }

    /**
     * March 31, 2020
     * @author john kevin paunel
     * get the user total sales
     * @param string $user_id
     * @return string
     * */
    public function getTotalSales($user_id)
    {
        $sales = User::findOrFail($user_id)->sales()->where('status','!=','cancelled')->get();/*get all the user's sales*/
        $total_sales = 0;/*initiate the total sales by 0*/

        /*add all sales total contract price less the discount*/
        foreach ($sales as $sale)
        {
            $difference = $sale->total_contract_price - $sale->discount;
            $total_sales = $total_sales + $difference;
        }
        return number_format($total_sales);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_date'  => 'required',
            'buyer'  => 'required',
            'project'  => 'required',
            'model_unit'  => 'required',
            'total_contract_price'  => 'required',
            'financing'  => 'required',
        ],[
            'reservation_date.required' => 'Reservation Date is required'
        ]);

        if($validator->passes())
        {
            /*sales will be save if the commission rate is greater than 0*/
            if($this->setCommissionRate($request->project) > 0){
                $sales = new Sales();
                $sales->reservation_date = $request->reservation_date;
                $sales->user_id = auth()->user()->id;
                $sales->lead_id = $request->buyer;
                $sales->project_id = $request->project;
                $sales->model_unit_id = $request->model_unit;
                $sales->lot_area = $request->lot_area;
                $sales->floor_area = $request->floor_area;
                $sales->phase = $request->phase;
                $sales->block = $request->block_number;
                $sales->lot = $request->lot_number;
                $sales->total_contract_price = $request->total_contract_price;
                $sales->discount = $request->discount;
                $sales->processing_fee = $request->processing_fee;
                $sales->reservation_fee = $request->reservation_fee;
                $sales->equity = $request->equity;
                $sales->loanable_amount = $request->loanable_amount;
                $sales->financing = $request->financing;
                $sales->terms = $request->dp_terms;
                $sales->details = $request->details;
                $sales->commission_rate = $this->setCommissionRate($request->project);
                $sales->status = 'reserved';

                if($sales->save())
                {
                    return response()->json(['success' => true]);
                }
            }

            /*sales will not be save and will return an error message*/
            return response()->json(['success' => false, 'message' => 'Your commission rate is 0%, Please inform your manager']);
        }
        return response()->json($validator->errors());
    }

    /**
     * March 24, 2020
     * @author john kevin paunel
     * get the upline IDs
     * @param string $user_id
     * @return string
     * */
    public function getUpLineIds($user_id)
    {
//        $user_id = $user_id;
        $user = User::find($user_id);
        return $user->upline_id;
    }

    /**
     * @author john kevin paunel
     * set the agents commission rate
     * algorithm for getting the commission rate
     * returns the user's current sales commission rate
     * @param int $project_id
     * @return mixed
     * */
    public function setCommissionRate($project_id)
    {
        $user = auth()->user()->id;/*set the id of the current user*/
        $upLines = array(); /*instantiate the up line ids */
        $ctr = 1; /*array counter*/

        #this will loop until it gets all the user's up line IDs

        $upLines[$user] = 0;/*initialize the up line value to 0*/
        while($this->getUpLineIds($user) != null)
        {
            $user = $this->getUpLineIds($user);/*set the new user id*/
            $upLines[$user] = $ctr;/*set the user key value use for arranging the user by position or rank*/
            $ctr++;
        }


        $project_rate = Project::find($project_id); /*get the project rate*/
        $rate = $project_rate->commission_rate; /*instantiate the project rate*/

        arsort($upLines);/*this will arrange the Ids in descending order*/
        foreach ($upLines as $key => $value)
        {
            $user = User::find($key);

            if(!$user->hasRole('super admin'))
            {
                /*this will check if the project id is available on users commissions table project id column*/
                if($user->commissions()->where('project_id','=',$project_id)->count() > 0)
                {
                    /*if the commission was set to a specific project and it matches the sales project id*/
                    $user_rate = $user->commissions()->where('project_id','=',1)->first()->commission_rate;
                }else{
                    $user_rate =  $user->commissions()->where('project_id','=',null)->first()->commission_rate;/*get the user commission rate*/
                }

                /*this conditional statement will be used if the commission rate offers by the project is lower
                or equal to the user's commission rate*/
                if($user_rate >= $rate)
                {
                    $rate = $rate - 1;
                }elseif($user_rate <= 0){
                    $rate = 0; /* commission rate is zero and will return an error message to the user*/
                }else{
                    $rate = $user_rate;
                }
            }
        }
        return $rate;

    }


    /**
     * March 06, 2020
     * @author john kevin paunel
     * fetch all sales
     * */
    public function sales_list()
    {
        $sales = Sales::where('user_id',auth()->user()->id)->get();
        return DataTables::of($sales)
            ->editColumn('total_contract_price',function($sale){
                return number_format($sale->total_contract_price);
            })
            ->editColumn('discount',function($sale){
                return number_format($sale->discount);
            })
            ->addColumn('request_status',function($sale){
                $threshold = Threshold::where([
                    ['storage_name','=','sales'],
                    ['data->id','=',$sale->id],
                ])->first();

                return $threshold != null ? $this->statusLabel($threshold->status) : '';
            })
            ->addColumn('full_name',function($sale){
                $lead = Lead::find($sale->lead_id);
                $firstname = $lead->firstname;
                $lastname = $lead->lastname;

                return ucfirst($firstname).' '.ucfirst($lastname);
            })
            ->addColumn('project',function($sale){
                $project = Project::find($sale->project_id);

                return $project->name;
            })
            ->addColumn('model_unit',function($sale){
                $modelUnit = ModelUnit::find($sale->model_unit_id);

                return $modelUnit->name;
            })
            ->addColumn('contact_number',function($sale){
                return Lead::find($sale->lead_id)->mobileNo;
            })
            ->addColumn('email',function($sale){
                return Lead::find($sale->lead_id)->email;
            })
            ->editColumn('commission_rate',function($sale){
                if($sale->commission_rate != null)
                {
                    return $sale->commission_rate.'%';
                }
                return "";
            })
            ->editColumn('status',function($sale){
                return $this->statusLabel($sale->status);
            })
            ->addColumn('action', function ($sale)
            {
                $action = "";
                if(auth()->user()->can('view sales'))
                {
                    $action .= '<button class="btn btn-xs btn-success view-sales-btn" id="'.$sale->id.'" data-toggle="modal" data-target="#view-sales-details" title="View"><i class="fa fa-eye"></i></button>';
                }
                if(auth()->user()->can('edit sales'))
                {
                    $action .= '<a href="'.route("leads.edit",["lead" => $sale->id]).'" class="btn btn-xs btn-primary view-btn" id="'.$sale->id.'" title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete sales'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-lead-btn" id="'.$sale->id.'" data-toggle="modal" data-target="#delete-lead-modal" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                if(auth()->user()->can('upload requirements'))
                {
                    $action .= '<a href="'.route('sales.upload.requirements',['sale' => $sale->id]).'" class="btn btn-xs btn-info" title="Upload Requirements"><i class="fas fa-file-upload"></i></a>';
                }
                if(auth()->user()->can('edit sales'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-warning update-sale-status-btn" title="Update Sale Status" data-toggle="modal" data-target="#update-sale-status" id="'.$sale->id.'"><i class="fas fa-thermometer-three-quarters"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action','status','request_status'])
            ->make(true);
    }

    /**
     * April 12, 2020
     * @author john kevin paunel
     * set sale status label
     * @param string $status
     * @return mixed
     * */
    public function statusLabel($status)
    {
        switch ($status){
            case "reserved" :
                return '<span class="badge badge-info right role-badge">Reserved</span>';
                break;
            case "cancelled" :
                return '<span class="badge badge-danger right role-badge">Cancelled</span>';
                break;
            case "paid" :
                return '<span class="badge badge-success right role-badge">Paid</span>';
                break;
            case "pending" :
                return '<span class="badge badge-warning right role-badge">Pending</span>';
                break;
            case "reject" :
                return '<span class="badge badge-danger right role-badge">Rejected</span>';
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sales = Sales::findOrFail($id);
        $lead = Lead::find($sales->lead_id);
        $project = Project::find($sales->project_id);
        $model_unit = ModelUnit::find($sales->model_unit_id);
        return response()->json([
            'sales' => $sales,
            'leads' => $lead,
            'project' => $project,
            'model_unit' => $model_unit,
//            'requirements' => $this->getRequirements($sales->project_id, $sales->financing),
        ]);
    }

    /**
     * March 05, 2020
     * @author john kevin paunel
     * get the sales requirement
     * @param int $project_id
     * @param string $financing_type
     * @return array
     * */
    public function getRequirements($project_id, $financing_type)
    {
        $requirements = Requirement::all(); /*get all the requirements template*/
        $document = array(); /*instantiate for storing array*/
        $ctr = 0;
        foreach ($requirements as $requirement)
        {
            /*get the project id's of a specific requirement row*/
            foreach (json_decode($requirement->project_id) as $val)
            {
                if($val == $project_id && $requirement->type == $financing_type)
                {
                    /*if the sales project id and financing type matches the requirement row details returns true*/
                    $document[$ctr] = Requirement::where([
                        ['id','=',$requirement->id],
                        ['type','=','HDMF'],
                    ])->get();
                    $ctr++;
                }
            }
        }
        return $document;
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
     * April 05, 2020
     * @author john kevin paunel
     * get the details of model unit
     * @param int $id
     * @return mixed
     * */
    public function model_unit_details($id)
    {
        $model_unit = ModelUnit::findOrFail($id);
        return $model_unit;
    }

    /**
     * April 08, 2020
     * @author john kevin paunel
     * get all the requirements by ID
     * @param int $template_id
     * @return object
     * */
    public function getRequirementsByTemplate($template_id)
    {
        $template = Template::findOrFail($template_id);
        return response()->json(['template' => $template, 'requirements' => $template->requirements]);
    }

    /**
     * April 10, 2020
     * @author john kevin paunel
     * save the requirements of template to sales table
     * @param Request $request
     * @return Response
     * */
    public function save_requirements_template(Request $request)
    {
        $sales = Sales::findOrFail($request->salesId);
        $sales->template_id = $request->template;

        if($sales->save())
        {
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
        }
    }

    /**
     * April 10, 2020
     * @author john kevin paunel
     * upload requirements
     * @param Request $request
     * @return mixed
     * */
    public function upload_requirements(Request $request)
    {
        $id = $request->requirementId;
        $validator = Validator::make($request->all(),[
            'requirement_'.$id => ['required','image:image:jpeg,png','max:1999']
        ],[
            'requirement_'.$id.'.required'  => 'Requirement Image is required',
            'requirement_'.$id.'.image'  => 'Requirement must be an image file',
        ]);

        if($validator->passes())
        {
            $image = $request->file('requirement_'.$id);
            $image_name = time().'.'.$image->getClientOriginalExtension();

            //instantiate the sales requirements variable
            $salesRequirements = new SaleRequirement();
            $salesRequirements->sale_id = $request->saleId;
            $salesRequirements->requirement_id = $id;
            $salesRequirements->image = $image_name;

            if($salesRequirements->save())
            {
                //resize the image for thumbnail purpose and save it to thumbnail folder
                $destinationPath = public_path('/thumbnail');

                $resize_image = Image::make($image->getRealPath());
                $resize_image->resize(150,150, function ($constraint){
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$image_name);

                //save the image to images folder
                $destinationPath = public_path('/images');
                $image->move($destinationPath,$image_name);

                return back()->with(['success' => true]);
            }
        }
        return back()->withErrors($validator->errors())->withInput();
    }

    /**
     * April 12, 2020
     * @author john kevin paunel
     * update the sale status
     * @param Request $request
     * @return mixed
     * */
    public function updateSaleStatus(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(),[
            'status'    => 'required',
            'reason'    => 'required'
        ]);

        if($validator->passes())
        {
            if(!$user->hasRole('super admin'))
            {
                //get the request or threshold priority
                $priority = $this->thresholdRepository->getThresholdPriority('update sale status');

                //get the sales object
                $sale = $this->salesRepository->getSalesById($request->updateSaleId);
                //get the sales current status
                $currentSaleStatus = $sale->status;

                //sent the request first to the threshold table and need's admin or super admin approval before update
                $data = array(
                    'status' => $request->status,
                    'action' => 'Update Sale Status from <span style="color:#007bff">'.$currentSaleStatus.'</span> to <span style="color:#007bff">'.$request->status.'</span>',
                    'original_data' => $this->salesRepository->getSalesOriginalData($request->updateSaleId)
                );

                //save the Request to threshold table
                $this->thresholdRepository->saveThreshold('update',$request->reason,$data,'sales','pending',$priority);

                return response()->json(['success' => true]);
            }else{
                //update the sale status directly at the sales table
                $sale = Sales::find($request->updateSaleId);
                $sale->status = $request->status;

                if($sale->isDirty('status'))
                {
                    if($sale->save())
                    {
                        return response()->json(['success' => true, 'message' => 'Sale Status Successfully Updated!']);
                    }
                }
                return response()->json(['success' => false, 'message' => 'No Changes Occurred!']);

            }
        }
        return response()->json($validator->errors());
    }
}
