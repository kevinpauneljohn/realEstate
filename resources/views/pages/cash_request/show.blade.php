@extends('adminlte::page')

@section('title', 'Cash Request History')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Cash Request History</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Cash Request History</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container" style="max-width: 800px;">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-primary btn-sm" style="margin-bottom: 10px;">Back</a>

        @foreach($amount_withdrawal_request as $amount_requests)
        <div class="card">
            <div class="card-header">
                <span class="float-right">Date Requested: <span class="text-primary">{{$amount_requests->created_at->format('M d, Y h:i a')}}</span></span>
                Status: <span class="text-primary">{{ucfirst($amount_requests->status)}}</span> @if($amount_requests->status !== 'pending') / Date {{ucfirst($amount_requests->status)}}:
                <span class="text-primary">{{$amount_requests->updated_at->format('M d, Y h:i a')}}</span> @endif
            </div>
            <div class="card-body">
                <p>
                    Source: <strong>{{ucfirst($amount_requests->wallet->category)}}</strong><br/>
                    Original Amount: <strong class="text-success">&#8369; {{number_format($amount_requests->original_amount,2)}}</strong><br/>
                    Requested Amount: <strong class="text-primary">&#8369; {{number_format($amount_requests->requested_amount,2)}}</strong><br/>
                    Balance: <strong class="text-danger">&#8369; {{number_format($amount_requests->original_amount - $amount_requests->requested_amount,2)}}</strong><br/>
                </p>
                <h6>Description</h6>
                <p>
                    {{ucfirst($amount_requests->wallet->details->description)}}
                </p>
                <hr/>
                <h6>System Remarks</h6>
                <p>{{ucfirst($amount_requests->remarks)}}</p>
            </div>
        </div>
        @endforeach
    </div>

@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
    </style>
@stop

@section('js')
@stop
