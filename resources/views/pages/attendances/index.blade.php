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
                    <button  class="btn btn-default btn-lg" id="btnTimeIn" data-timein="<?php echo date('Y-m-d H:i:s')?>">Time In</button>
                    <button  class="btn btn-default btn-lg" id="btnBreak" data-break="<?php echo date('Y-m-d H:i:s')?>"> Break</button>
                    <button  class="btn btn-default btn-lg" id="btnBFB" data-bfb="<?php echo date('Y-m-d H:i:s')?>"> Back From Break</button>
                    <button  class="btn btn-default btn-lg" id="btnTimeOut" data-timeout="<?php echo date('Y-m-d H:i:s')?>"> Time Out</button>
                </div>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="rank-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <h3>Your Last Shift</h3>
                            <th width="10%">Time In</th>
                            <th width="10%">Break Time</th>
                            <th width="10%">Back from Break</th>
                            <th width="10%">Time Out</th>
                        </tr>
                        @foreach ($users as $user)
                            <tr>
                            <td>{{ $user->timein }}</td>
                            <td>{{ $user->breakout }}</td>
                            <td>{{ $user->breakin }}</td>
                            <td>{{ $user->timeout }}</td>
                            </tr>
                        @endforeach
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="rank-list" class="table table-bordered table-striped" role="grid">
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

@section('js')
<script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{asset('js/custom-alert.js')}}"></script>
<script src="{{asset('js/validation.js')}}"></script>
<script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function(){
        $(document).on('click','#btnTimeIn',function(){
            var timeIn = $(this).data("timein");
            console.log(timeIn); 
        $.ajax({
            type: "PUT",
            url: '/attendances',
            success: function(data){
                console.log(data);
            }
        }); 
        }); 
        });
    $(function(){
        $(document).on('click','#btnBreak',function(){
            var BREAK = $(this).data("break");
            console.log(BREAK); 
        $.ajax({
            type: "PUT",
            url: '/attendances',
            success: function(data){
                console.log(data);
            }
        }); 
        });
        });
    $(function(){
        $(document).on('click','#btnBFB',function(){
            var BFB = $(this).data("bfb");
            console.log(BFB); 
        $.ajax({
            type: "PUT",
            url: '/attendances',
            success: function(data){
                console.log(data);
            }
        }); 
        });
        });      
    $(function(){
        $(document).on('click','#btnTimeOut',function(){
            var timeOut = $(this).data("timeout");
            console.log(timeOut); 
        $.ajax({
            type: "PUT",
            url: '/attendances',
            success: function(data){
                console.log(data);
            }
        }); 
        });
        }); 
 
</script>
@stop