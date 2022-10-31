@extends('adminlte::page')

@section('title', 'TimeSheet')

@section('content_header')

    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Time Sheet</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Time Sheet</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop


@section('content')
    <form action="{{route('search')}}" method="GET">
        @csrf
        <div class="container"> 
            <div class="row">
                <div class="container-fluid">
                    <div class="form-group row">
    
                        <label for="date" class="col-form-label col-sm-2">From</label>
                        <div class="col-sm-2">
                            <input  type="date" id="fromDate"  name="fromDate" class="form-control input-sm" required>
                        </div>
                        
                        <label for="date" class="col-form-label col-sm-2">To</label>
                        <div class="col-sm-3">
                            <input  type="date" id="toDate"  name="toDate" class="form-control input-sm" required>
                        </div>
                        <div class="col-sm-2">
                                <button type="submit" class="btn" name="search" title="Search"><i class="fa fa-search"></i></button>
                        </div>                   
                    </div>
                </div>
            </div>
        </div>
        
    </form>
    

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="user-time" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            
                            <th width='15%'>User Id</th>
                            <th width="15%">Time In</th>
                            <th width="15%">Break Out</th>
                            <th width="15%">Break In</th>
                            <th width="15%">Time Out</th>
                            <th width="10%">Date</th>
                            <th width='10%'>Late(TI)</th>
                            <th width='10%'>Late(BFL)</th>
                            <th width='10%'>Hours Rendered</th>
                           
                        </tr>
                        </thead>
                        @foreach($attendances as $row)
                        <tr>
                            <td>{{$row['user_id']}}</td>
                            <td>{{$row['timein']}}</td>
                            <td>{{$row['breakout']}}</td>
                            <td>{{$row['breakin']}}</td>
                            <td>{{$row['timeout']}}</td>
                            <td>{{$row['created_at']}}</td>
                        </tr>

                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
    
@stop

<!--@section('js')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
    $( function() {
    $( "input[name^=MyDate]" ).datepicker();
    } );
    </script>

@stop

@section('ajax')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
@stop-->


@section('css') 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
    </style>
@stop
