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
                                    <span class="info-box-number text-center text-muted mb-0">{{$status}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Request Type</span>
                                    <span class="info-box-number text-center text-muted mb-0">{{$type}} {{$storage_name}}</span>
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
                                    <strong>Request Action: </strong>{!! $data->action !!}
                                </p>
                                <p>
                                    <strong>Reason: </strong>{{$description}}
                                </p>
                            </div>
                            <div class="post">
                                <h4>Data Origin</h4>
                                {!! $data->original_data !!}
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
                    </div>

                    <h5 class="mt-5 text-muted">Project files</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                        </li>
                        <li>
                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                        </li>
                    </ul>
                    <div class="text-center mt-5 mb-3">
                        <a href="#" class="btn btn-sm btn-primary">Add files</a>
                        <a href="#" class="btn btn-sm btn-warning">Report contact</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
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
