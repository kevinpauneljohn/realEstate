@extends('adminlte::page')

@section('title', 'User Profile | Agents')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">User Profile | Agents</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a> </li>
                <li class="breadcrumb-item active">User Profile | Agents</li>
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
                        <strong><i class="fas fa-plus mr-1"></i> Added By</strong>

                        <p class="text-muted">{{ucfirst($upline->firstname)}} {{ucfirst($upline->lastname)}}</p>
                        <hr>

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
                        <a href="{{route('users.profile',['user' => $user->id])}}"><button type="button" class="btn @if(request()->segment(3) == 'profile')btn-success @else btn-default @endif" data-toggle="modal"><i class="fa fa-money-bill"></i> Sales</button></a>
                        <a href="{{route('users.agents',['user' => $user->id])}}"><button type="button" class="btn @if(request()->segment(3) == 'agents')btn-success @else btn-default @endif" data-toggle="modal"><i class="fa fa-calendar-alt"></i> Agents</button></a>
                        <a href="{{route('users.commissions',['user' => $user->id])}}"><button type="button" class="btn @if(request()->segment(3) == 'commissions')btn-success @else btn-default @endif" data-toggle="modal"><i class="fa fa-calendar-alt"></i> Commission</button></a>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <button type="button" class="btn btn-primary" style="margin:3px;" data-target="#add-commission-modal" data-toggle="modal" @if($rate_limit === null) disabled="disabled" @endif>Add Commission</button>

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
                                <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
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
        </script>
    @endcan
@stop
