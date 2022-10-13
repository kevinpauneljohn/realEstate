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
            <div class="card">
                <article class="card-group-item">
                    <header class="card-header"><h6 class="title">Similar category </h6></header>
                    <div class="filter-content">
                        <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item">Cras justo odio <span class="float-right badge badge-light round">142</span> </a>
                        <a href="#" class="list-group-item">Dapibus ac facilisis  <span class="float-right badge badge-light round">3</span>  </a>
                        <a href="#" class="list-group-item">Morbi leo risus <span class="float-right badge badge-light round">32</span>  </a>
                        <a href="#" class="list-group-item">Another item <span class="float-right badge badge-light round">12</span>  </a>
                        </div>  <!-- list-group .// -->
                    </div>
                </article> <!-- card-group-item.// -->
                <article class="card-group-item">
                    <header class="card-header"><h6 class="title">Color check</h6></header>
                    <div class="filter-content">
                        <div class="card-body">
                            <label class="btn btn-danger">
                            <input class="" type="checkbox" name="myradio" value="">
                            <span class="form-check-label">Red</span>
                            </label>
                            <label class="btn btn-success">
                            <input class="" type="checkbox" name="myradio" value="">
                            <span class="form-check-label">Green</span>
                            </label>
                            <label class="btn btn-primary">
                            <input class="" type="checkbox" name="myradio" value="">
                            <span class="form-check-label">Blue</span>
                            </label>
                        </div> <!-- card-body.// -->
                    </div>
                </article> <!-- card-group-item.// -->
            </div> <!-- card.// -->
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
