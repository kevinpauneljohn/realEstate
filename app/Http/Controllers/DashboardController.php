<?php

namespace App\Http\Controllers;

use App\Lead;
use Illuminate\Http\Request;
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
        $reminder = \App\Notification::where([
            ['user_id','=',auth()->user()->id],
            ['viewed','=',0],
            ['type','=','lead activity'],
        ]);

        $chart_options = [
            'chart_title' => 'Weekly Leads',
            'report_type' => 'group_by_date',
            'model' => Lead::class,
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'chart_type' => 'line',
        ];
        $chart1 = new LaravelChart($chart_options);

        return view('pages.dashboard',compact('chart1'))->with([
            'reminders' => $reminder
        ]);
    }
}
