@extends('adminlte::page')

@section('title', 'Lead Details')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Lead Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('leads.index')}}">Leads</a> </li>
                <li class="breadcrumb-item active">Lead Details</li>
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
                        <h3 class="card-title">Lead Overview</h3>
                        <a href="{{route('leads.edit',['lead' => $lead->id])}}" class="float-right"><i class="fas fa-user-edit"></i> Edit Lead</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-id-badge mr-1"></i> Full Name</strong>

                        <p class="text-muted">
                            {{ucfirst($lead->firstname)}}
                            {{ucfirst($lead->middlename)}}
                            {{ucfirst($lead->lastname)}}
                        </p>

                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                        <p class="text-muted">{{$lead->address}}</p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Landline</strong>

                        <p class="text-muted">
                            {{$lead->landline}}
                        </p>

                        <hr>

                        <strong><i class="fas fa-mobile-alt mr-1"></i> Mobile Phone</strong>

                        <p class="text-muted">
                            {{$lead->mobileNo}}
                        </p>

                        <hr>

                        <strong><i class="fas fa-envelope-open mr-1"></i> Email</strong>

                        <p class="text-muted">
                            {{$lead->email}}
                        </p>

                        <hr>

                        <strong><i class="fas fa-money-bill mr-1"></i> Income Range</strong>

                        <p class="text-muted">
                            {{$lead->income_range}}
                        </p>

                        <hr>
                        <strong><i class="fas fa-phone mr-1"></i> Point Of Contact</strong>

                        <p class="text-muted">
                            {{$lead->point_of_contact}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-home mr-1"></i> Project Interested</strong>

                        <p class="text-muted">
                            {!! \App\Http\Controllers\LeadController::labeler($lead->project) !!}
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Remarks</strong>

                        <p class="text-muted">{!! $lead->remarks !!}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <button type="button" class="btn btn-success create-schedule" data-toggle="modal" data-target="#add-schedule-modal"><i class="fa fa-calendar-alt"></i> Create Schedule</button>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">

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


    <!--add new schedule modal-->
    <div class="modal fade" id="add-schedule-modal">
        <form role="form" id="add-schedule-form" class="form-submit">
            @csrf
            <input type="hidden" name="leadId" value="{{$lead->id}}">
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
{{--                                        <input type="text" name="start_time" class="form-control timepicker" id="start_time">--}}
                                        <div class="input-group date" id="time_start" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#time_start" name="start_time"/>
                                            <div class="input-group-append" data-target="#time_start" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="end_time">End Time</label>
{{--                                        <input type="text" name="end_time" class="form-control timepicker" id="end_time">--}}
                                        <div class="input-group date" id="time_end" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#time_end" name="end_time"/>
                                            <div class="input-group-append" data-target="#time_end" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
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
                                        <option value="Send Details"> Send Details</option>
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
                        <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
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
            <form role="form" id="edit-schedule-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="editLeadId" value="{{$lead->id}}">
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
{{--                                            <input type="text" name="edit_start_time" class="form-control timepicker" id="edit_start_time">--}}
                                            <div class="input-group date" id="edit_time_start" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#edit_time_start" name="edit_start_time"/>
                                                <div class="input-group-append" data-target="#edit_time_start" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="edit_end_time">End Time</label>
{{--                                            <input type="text" name="edit_end_time" class="form-control timepicker" id="edit_end_time">--}}
                                            <div class="input-group date" id="edit_time_end" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#edit_time_end" name="edit_end_time"/>
                                                <div class="input-group-append" data-target="#edit_time_end" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
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
                                            <option value="Send Details"> Send Details</option>
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
                            <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
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
            <form role="form" id="delete-schedule-form" class="form-submit">
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
                            <button type="submit" class="btn btn-outline-light submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Delete</button>
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
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    {{--<!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('/vendor/timepicker/bootstrap-timepicker.min.css')}}">--}}
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
        <script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

{{--        <script src="{{asset('/vendor/timepicker/bootstrap-timepicker.min.js')}}"></script>--}}
        <!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script src="{{asset('js/leadActivity.js')}}"></script>
        <script src="{{asset('js/schedule.js')}}"></script>
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
                    ajax: '{!! route('leads.activity.list',['lead' => $lead->id]) !!}',
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
            /*//Timepicker
            $('.timepicker').timepicker({
                showInputs: false,
                defaultTime: false,
            });*/
            //Timepicker
            $('#time_start, #time_end, #edit_time_start, #edit_time_end').datetimepicker({
                format: 'LT'
            })

        </script>
    @endcan
@stop
