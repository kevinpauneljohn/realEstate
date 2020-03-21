@extends('adminlte::page')

@section('title', 'User Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">User Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a> </li>
                <li class="breadcrumb-item active">User Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{asset('/images/avatar.png')}}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">
                            {{ucfirst($user->firstname)}} {{ucfirst($user->lastname)}}
                        </h3>

                        <a href="" class="btn btn-info btn-block"><b>Edit</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Details</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                        <p class="text-muted">{{$user->address}}</p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Contact Number</strong>

                        <p class="text-muted">
                            {{$user->mobileNo}}
                        </p>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <button type="button" class="btn @if(request()->segment(3) == 'profile')btn-success @else btn-default @endif" data-toggle="modal"><i class="fa fa-money-bill"></i> Sales</button>
                        <a href="{{route('users.agents',['user' => $user->id])}}"><button type="button" class="btn @if(request()->segment(3) == 'agents')btn-success @else btn-default @endif" data-toggle="modal"><i class="fa fa-calendar-alt"></i> Agents</button></a>
                        <a href="{{route('users.commissions',['user' => $user->id])}}"><button type="button" class="btn @if(request()->segment(3) == 'commissions')btn-success @else btn-default @endif" data-toggle="modal"><i class="fa fa-calendar-alt"></i> Commission</button></a>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">

                                <table id="sales-list" class="table table-bordered table-striped" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>Date Reserved</th>
                                        <th>Full Name</th>
                                        <th>Project</th>
                                        <th>Model Unit</th>
                                        <th>Total Contract Price</th>
                                        <th>Discount</th>
                                        <th>Financing</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                        <th>Date Reserved</th>
                                        <th>Full Name</th>
                                        <th>Project</th>
                                        <th>Model Unit</th>
                                        <th>Total Contract Price</th>
                                        <th>Discount</th>
                                        <th>Financing</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('/vendor/timepicker/bootstrap-timepicker.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
        small{
            margin: 2px;
        }
    </style>
@stop

@section('js')
    @can('view lead')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('/vendor/timepicker/bootstrap-timepicker.min.js')}}"></script>
        <!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script src="{{asset('js/sales.js')}}"></script>
        <script>

            $(function () {
                // Summernote
                $('.textarea').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['height', ['height']],
                        ['view', ['fullscreen']],
                    ],
                    lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
                });
            })
        </script>
        <script>
            $('#schedule, #edit_schedule').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            //Initialize Select2 Elements
            $('.select2').select2();
            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false,
                defaultTime: false,
            });

        </script>
        <script>
            $(function() {
                $('#sales-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.sales.list',['id' => $user->id]) !!}',
                    columns: [
                        { data: 'reservation_date', name: 'reservation_date'},
                        { data: 'full_name', name: 'full_name'},
                        { data: 'project', name: 'project'},
                        { data: 'model_unit', name: 'model_unit'},
                        { data: 'total_contract_price', name: 'total_contract_price'},
                        { data: 'discount', name: 'discount'},
                        { data: 'financing', name: 'financing'},
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>
    @endcan
@stop
