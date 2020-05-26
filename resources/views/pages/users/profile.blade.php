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

                        <p class="text-muted text-center">
                            @foreach($user->getRoleNames() as $roles)
                                <span class="badge badge-info">{{$roles}}</span>
                            @endforeach
                        </p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b><i class="fas fa-plus mr-1"></i>Up line</b> <a class="float-right">{{ucfirst($upline->firstname)}} {{ucfirst($upline->lastname)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-user-alt mr-1"></i>Username</b> <a class="float-right">{{$user->username}}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-envelope mr-1"></i>Email</b> <a class="float-right">{{$user->email}}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-mobile-alt mr-1"></i>Mobile No.</b> <a class="float-right">{{$user->mobileNo}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#sales">Sales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#down-line">Down Lines</a>
                            </li>
                            @if(auth()->user()->can('view down line leads'))
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#leads">Leads</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#commission">Commissions</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="sales" class="container tab-pane active"><br>
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
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="down-line" class="container tab-pane fade"><br>
                                <h3>Down Lines</h3>
                                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                            @if(auth()->user()->can('view down line leads'))
                                <div id="leads" class="container tab-pane fade"><br>
                                    <table id="leads-list" class="table table-bordered table-hover" role="grid">
                                        <thead>
                                        <tr role="row">
                                            <th>Date Inquired</th>
                                            <th>Name</th>
                                            <th>Source</th>
                                            <th>Lead Status</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr>
                                            <th>Date Inquired</th>
                                            <th>Name</th>
                                            <th>Source</th>
                                            <th>Lead Status</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                            <div id="commission" class="container tab-pane fade"><br>
                                <button type="button" class="btn btn-primary btn-sm" style="margin:3px;" data-target="#add-commission-modal" data-toggle="modal" @if($rate_limit === null) disabled="disabled" @endif>Add Commission</button>

                                <table id="commission-list" class="table table-bordered table-striped" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>Date Assigned</th>
                                        <th>Commission Rate</th>
                                        <th>Project</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                        <th>Date Assigned</th>
                                        <th>Commission Rate</th>
                                        <th>Project</th>
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

    @if($rate_limit !== null)
        @can('add commissions')
            <!--edit role modal-->
            <div class="modal fade" id="add-commission-modal">
                <form role="form" id="add-commission-form" class="form-submit">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Commission</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group project">
                                    <label for="project">Project</label> <span>(Optional)</span>
                                    <select class="form-control" name="project" id="project">
                                        <option value=""> -- Select -- </option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group commission_rate"><span class="required">*</span>
                                    <label for="commission_rate">Commission Rate</label>
                                    <select class="form-control" name="commission_rate" id="commission_rate">
                                        <option value=""> -- Select -- </option>
                                        @for($ctr = 1; $ctr <= $rate_limit; $ctr++)
                                            <option value="{{$ctr-0.5}}">{{$ctr-0.5}}%</option>
                                            <option value="{{$ctr}}">{{$ctr}}%</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="reset" class="btn btn-default">Reset</button>
                                    <input type="submit" class="btn btn-primary submit-commission-btn" value="Save">
                                </div>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </form>
            </div>
            <!--end add user modal-->
        @endcan
    @endif
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
        <script src="{{asset('js/commission.js')}}"></script>
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
                        // { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });


            $(function() {
                $('#commission-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('commissions.list',['user' => $user->id]) !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'commission_rate', name: 'commission_rate'},
                        { data: 'project_id', name: 'project_id'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });

            $(function() {
                $('#leads-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('user.leads.list',['user' => $user->id]) !!}',
                    columns: [
                        { data: 'date_inquired', name: 'date_inquired'},
                        { data: 'fullname', name: 'fullname'},
                        { data: 'point_of_contact', name: 'point_of_contact'},
                        { data: 'lead_status', name: 'lead_status'},
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>
    @endcan
@stop
