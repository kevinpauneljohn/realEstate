@extends('adminlte::page')

@section('title', 'Request')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Request</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Request</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            Select Status to Display: <select name="request_status" id="request-status" class="select2" style="min-width: 200px;">
                <option value="all"> -- All -- </option>
                <option value="pending" @if(session('statusRequests') == 'pending') selected @endif>Pending</option>
                <option value="approved" @if(session('statusRequests') == 'approved') selected @endif>Approved</option>
                <option value="rejected" @if(session('statusRequests') == 'rejected') selected @endif>Rejected</option>
            </select>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="threshold-list" class="table table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Request #</th>
                        <th>Date Requested</th>
                        <th>How Recently</th>
                        <th>Requested By</th>
                        <th>Type</th>
                        <th>Request Action</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Days Left</th>
                        <th>Assessed By</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Ticker #</th>
                        <th>Date Requested</th>
                        <th>How Recently</th>
                        <th>Requested By</th>
                        <th>Type</th>
                        <th>Request Action</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Days Left</th>
                        <th>Assessed By</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .data-value
        {
            color: #1d68a7;
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('js/validation.js')}}"></script>
    <script src="{{asset('js/request.js')}}"></script>
    @can('view request')
        <script>
            $(function() {
                $('#threshold-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('thresholds.list') !!}',
                    columns: [
                        { data: 'id', name: 'id'},
                        { data: 'created_at', name: 'created_at'},
                        { data: 'recent_time', name: 'recent_time'},
                        { data: 'user_id', name: 'user_id'},
                        { data: 'request', name: 'request'},
                        { data: 'description', name: 'description'},
                        { data: 'status', name: 'status'},
                        { data: 'priority_id', name: 'priority_id'},
                        { data: 'daysLeft', name: 'daysLeft'},
                        { data: 'approved_by', name: 'approved_by'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
    @endcan
@stop
