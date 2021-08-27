@extends('adminlte::page')

@section('title', 'Commission Requests')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Commission Requests</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Commission Requests</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">


            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="commission-request-list" class="table table-bordered table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Date Requested</th>
                            <th>Project</th>
                            <th>Client</th>
                            <th>TCP</th>
                            <th>Discount</th>
                            <th>Agent</th>
                            <th>Up Line</th>
                            <th>Comm. Rate</th>
                            <th>Rate Requested</th>
                            <th>Last Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Date Requested</th>
                            <th>Project</th>
                            <th>Client</th>
                            <th>TCP</th>
                            <th>Discount</th>
                            <th>Agent</th>
                            <th>Up Line</th>
                            <th>Comm. Rate</th>
                            <th>Rate Requested</th>
                            <th>Last Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
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
        @can('view commission request')
            <script>
                $(function() {
                    $('#commission-request-list').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{!! route('commission.request.approval.get') !!}',
                        columns: [
                            { data: 'dateRequested', name: 'dateRequested'},
                            { data: 'project', name: 'project'},
                            { data: 'client', name: 'client'},
                            { data: 'tcp', name: 'tcp'},
                            { data: 'discount', name: 'discount'},
                            { data: 'agent', name: 'agent'},
                            { data: 'upLine', name: 'upLine'},
                            { data: 'rate', name: 'rate'},
                            { data: 'rateRequested', name: 'rateRequested'},
                            { data: 'lastDueDate', name: 'lastDueDate'},
                            { data: 'status', name: 'status'},
                            { data: 'action', name: 'action', orderable: false, searchable: false}
                        ],
                        responsive:true,
                        order:[0,'asc']
                    });
                });
                //Initialize Select2 Elements
                $('.select2').select2();
            </script>
        @endcan
@stop
