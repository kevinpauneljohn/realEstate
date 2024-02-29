@extends('adminlte::page')

@section('title', 'Request Details')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Request Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('thresholds.index')}}">Request</a></li>
                <li class="breadcrumb-item active">Request Details</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Request Status</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{ucfirst($status)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Request Type</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{ucfirst($type)}} {{ucfirst($storage_name)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Priority</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{$priority['name']}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4>Requested By:</h4>
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="{{asset('images/avatar.png')}}" alt="user image">
                                    <span class="username">
                          <a href="#">{{$user['firstname']}} {{$user['lastname']}}</a>
                        </span>
                                    <span class="description">Role:
                                        @foreach($user['roles'] as $role)
                                            {{$role['name']}}
                                        @endforeach
                                    </span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    <strong>Request Action: </strong>{!! $extra_data->action !!}
                                </p>
                                <p>
                                    <strong>Reason: </strong>{{$description}}
                                </p>
                            </div>
                            <div class="post">
                                <h4>Data Origin</h4>
                                {!! $extra_data->original_data !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <h3 class="text-primary"><i class="fas fa-user-tie"></i> User Details</h3>

                    <div class="text-muted">

                        <p class="text-sm">Username
                            <b class="d-block">{{$user['username']}}</b>
                        </p>
                        <p class="text-sm">Mobile No.
                            <b class="d-block">{{$user['mobileNo']}}</b>
                        </p>
                        <p class="text-sm">Email
                            <b class="d-block">{{$user['email']}}</b>
                        </p>
                        <p class="text-sm">Date Of Birth
                            <b class="d-block">{{$user['date_of_birth']}}</b>
                        </p>
                        <p class="text-sm">Address
                            <b class="d-block">{{$user['address']}}</b>
                        </p>
                        <p class="text-sm">Up Line
                            <b class="d-block"><a href="{{route('users.profile',['user' => $user['upline_id']])}}" target="_blank">{{\App\User::findOrFail($user['upline_id'])->fullname}}</a></b>
                        </p>
                        <p class="text-sm">Admin Report
                            <b class="d-block">{{$admin_report}}</b>
                        </p>
                    </div>
                    @can('approve request')
                        <div class="text-center mt-5 mb-3">
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#approve" @if($status !== 'pending') disabled="disabled" @endif>Approve</button>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#reject" @if($status !== 'pending') disabled="disabled" @endif>Reject</button>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    @if($status === 'pending')
        @can('approve request')
            <div class="modal fade" id="approve">
                <form role="form" id="approve-form" class="form-submit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="thresholdId" value="{{$id}}">
                    <input type="hidden" name="action" value="approved">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Approve Request</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group reason">
                                    <label for="reason">Reason</label><span class="required">*</span>
                                    <textarea class="form-control" name="reason" id="reason"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </form>
            </div>
        @endcan
        @can('reject request')
            <div class="modal fade" id="reject">
                <form role="form" id="reject-form" class="form-submit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="thresholdId" value="{{$id}}">
                    <input type="hidden" name="action" value="rejected">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Reject Request</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group reason">
                                    <label for="reason">Reason</label><span class="required">*</span>
                                    <textarea class="form-control" name="reason" id="reason" style="min-height:200px;"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </form>
            </div>
        @endcan
    @endif
@stop

@section('right-sidebar')
    <x-custom.right-sidebar />
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
