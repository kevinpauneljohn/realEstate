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
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="threshold-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date Requested</th>
                        <th>Requested By</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Date Requested</th>
                        <th>Requested By</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('view request')
        <!--view request modal-->
        <div class="modal fade" id="view-request-details">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Request Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="post">
                                        <div class="user-block">
                                            <img class="img-circle img-bordered-sm" src="{{asset('images/avatar.png')}}" alt="user image">
                                            <span class="username"></span>
                                            <span class="description role">Role: </span>
                                        </div>
                                        <!-- /.user-block -->
                                        <div class="post">
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-muted">Reason</span>
                                                    <span class="info-box-number text-muted mb-0 reason"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-sm ">
                            <tr>
                                <td>Request Status</td>
                                <td id="request-status"></td>
                            </tr>
                            <tr>
                                <td>Request Type</td>
                                <td id="request-type"></td>
                            </tr>
                            <tr>
                                <td>Priority Level</td>
                                <td id="priority"></td>
                            </tr>
                        </table>
                        <table class="table table-sm request-data"></table>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
        <!--view request modal-->
    @endcan
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
    @can('view sales')
        <script>
            $(function() {
                $('#threshold-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('thresholds.list') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'user_id', name: 'user_id'},
                        { data: 'request', name: 'request'},
                        { data: 'description', name: 'description'},
                        { data: 'status', name: 'status'},
                        { data: 'approved_by', name: 'approved_by'},
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
