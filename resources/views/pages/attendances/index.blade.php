@extends('adminlte::page')

@section('title', 'Attendance')

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
            <h3>Log</h3>
            <div class="row">
                <div class="col-md-6">
                    <button onclick="disable(this)" class="btn btn-default btn-lg button2"><i class=""></i> Time In</button>
                    <button onclick="disable(this)" class="btn btn-default btn-lg button2"><i class=""></i> Break</button>
                    <button onclick="disable(this)" class="btn btn-default btn-lg button2"><i class=""></i> Back From Break</button>
                    <button onclick="disable(this)" class="btn btn-default btn-lg button2"><i class=""></i> Time Out</button>
                </div>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="rank-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <h3>Your Last Shift</h3>
                            <th width="10%">Date</th>
                            <th width="10%">Time In</th>
                            <th width="10%">Break Time</th>
                            <th width="10%">Back from Break</th>
                            <th width="10%">Time Out</th>
                        </tr>
                        <tr>
                            <td>Chena</td>
                            <td>Chena</td>
                            <td>Chena</td>
                            <td>Chena</td>
                            <td>Chena</td>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="rank-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <h3>Your Next Shift</h3>
                            <th width="10%">Date</th>
                            <th width="10%">Shift</th>
                        </tr>
                        <tr>
                            <td>Chena</td>
                            <td>Chena</td>
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