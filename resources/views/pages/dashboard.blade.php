@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
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
        </div>
        <div class="col-lg-4">
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
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
@stop

@section('js')
    <script src="{{asset('js/dashboard.js')}}"></script>
    {!! $leads->renderChartJsLibrary() !!}
    {!! $leads->renderJs() !!}
@stop
