@extends('adminlte::page')

@section('title', 'Attendace')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Attendances</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Attendances</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#create-time-in">time in</button>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#create-break">break</button>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#create-break-out">end break</button>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#create-time-out">time out</button>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="rank-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th></th>
                            <th>Name</th>
                            <th width="38%">Time IN</th>
                            <th width="17%">Break Time</th>
                            <th width="15%">Back from Break</th>
                            <th width="15%">Time Out</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
    </style>
@stop
