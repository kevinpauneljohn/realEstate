@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <style type="text/css">
        .process{
            text-align:center;
            font-size:10px;
        }
        .process-section{
            margin-top:20px;
            margin-bottom:20px;
        }
        .payment-icon{
            margin-top:20px;
        }
        .click-icon{
            position:relative;
            float:right;
            top:-60px!important;
            padding:5px;
            border-radius:50px 50px 50px 50px;
            box-shadow: 1px 1px 2px #888888;
            transition: color 0.5s;
        }
        .click-icon:hover{
            color:Red;
        }
    </style>
    <h1>Dashboard</h1>
@stop

@section('content')

    @if(!auth()->user()->hasAnyRole('architect|client'))
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h4>&#8369; {{number_format($total_sales,2)}}</h4>

                            <p>Total sales this year {{$current_year}}</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>&#8369; {{number_format($total_sales_this_month,2)}}</h4>

                            <p>Total sales this month of {{ucfirst($current_month)}}</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-pink">
                        <div class="inner">
                            <h4>&#8369; {{number_format($current_balance,2)}}</h4>

                            <p>Wallet remaining cash</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="float-right text-muted">Lead Monitoring Graph</h5>
                            <span>Display</span>
                            <select class="display-period">
                                <option value="day" @if($display_period === 'day') selected @endif>Daily</option>
                                <option value="week" @if($display_period === 'week') selected @endif>Weekly</option>
                                <option value="month" @if($display_period === 'month') selected @endif>Monthly</option>
                            </select>
                            Leads
                        </div>
                        <div class="card-body display-graph">
                            {!! $leads->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="float-right text-muted">Sales Monitoring Graph</h5>
                        </div>
                        <div class="card-body display-graph">
                            {!! $sales->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-gradient-primary">
                        <div class="card-body">
                            <h6 align="center">Current Rank</h6>
                            <h1 align="center" style="color:#f6ff00;">{{ucfirst(auth()->user()->userRankPoint->rank->name)}}</h1>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('notifications.index')}}" class="btn btn-outline-light btn-sm float-right" data-toggle="modal" data-target="#rank-lists-modal">View Rank Lists</a>
                            <h6>Total Points Earned = {{number_format((auth()->user()->userRankPoint->sales_points + auth()->user()->userRankPoint->extra_points),2)}} pts</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h6>You have <span class="text-danger">{{$reminders->count()}}</span> unread @if($reminders->count() > 1) reminders @else reminder @endif</h6>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        @foreach($reminders->limit(3)->get() as $remind)
                                            <tr>
                                                <td>
                                                    <a href="{{route('leads.show',['lead' => $remind->data->lead_id])}}">
                                                        <div class="media">
                                                            <img src="http://crm.dream-homeseller.com/images/avatar-sm.png" class="user-image img-circle elevation-2" height="40" style="margin:0px 10px 10px 10px;">
                                                            <div class="media-body">
                                                                <h3 class="dropdown-item-title">
                                                                    {{$remind->data->category}} to <span class="text-muted">{{ucfirst($remind->data->client_name)}}</span>
                                                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{$remind->data->time_left}}</p>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" align="center">
                            <a href="{{route('notifications.index')}}" class="btn btn-default btn-sm">View All Reminders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--rank lists modal-->
    <div class="modal fade" id="rank-lists-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rank Lists</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Rank Name</th>
                            <th>Points</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ranks as $rank)
                            <tr>
                                <td>{{ucfirst($rank->name)}}</td>
                                <td><span class="text-primary">{{ucfirst($rank->start_points)}} pts</span> to
                                    <span class="text-primary">{{ucfirst($rank->end_points)}} pts</span></td>
                                <td>{{ucfirst(ucfirst($rank->description))}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--end rank lists modal-->
    @endif

    {{--architects module--}}
    @if(auth()->user()->hasRole('architect'))
        architect dashboard
    @endif

    {{--Client module--}}
    @if(auth()->user()->hasRole('client'))

        <div class="row process-section">
            <div class="col-3 process">
                <img src="{{asset('/images/mobileappicon/document.png')}}" alt="documentation" width="35" height="35">
                <p>Documentation</p>
            </div>
            <div class="col-3 process">
                <img src="{{asset('/images/mobileappicon/renovation.png')}}" alt="construction" width="35" height="35">
                <p>Construction</p>
            </div>
            <div class="col-3 process">
                <img src="{{asset('/images/mobileappicon/document-search.png')}}" alt="inspection" width="35" height="35">
                <p>Inspection</p>
            </div>
            <div class="col-3 process">
                <img src="{{asset('/images/mobileappicon/deal.png')}}" alt="turn over" width="35" height="35">
                <p>Turn-over</p>
            </div>
        </div>

        <h4>My Account</h4>
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <img src="{{asset('/images/mobileappicon/hand.png')}}" class="payment-icon" alt="turn over" width="80" height="80">
                    </div>
                    <div class="col-8">
                        <span class="payment-section">
                            <p class="text-muted">Total Payment</p>
                            <h5 class="text-bold">PHP 1,500,000.00</h5>
                            <p class="text-muted">Last Payment</p>
                            <h6 class="text-bold">Sep 03, 2020</h6>
                        </span>
                        <span class="payment-section">
                            <div class="click-icon">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
@stop

@section('js')
    <script src="{{asset('js/dashboard.js')}}"></script>
    {!! $leads->renderChartJsLibrary() !!}
    {!! $leads->renderJs() !!}
    {!! $sales->renderJs() !!}
@stop
