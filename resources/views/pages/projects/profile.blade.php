@extends('adminlte::page')

@section('title', 'Project Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Project Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a> </li>
                <li class="breadcrumb-item active">Project Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">


                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Details</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-home mr-1"></i> Project Name</strong>

                        <p class="text-muted">{{$project->name}}</p>

                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                        <p class="text-muted">{{$project->address}}</p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Remarks</strong>

                        <p class="text-muted">{!! $project->remarks !!}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        @can('add model unit')
                            <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-project-modal"><i class="fa fa-plus-circle"></i> Add Model Unit</button>
                        @endcan
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <table id="activity-list" class="table table-bordered table-striped" role="grid">
                            <thead>
                            <tr role="row">
                                <th>Date Scheduled</th>
                                <th>Details</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th width="12%">Date Scheduled</th>
                                <th width="50%">Details</th>
                                <th width="9%">Category</th>
                                <th width="9%">Status</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>


    <!--add new schedule modal-->
    <div class="modal fade" id="add-schedule-modal">
        <form role="form" id="add-schedule-form">
            @csrf
            <input type="hidden" name="leadId" value="{{$project->id}}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create Schedule</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group schedule">
                                    <label for="schedule">Date</label><span class="required">*</span>
                                    <input type="text" name="schedule" class="form-control datemask" id="schedule" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 start_time">
                                        <label for="start_time">Start Time</label>
                                        <input type="text" name="start_time" class="form-control timepicker" id="start_time">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="end_time">End Time</label>
                                        <input type="text" name="end_time" class="form-control timepicker" id="end_time">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
                                </div>
                                <div class="form-group category">
                                    <label for="category">Category</label>
                                    <select name="category" class="form-control" id="category">
                                        <option value=""> -- Select -- </option>
                                        <option value="Tripping"> Tripping</option>
                                        <option value="Assist"> Assist</option>
                                        <option value="Follow-up"> Follow-up</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul id="schedules"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <!--end add new schedule modal-->


    @can('edit lead')
        <!--add new schedule modal-->
        <div class="modal fade" id="edit-schedule-modal">
            <form role="form" id="edit-schedule-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="editLeadId" value="{{$project->id}}">
                <input type="hidden" name="scheduleId" id="scheduleId">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create Schedule</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group edit_schedule">
                                        <label for="edit_schedule">Date</label><span class="required">*</span>
                                        <input type="text" name="edit_schedule" class="form-control datemask" id="edit_schedule" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 edit_start_time">
                                            <label for="edit_start_time">Start Time</label>
                                            <input type="text" name="edit_start_time" class="form-control timepicker" id="edit_start_time">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="edit_end_time">End Time</label>
                                            <input type="text" name="edit_end_time" class="form-control timepicker" id="edit_end_time">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_remarks">Remarks</label>
                                        <textarea name="edit_remarks" class="textarea" id="edit_remarks" data-min-height="150" placeholder="Place some text here"></textarea>
                                    </div>
                                    <div class="form-group edit_category">
                                        <label for="edit_category">Category</label>
                                        <select name="edit_category" class="form-control" id="edit_category">
                                            <option value=""> -- Select -- </option>
                                            <option value="Tripping"> Tripping</option>
                                            <option value="Assist"> Assist</option>
                                            <option value="Follow-up"> Follow-up</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <ul id="edit_schedules"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new schedule modal-->
    @endcan

    @can('delete lead')
        <!--delete schedule-->
        <div class="modal fade" id="delete-schedule-modal">
            <form role="form" id="delete-schedule-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteScheduleId" id="deleteScheduleId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_schedule">Are you sure you want to delete schedule?</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-light">Delete</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end delete user modal-->
    @endcan
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
        <script src="{{asset('js/leadActivity.js')}}"></script>
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
            $(function() {
                $('#activity-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('leads.activity.list') !!}',
                    columns: [
                        { data: 'schedule', name: 'schedule'},
                        { data: 'details', name: 'details'},
                        { data: 'category', name: 'category'},
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
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
    @endcan
@stop
