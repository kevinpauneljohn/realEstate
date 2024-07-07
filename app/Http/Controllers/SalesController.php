<?php

namespace App\Http\Controllers;

use App\Events\UpdateLeadStatusEvent;
use App\Events\UserRankPointsEvent;
use App\Events\DeleteSalesRequestEvent;
use App\Lead;
use App\ModelUnit;
use App\Project;
use App\Repositories\RepositoryInterface\SalesInterface;
use App\Repositories\SalesRepository;
use App\Repositories\ThresholdRepository;
use App\Requirement;
use App\SaleRequirement;
use App\Sales;
use App\Services\AccountManagerService;
use App\Services\DownLineService;
use App\Services\Leaderboard;
use App\Services\PaymentReminderService;
use App\Template;
use App\Threshold;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;
use App\Imports\SalesImport;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    private $thresholdRepository,
        $salesRepository,
        $sales,
        $accountManagement,
        $paymentReminder,
        $downLines,
        $leaderboard;

    public function __construct(
        ThresholdRepository $thresholdRepository,
        SalesRepository $salesRepository,
        SalesInterface $sales,
        AccountManagerService $accountManagerService,
        PaymentReminderService $paymentReminder,
        DownLineService $downLineService,
        Leaderboard $leaderboard
    )
    {
        $this->thresholdRepository = $thresholdRepository;
        $this->salesRepository = $salesRepository;
        $this->sales = $sales;
        $this->accountManagement = $accountManagerService;
        $this->paymentReminder = $paymentReminder;
        $this->downLines = $downLineService;
        $this->leaderboard = $leaderboard;
    }



    public function index(Request $request)
    {
        $start_date = $request->session()->get('start_date');
        $end_date = $request->session()->get('end_date');
        return view('pages.sales.index')->with([
            'leads' => Lead::where('user_id',$this->accountManagement->checkIfUserIsAccountManager()->id)->get(),
            'projects'   => Project::all(),
            'team_units_sold' => $this->salesRepository->getTeamUnitSold((array)$this->accountManagement->checkIfUserIsAccountManager()->id)->count(),
            'team_units_sold_this_month' => $this->salesRepository->getTeamUnitSold((array)$this->accountManagement->checkIfUserIsAccountManager()->id)->whereMonth('reservation_date',now()->format('m'))->count(),
            'personal_units_sold' => $this->salesRepository->getTotalUnitSold((array)$this->accountManagement->checkIfUserIsAccountManager()->id)->count(),
            'personal_units_sold_this_month' => $this->salesRepository->getTotalUnitSold((array)$this->accountManagement->checkIfUserIsAccountManager()->id)->whereMonth('reservation_date',now()->format('m'))->count(),
            'team_sales_this_month' => $this->salesRepository->getTeamSalesThisMonth((array)$this->accountManagement->checkIfUserIsAccountManager()->id),
            'total_team_sales'   => $this->salesRepository->getTeamSales($this->accountManagement->checkIfUserIsAccountManager()->id),
            'personal_sales_this_year' => $this->salesRepository->getTotalPersonalSales($this->accountManagement->checkIfUserIsAccountManager()->id),
            'personal_sales_this_month' => $this->salesRepository->getTotalPersonalSalesThisMonth($this->accountManagement->checkIfUserIsAccountManager()->id),
            'total_cancelled'   => Sales::where('status','cancelled')->count(),
            'templates' => Template::all(),
            'leaderboard' => $this->leaderboard->userRankingBySales($start_date, $end_date)
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
     * Show the form for creating a new resource.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        ///if the user is an online warrior or account manager, it will only get the assigned leads to him
        $leads = auth()->user()->hasRole(['account manager','online warrior'])
            ? Lead::where('online_warrior_id',auth()->user()->id)->get() : Lead::where('user_id',auth()->user()->id)->get();

        return view('pages.sales.addSales')->with([
            'leads' => $leads,
            'projects' => collect(Project::all())->sortBy('name'),
            'leadId' => $request->leadId
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_date'  => 'required',
            'buyer'  => 'required',
            'project'  => 'required',
            'model_unit'  => 'required',
            'total_contract_price'  => 'required|numeric',
            'discount'  => 'numeric',
            'processing_fee'  => 'numeric',
            'reservation_fee'  => 'numeric',
            'equity'  => 'numeric',
            'loanable_amount'  => 'numeric',
            'financing'  => 'required',
        ],[
            'reservation_date.required' => 'Reservation Date is required',
            'total_contract_price.numeric' => 'Total Contract Price must be a whole number',
            'total_contract_price.required' => 'Total Contract Price is required',
            'discount.numeric' => 'Discount must be a whole number',
            'processing_fee.numeric' => 'Processing fee must be a whole number',
            'reservation_fee.numeric' => 'Reservation fee must be a whole number',
            'equity.numeric' => 'Equity must be a whole number',
            'loanable_amount.numeric' => 'Loanable amount must be a whole number',
        ]);

        if($validator->passes())
        {
            /*sales will be save if the commission rate is greater than 0*/
            if($this->salesRepository->setCommissionRate($request->project,$this->accountManagement->checkIfUserIsAccountManager()->id) > 0){
                $sales = new Sales();
                $sales->reservation_date = $request->reservation_date;
                $sales->user_id = $this->accountManagement->checkIfUserIsAccountManager()->id;
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
                $sales->commission_rate = $this->salesRepository->setCommissionRate($request->project,auth()->user()->id);
                $sales->status = 'reserved';

                if($sales->save())
                {
                    //add additional points based on sales price
                    $plusPoint = ($request->total_contract_price - $request->discount)/100000;
                    //$points = auth()->user()->userRankPoint->points + $plusPoint;
                    $points = $this->accountManagement->checkIfUserIsAccountManager()->userRankPoint->sales_points + $plusPoint;

                    ///check if the user has extra points
                    $extra_points = $this->accountManagement->checkIfUserIsAccountManager()->userRankPoint == null ? 0 : $this->accountManagement->checkIfUserIsAccountManager()->userRankPoint->extra_points;

                    event(new UserRankPointsEvent($this->accountManagement->checkIfUserIsAccountManager(), $points, $extra_points));

                    event(new UpdateLeadStatusEvent($sales->lead_id));
                    return response()->json(['success' => true, 'message' => 'Sales successfully added!',
                        'view' => route('leads.show',$sales->lead_id)]);
                }
            }

            /*sales will not be save and will return an error message*/
            return response()->json(['success' => false, 'message' => 'Your commission rate is 0%, Please inform your manager']);
        }
        return response()->json($validator->errors());
    }


    /**
     * March 06, 2020
     * @author john kevin paunel
     * fetch all sales
     * */
    public function salesList(Request $request)
    {
        $userId = $this->accountManagement->checkIfUserIsAccountManager()->id;
        if(is_null($request->session()->get('start_date')))
        {
            $start_date = now()->startOfYear()->format('m/d/Y');
            $end_date = now()->endOfYear()->format('m/d/Y');
            $sales = Sales::whereIn('user_id',collect(collect($this->downLines->extractDownLines((array)$userId)->pluck('id'))->concat((array)$userId))->toArray())->get();
        }else{
            $start_date = $request->session()->get('start_date');
            $end_date = $request->session()->get('end_date');
            $sales = Sales::whereIn('user_id',collect(collect($this->downLines->extractDownLines((array)$userId)->pluck('id'))->concat((array)$userId))->toArray())
                ->whereBetween('reservation_date',[$start_date, $end_date])->get();
        }

        return DataTables::of($sales)
            ->editColumn('reservation_date',function($sale){
                return $sale->reservation_date;
            })
            ->editColumn('total_contract_price',function($sale){
                return number_format($sale->total_contract_price);
            })
            ->addColumn('request_status',function($sale){
                $threshold = Threshold::where([
                    ['storage_name','=','sales'],
                    ['storage_id','=',$sale->id],
                ]);

                return $threshold->count();
            })
            ->addColumn('full_name',function($sale){
                return '<a href="'.route('leads.show',['lead' => $sale->lead->id]).'">'.$sale->lead->fullname.'</a>';
            })
            ->addColumn('project',function($sale){
                return $sale->project->name;
            })
            ->addColumn('model_unit',function($sale){
                return $sale->modelUnit->name;
            })
            ->addColumn('contact_number',function($sale){
                return $sale->lead->mobileNo;
            })
            ->addColumn('email',function($sale){
                return $sale->lead->email;
            })
            ->editColumn('commission_rate',function($sale){
                if($sale->commission_rate != null && !auth()->user()->hasRole('online warrior'))
                {
                    return $sale->commission_rate.'%';
                }
                return "";
            })
            ->editColumn('status',function($sale){
                return $this->statusLabel($sale->status);
            })
            ->editColumn('agent',function($sale){
                return $sale->user->fullname;
            })
            ->addColumn('action', function ($sale)
            {
                //this will get the due date by payment reminder last schedule or reservation date
                $dueDate = collect($sale->paymentReminders)->count() > 0 && $sale->status !== "cancelled" ? $sale->paymentReminders->last()->schedule : collect($this->paymentReminder->scheduleFormatter($sale->id,$sale->reservation_date))->last();
                $action = "";
                if(auth()->user()->can('view sales'))
                {
                    $action .= '<button class="btn btn-xs btn-default view-sales-btn" id="'.$sale->id.'" data-toggle="modal" data-target="#view-sales-details" title="View"><i class="fa fa-eye"></i></button>';
                }
                if((auth()->user()->hasRole(['online warrior']) && $sale->lead->online_warrior_id === auth()->user()->id)
                    || auth()->user()->hasRole(['super admin','account manager','admin','team leader','referral','manager','agent']))
                {
                    if(auth()->user()->can('edit sales')&& $this->accountManagement->checkIfUserIsAccountManager()->id === $sale->user_id)
                    {
                        $action .= '<button class="btn btn-xs btn-default edit-sales-btn" id="'.$sale->id.'" data-target="#edit-sales-modal" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></button>';
                    }
                    if(auth()->user()->hasRole(['super admin']) && $this->accountManagement->checkIfUserIsAccountManager()->id === $sale->user_id)
                    {
                        $action .= '<button class="btn btn-xs btn-default delete-sale-btn" id="'.$sale->id.'" title="Delete"><i class="fa fa-trash"></i></button>';
                    } else if(auth()->user()->can('delete sales') && $this->accountManagement->checkIfUserIsAccountManager()->id === $sale->user_id)
                    {
                        $action .= '<button class="btn btn-xs btn-default delete-request-sale-btn" id="'.$sale->id.'" title="Delete" data-toggle="modal" data-target="#delete-sale-request"><i class="fa fa-trash"></i></button>';
                    }
                    if(auth()->user()->can('edit sales') && $this->accountManagement->checkIfUserIsAccountManager()->id === $sale->user_id)
                    {
                        $action .= '<a href="#" data-status="'.$sale->status.'" class="btn btn-xs btn-default update-sale-status-btn" title="Update Sale Status" data-toggle="modal" data-target="#update-sale-status" id="'.$sale->id.'"><i class="fas fa-thermometer-three-quarters"></i></a>';
                    }
                    if(auth()->user()->can('view request') && $this->accountManagement->checkIfUserIsAccountManager()->id === $sale->user_id)
                    {
                        $action .= '<button class="btn btn-xs btn-default view-request-btn" id="'.$sale->id.'" data-toggle="modal" data-target="#view-request" title="View all requests #"><i class="fa fa-ticket-alt"></i></button>';
                    }

                    if($this->accountManagement->checkIfUserIsAccountManager()->id === $sale->user_id)
                    {
                        $action .= '<a href="'.route('leads.show',['lead' => $sale->lead_id]).'" class="btn btn-xs btn-info view-request-btn" id="'.$sale->id.'" title="Create Client Account"><i class="fa fa-user-alt"></i></a>';
                    }
                }

                if(!auth()->user()->hasRole(['online warrior','account manager','admin','Finance Admin'])
                    && today()->diffInDays($dueDate,false) < 0
                    && $sale->commissionRequests()->where('user_id',auth()->user()->id)->whereIn('status',['pending','for review','requested to developer','for release','completed'])->count() < 1
                    && auth()->user()->can('view commission request') && $sale->status != 'cancelled')
                {
                    $action .= '<button class="btn btn-xs btn-success commission-request-btn" id="request-'.$sale->id.'" title="Commission Request" value="'.$sale->id.'">Request Commission</button>';
                }

                return $action;
            })
            ->setRowClass(function($sale){
                $dueDate = collect($sale->paymentReminders)->count() > 0 && $sale->status !== "cancelled" ? $sale->paymentReminders->last()->schedule : collect($this->paymentReminder->scheduleFormatter($sale->id,$sale->reservation_date))->last();
                $action = "";
                if(!auth()->user()->hasRole(['online warrior','account manager','admin','Finance Admin'])
                    && today()->diffInDays($dueDate,false) < 0
                    && $sale->commissionRequests()->where('user_id',auth()->user()->id)->whereIn('status',['pending','for review','requested to developer','for release','completed'])->count() < 1
                    && auth()->user()->can('view commission request') && $sale->status != 'cancelled')
                {
                    $action .= 'for-commission';
                }elseif (!auth()->user()->hasRole(['online warrior','account manager','admin','Finance Admin'])
                    && today()->diffInDays($dueDate,false) < 0
                    && $sale->commissionRequests()->where('user_id',auth()->user()->id)->whereIn('status',['pending','for review','requested to developer','for release','completed'])->count() > 0
                    && auth()->user()->can('view commission request') && $sale->status != 'cancelled')
                {
                    $action .= 'commission-request-pending';
                }
                return $action;
            })
            ->addColumn('rate_status', function ($sale)
            {
                return $this->StatusSaleRate();
            })
            ->rawColumns(['action','status','request_status','full_name'])
            ->with([
                'total_sales' => number_format($this->salesRepository->getTeamSalesByDateRange(auth()->user()->id, $start_date, $end_date),2),
                'leaderboard' => $this->leaderboard->userRankingBySales($start_date, $end_date)
            ])
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $sales = auth()->user()->hasRole('online warrior') ? Sales::findOrFail($id)->makeHidden(['commission_rate']) : Sales::findOrFail($id);
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
     * @return object
     */
    public function edit($id)
    {
        $sales = collect($this->salesRepository->getSalesById($id));
        $merge = $sales->merge(['modelUnit' => ModelUnit::where('project_id',$this->salesRepository->getSalesById($id)->project_id)->get()]);
        return $merge->all();
    }

    public function editSales($id)
    {
        $sales = $this->salesRepository->getSalesById($id);
        ///if the user is an online warrior or account manager, it will only get the assigned leads to him
        $leads = auth()->user()->hasRole(['account manager','online warrior'])
            ? Lead::where('online_warrior_id',auth()->user()->id)->get() : Lead::where('user_id',auth()->user()->id)->get();

        return view('pages.sales.editSales')->with([
            'leads' => $leads,
            'projects' => Project::all(),
            'modelUnits' => ModelUnit::where('project_id',$sales->project->id)->get(),
            'leadId' => $sales->lead->id,
            'sales'  => $sales
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'edit_reservation_date'  => 'required',
            'edit_buyer'  => 'required',
            'edit_project'  => 'required',
            'edit_model_unit'  => 'required',
            'edit_total_contract_price'  => 'required|numeric',
            'edit_discount'  => 'numeric',
            'edit_processing_fee'  => 'numeric',
            'edit_reservation_fee'  => 'numeric',
            'edit_equity'  => 'numeric',
            'edit_loanable_amount'  => 'numeric',
            'edit_financing'  => 'required',
            'update_reason'  => 'required',
        ],[
            'edit_reservation_date.required' => 'Reservation Date is required',
            'edit_buyer.required' => 'Buyer is required',
            'edit_project.required' => 'Project is required',
            'edit_model_unit.required' => 'Model unit is required',
            'edit_total_contract_price.required' => 'Total Contract Price is required',
            'edit_total_contract_price.numeric' => 'Total Contract Price must be a whole number',
            'edit_discount.numeric' => 'Discount must be a whole number',
            'edit_processing_fee.numeric' => 'Processing fee must be a whole number',
            'edit_reservation_fee.numeric' => 'Reservation fee must be a whole number',
            'edit_equity.numeric' => 'Equity must be a whole number',
            'edit_loanable_amount.numeric' => 'Loanable amount must be a whole number',
            'edit_financing.required' => 'Financing is required',
            'update_reason.required' => 'Reason is required',
        ]);

        if($validator->passes())
        {
            if($this->accountManagement->checkIfUserIsAccountManager()->hasRole('super admin'))
            {
                $sale = $this->salesRepository->updateSales($request, $id);
            }else{

                if($this->salesRepository->get_sales_request_count_in_threshold_for_attribute($id) > 0)
                {
                    return response()->json(['success' => false, 'message' => 'You have a current sales details update request!']);
                }
                $commissionRate = $this->salesRepository->setCommissionRate($request->edit_project,$this->accountManagement->checkIfUserIsAccountManager()->id);
                $priority = $this->thresholdRepository->getThresholdPriority('update sales attribute');

                //this will instantiate the sales attribute to check if there are changes in the model
                $sale = $this->salesRepository->getSalesById($id);
                $sale->reservation_date = $request->edit_reservation_date;
                $sale->lead_id = $request->edit_buyer;
                $sale->project_id = $request->edit_project;
                $sale->model_unit_id = $request->edit_model_unit;
                $sale->lot_area =$request->edit_lot_area;
                $sale->floor_area = $request->edit_floor_area;
                $sale->phase = $request->edit_phase;
                $sale->block = $request->edit_block_number;
                $sale->lot = $request->edit_lot_number;
                $sale->total_contract_price = $request->edit_total_contract_price;
                $sale->discount = $request->edit_discount;
                $sale->processing_fee = $request->edit_processing_fee;
                $sale->reservation_fee = $request->edit_reservation_fee;
                $sale->equity = $request->edit_equity;
                $sale->loanable_amount = $request->edit_loanable_amount;
                $sale->financing = $request->edit_financing;
                $sale->terms = $request->edit_dp_terms;
                $sale->details = $request->edit_details;
                $sale->commission_rate = $commissionRate;

                if($sale->isDirty())
                {
                    //only the changed attribute will be use
                    if($sale->isDirty('reservation_date')){$data['reservation_date'] = $request->edit_reservation_date;}
                    if($sale->isDirty('lead_id')){$data['lead_id'] = $request->edit_buyer;}
                    if($sale->isDirty('project_id')){$data['project_id'] = $request->edit_project;}
                    if($sale->isDirty('model_unit_id')){$data['model_unit_id'] = $request->edit_model_unit;}
                    if($sale->isDirty('lot_area')){$data['lot_area'] = $request->edit_lot_area;}
                    if($sale->isDirty('floor_area')){$data['floor_area'] = $request->edit_floor_area;}
                    if($sale->isDirty('phase')){$data['phase'] = $request->edit_phase;}
                    if($sale->isDirty('block')){$data['block'] = $request->edit_block_number;}
                    if($sale->isDirty('lot')){$data['lot'] = $request->edit_lot_number;}
                    if($sale->isDirty('total_contract_price')){$data['total_contract_price'] = $request->edit_total_contract_price;}
                    if($sale->isDirty('discount')){$data['discount'] = $request->edit_discount;}
                    if($sale->isDirty('processing_fee')){$data['processing_fee'] = $request->edit_processing_fee;}
                    if($sale->isDirty('reservation_fee')){$data['reservation_fee'] = $request->edit_reservation_fee;}
                    if($sale->isDirty('equity')){$data['equity'] = $request->edit_equity;}
                    if($sale->isDirty('loanable_amount')){$data['loanable_amount'] = $request->edit_loanable_amount;}
                    if($sale->isDirty('financing')){$data['financing'] = $request->edit_financing;}
                    if($sale->isDirty('terms')){$data['terms'] = $request->edit_dp_terms;}
                    if($sale->isDirty('details')){$data['details'] = $request->edit_details;}
                    if($sale->isDirty('commission_rate')){$data['commission_rate'] = $commissionRate;}

                    ///this will be use to display the data origin of the request for the $extra_data array
                    $dataComparison = array(
                        'reservation_date' => ($sale->isDirty('reservation_date')) ? $request->edit_reservation_date :"",
                        'lead_id' => ($sale->isDirty('lead_id')) ? Lead::find($request->edit_buyer)->fullname :"",
                        'project_id' => ($sale->isDirty('project_id')) ? Project::find($request->edit_project)->name :"",
                        'model_unit_id' => ($sale->isDirty('model_unit_id'))? ModelUnit::find($request->edit_model_unit)->name:"",
                        'lot_area' => ($sale->isDirty('lot_area')) ? $request->edit_lot_area :"",
                        'floor_area' => ($sale->isDirty('floor_area')) ? $request->edit_floor_area :"",
                        'phase' => ($sale->isDirty('phase')) ? $request->edit_phase :"",
                        'block' => ($sale->isDirty('block')) ? $request->edit_block_number :"",
                        'lot' => ($sale->isDirty('lot')) ? $request->edit_lot_number :"",
                        'total_contract_price' => ($sale->isDirty('total_contract_price')) ? $request->edit_total_contract_price :"",
                        'discount' => ($sale->isDirty('discount')) ? $request->edit_discount :"",
                        'processing_fee' => ($sale->isDirty('processing_fee')) ? $request->edit_processing_fee :"",
                        'reservation_fee' => ($sale->isDirty('reservation_fee')) ? $request->edit_reservation_fee : "",
                        'equity' => ($sale->isDirty('equity')) ? $request->edit_equity :"",
                        'loanable_amount' => ($sale->isDirty('loanable_amount')) ? $request->edit_loanable_amount : "",
                        'financing' => ($sale->isDirty('financing')) ? $request->edit_financing: "",
                        'terms' => ($sale->isDirty('terms')) ? $request->edit_dp_terms :"",
                        'details' => ($sale->isDirty('details')) ? $request->edit_details :"",
                        'commission_rate' => ($sale->isDirty('commission_rate')) ? $commissionRate :"",
                    );
//
                    $extra_data = array(
                        'action' => 'Update the sales attribute',
                        'original_data' => $this->salesRepository->getSalesOriginalData($request->updateSalesId,$request->status,$dataComparison)
                    );


                    //save the sales update request if there are changes in total contract price and discount field
                    if($sale->isDirty('lead_id') || $sale->isDirty('total_contract_price') || $sale->isDirty('discount')
                        || $sale->isDirty('project_id') || $sale->isDirty('model_unit_id') || $sale->isDirty('financing'))
                    {
                        //save the request to the threshold table
                        $this->thresholdRepository->saveThreshold('update',$request->update_reason,$data,$extra_data,
                            'sales',$request->updateSalesId,'pending',$priority);

                        return response()->json(['success' => true, 'message' => 'Request for update sent<br/>Please wait for the admin approvel']);
                    }

                        //update directly to sales table without saving the request to the threshold
                    $sale = $this->salesRepository->updateSales($request, $id);

                }else{
                    return response()->json(['success' => false, 'message' => 'No Changes Occurred!']);
                }
            }
            //return message for the add sales action
            return response()->json($sale);
        }
        return response()->json($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->hasRole('super admin'))
        {
            $sale = Sales::find($id);
            if($sale->delete())
            {
                //update the user ranking and points
                $total_points = $this->salesRepository->getTotalSales($this->accountManagement->checkIfUserIsAccountManager()->id) / 100000;

                ///check if the user has extra points
                $extra_points = $this->accountManagement->checkIfUserIsAccountManager()->userRankPoint == null ? 0 : $this->accountManagement->checkIfUserIsAccountManager()->userRankPoint->extra_points;
                event(new UserRankPointsEvent($this->accountManagement->checkIfUserIsAccountManager(),$total_points,$extra_points));
                return response()->json(['success' => true,'message' => 'Sales successfully deleted']);
            }
        }
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
        return ModelUnit::findOrFail($id);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_requirements_template(Request $request): \Illuminate\Http\JsonResponse
    {
        $sales = Sales::findOrFail($request->salesId);
        $sales->template_id = $request->template;

        return $sales->save() ? response()->json(['success' => true]) : response()->json(['success' => false]);
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
        $user = $this->accountManagement->checkIfUserIsAccountManager();

        $validator = Validator::make($request->all(),[
            'status'    => 'required',
            'reason'    => 'required'
        ]);

        if($validator->passes())
        {
            if(!$user->hasRole('super admin'))
            {
                //will not save new status change request if there is a similar pending request
                if($this->salesRepository->get_sales_request_count_in_threshold($request->updateSaleId) > 0)
                {
                    return response()->json(['success' => false,'message' => 'You have a current change status request!']);
                }

                //get the sales object
                $sale = $this->salesRepository->getSalesById($request->updateSaleId);
                //get the sales current status
                $currentSaleStatus = $sale->status;

                //sent the request first to the threshold table and need's admin or super admin approval before update
                $data = array(
                    'status' => $request->status,
                );

                //extra_data column from threshold table
                $extra_data = array(
                    'action' => 'Update Sale Status from <span style="color:#007bff">'.$currentSaleStatus.'</span> to <span style="color:#007bff">'.$request->status.'</span>',
                    'original_data' => $this->salesRepository->getSalesOriginalData($request->updateSaleId,$request->status)
                );

                //get the request or threshold priority
                $priority = $this->thresholdRepository->getThresholdPriority('update sale status');

                //save the Request to threshold table
                $this->thresholdRepository->saveThreshold('update',$request->reason,$data,$extra_data,'sales',$request->updateSaleId,'pending',$priority);

                return response()->json(['success' => true,'message' => 'Status update request sent! <br/><strong>Please wait for the admin approval</strong>']);
            }

            //update the sale status directly at the sales table
            $sale = Sales::find($request->updateSaleId);
            $sale->status = $request->status;

            if($sale->isDirty('status') && $sale->save()) {
                return response()->json(['success' => true, 'message' => 'Sale Status Successfully Updated!']);
            }
            return response()->json(['success' => false, 'message' => 'No Changes Occurred!']);
        }
        return response()->json($validator->errors());
    }


    public function savePaymentDate(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'payment_date' => 'required|date',
            'payment_amount' => 'required'
        ]);

        if($validation->passes())
        {
            $salesId = $request->input('sales_id');
            $firstPayment = $request->input('payment_date');
            if($saveSchedule = $this->paymentReminder->savePaymentSchedule($salesId, $firstPayment, $request->input('payment_amount')))
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Due date successfully set',
                    'payment' => $saveSchedule,
//                    'payment' => number_format($request->input('payment_amount'),2),
                ]);
            }
        }
        return response()->json($validation->errors());
    }

    public function updateDueAmount(Request $request)
    {
        $data = [];
        if(array_key_exists("amountDue",$request->all()))
        {
            foreach ($request->input('amountDue') as $key => $value)
            {
                $data[$key] = $value;
                $this->paymentReminder->updatePaymentDueAmount($key, $value);
            }
            return response()->json(['success' => true, 'message' => 'Due Amount Successfully Updated!', 'data' => $data]);
        }
        return response()->json(['success' => false, 'message' => 'Error occurred!']);
    }

    public function getSalesDueDate($salesId)
    {
        return $this->paymentReminder->viewSalesReminder($salesId)->get();
    }

    public function paymentSchedule()
    {
        return view('pages.sales.paymentSchedule')->with([
            'paymentReminders' => $this->paymentReminder->viewAllSalesReminderOfCurrentUser($this->accountManagement->checkIfUserIsAccountManager()->id),
        ]);
    }

    public function paymentThisMonth()
    {
        return $this->paymentReminder->paymentRemindersThisMonth($this->accountManagement->checkIfUserIsAccountManager()->id);
    }

    public function delRequest(Request $request, $id)
    {
        $get_request =[
            'sales_id' => $request->deleteSaleId,
            '_token' => $request->_token,
            'user_id' => auth()->user()->id,
            'reason' => $request->reason,
            'action' => 'delete'
        ];
        $result = event(new DeleteSalesRequestEvent($get_request));
        return response()->json(['success' => true,'message' => 'Delete Sales Request successfully submitted<br/><strong>Please wait for the admin approval</strong>']);
    }

    public function importSales()
    {
        Excel::import(new SalesImport,request()->file('file'));

        return back();
    }

    public function hideSaleRate(Request $request): void
    {
        \session(['rate' => $request->input('rate')]);
        \session(['user_rate' => auth()->user()->id]);
    }

    public function StatusSaleRate()
    {
        $rate =\session('rate');
        $user_rate =\session('user_rate');

        $data = '';
        if ($user_rate == auth()->user()->id) {
            $data = $rate;
        }

        return $data;
    }

    public function salesDateRange(Request $request)
    {
        $date = explode('-',$request->date);
        $request->session()->put('start_date',Carbon::parse($date[0]));
        $request->session()->put('end_date',Carbon::parse($date[1]));
    }
}
