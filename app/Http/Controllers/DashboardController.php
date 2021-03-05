<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Rank;
use App\Repositories\SalesRepository;
use App\Sales;
use App\Services\AccountManagerService;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class DashboardController extends Controller
{

    public $salesRepository;
    public $accountManagement;

    public function __construct(
        SalesRepository $salesRepository,
        AccountManagerService $accountManagerService
    )
    {
        $this->salesRepository = $salesRepository;
        $this->accountManagement = $accountManagerService;
    }

    /**
     * Feb. 09, 2020
     * @author john kevin paunel
     * display dashboard page
     * */
    public function dashboard()
    {
        //set the graph display by day, week, or month
        $display_period = Cookie::get('display_period');
        $period = isset($display_period) ? $display_period : 'week';

        $reminder = \App\Notification::where([
            ['user_id','=',$this->accountManagement->checkIfUserIsAccountManager()->id],
            ['viewed','=',0],
            ['type','=','lead activity'],
        ]);

        $total_leads = Lead::where('user_id',$this->accountManagement->checkIfUserIsAccountManager()->id)->count();
        $total_cold_leads = Lead::where([
            ['user_id','=',auth()->user()->id],
            ['lead_status','=','Cold'],
        ])->count();
        $total_reserved_leads = Lead::where([
            ['user_id','=',$this->accountManagement->checkIfUserIsAccountManager()->id],
            ['lead_status','=','Reserved'],
        ])->count();

        $chart_options = [
            'chart_title' => 'Weekly Leads',
            'report_type' => 'group_by_date',
            'model' => Lead::class,
            'conditions'            => [
                ['name' => 'Total Leads ('.$total_leads.')', 'condition' => 'user_id = "'.$this->accountManagement->checkIfUserIsAccountManager()->id.'"', 'color' => '#d800ff'],
//                ['name' => 'Cold Leads ('.$total_cold_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'" AND lead_status = "Cold"','color' => '#007eff'],
//                ['name' => 'Reserved Leads ('.$total_reserved_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'" AND lead_status = "Reserved"','color' => 'green'],
            ],
            'group_by_field' => 'created_at',
            'group_by_period' => $period,
            'chart_type' => 'line',
        ];
        $leads = new LaravelChart($chart_options);


        $chart_options = [
            'chart_title' => 'Monthly Sales',
            'report_type' => 'group_by_date',
            'model' => Sales::class,
            'conditions'            => [
                ['name' => 'Total Sales', 'condition' => 'user_id = "'.$this->accountManagement->checkIfUserIsAccountManager()->id.'"', 'color' => 'green'],
//                ['name' => 'Cold Leads ('.$total_cold_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'" AND lead_status = "Cold"','color' => '#007eff'],
//                ['name' => 'Reserved Leads ('.$total_reserved_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'" AND lead_status = "Reserved"','color' => 'green'],
            ],

            'aggregate_function' => 'sum',
            'aggregate_field' => 'total_contract_price',

            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'line',
        ];
        $sales = new LaravelChart($chart_options);

        return view('pages.dashboard',compact('leads','sales'))->with([
            'reminders' => $reminder,
            'display_period' => $period,
            'total_sales_this_month' => $this->salesRepository->getTotalSalesThisMonth($this->accountManagement->checkIfUserIsAccountManager()->id),
            'total_sales'   => $this->salesRepository->getTotalSales($this->accountManagement->checkIfUserIsAccountManager()->id),
            'current_month' => now()->format('F'),
            'current_year' => now()->format('Y'),
            'current_balance' => Wallet::where([['user_id','=',$this->accountManagement->checkIfUserIsAccountManager()->id],['status','!=','completed']])->sum('amount'),
            'ranks' => Rank::all(),
        ]);
    }


    public function setDisplayLeadGraphStatus(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Lead Graph Display Changed'])->withCookie(\cookie()->forever('display_period',$request->status));
    }
}
