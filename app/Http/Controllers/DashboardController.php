<?php

namespace App\Http\Controllers;

use App\Lead;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class DashboardController extends Controller
{

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
            ['user_id','=',auth()->user()->id],
            ['viewed','=',0],
            ['type','=','lead activity'],
        ]);

        $total_leads = Lead::where('user_id',auth()->user()->id)->count();
        $total_cold_leads = Lead::where([
            ['user_id','=',auth()->user()->id],
            ['lead_status','=','Cold'],
        ])->count();
        $total_reserved_leads = Lead::where([
            ['user_id','=',auth()->user()->id],
            ['lead_status','=','Reserved'],
        ])->count();

        $chart_options = [
            'chart_title' => 'Weekly Leads',
            'report_type' => 'group_by_date',
            'model' => Lead::class,
            'conditions'            => [
                ['name' => 'Total Leads ('.$total_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'"', 'color' => '#d800ff'],
//                ['name' => 'Cold Leads ('.$total_cold_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'" AND lead_status = "Cold"','color' => '#007eff'],
//                ['name' => 'Reserved Leads ('.$total_reserved_leads.')', 'condition' => 'user_id = "'.auth()->user()->id.'" AND lead_status = "Reserved"','color' => 'green'],
            ],
            'group_by_field' => 'created_at',
            'group_by_period' => $period,
            'chart_type' => 'line',
        ];
        $leads = new LaravelChart($chart_options);

        $chart2 = new LaravelChart($chart_options);

        return view('pages.dashboard',compact('leads'))->with([
            'reminders' => $reminder,
            'display_period' => $period
        ]);
    }


    public function setDisplayLeadGraphStatus(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Lead Graph Display Changed'])->withCookie(\cookie()->forever('display_period',$request->status));
    }
}
