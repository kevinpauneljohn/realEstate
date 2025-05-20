@extends('adminlte::page')

@section('title', 'Tasks Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Task Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('tasks.index')}}">Tasks</a> </li>
                <li class="breadcrumb-item active">Task Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card card-default main-section">
                <div class="card-header">
                    <h3 class="card-title">Request Title: <span class="text-info">{{ucwords($task->title)}}</span></h3>
                    <span class="float-right task-action-button"><x-task-action-button id="{{$task->id}}"></x-task-action-button> </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="post">
                                <h5 class="text-bold">Description</h5>
                                <p>{!! ucfirst(nl2br($task->description)) !!}</p>
                            </div>
                            <div class="post">
                                <div class="row">
                                    <button class="btn btn-primary btn-sm add-new-action-taken">Add Action Taken</button>
                                    <input type="hidden" class="form-control action_taken_count" value="{{$action_taken}}" >
                                </div>
                                <br />
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <table id="action-list" class="table table-bordered table-hover" role="grid">
                                        <thead>
                                        <tr role="row">
                                            <th>Description</th>
                                            <th width="13%">Date</th>
                                            <th width="13%">Creator</th>
                                            <th width="13%">Action</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr>
                                            <th>Description</th>
                                            <th width="13%">Date</th>
                                            <th>Creator</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">Activity Logs</span>
                </div>
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="activity-log" class="table table-bordered table-hover" role="grid">
                            <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>Description</th>
                                <th>From</th>
                                <th>User</th>
                                <th width="20%">Date</th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>From</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-default">
                <div class="card-body">
                    <strong><i class="fas fa-ticket-alt"></i> Task # <span class="text-primary text-bold">{{str_pad($task->id, 5, '0', STR_PAD_LEFT)}}</span></strong>
                    <hr>
                    <strong><i class="fas fa-user mr-1"></i> Requester</strong>

                    <p class="text-muted">
                        {{$task->creator->fullname}}
                    </p>

                    <hr>

                    <strong><i class="fas fa-user-circle mr-1"></i> Assigned To</strong>

                    <p class="text-muted">
                        @if(auth()->user()->id === $task->created_by || auth()->user()->hasRole(['super admin','admin','account manager']))
                            <span class="update-assignee hidden">{{$task->user->fullname ?? ''}}</span>
                            <form id="update-assignee">
                                @csrf
                                @method('put')
                                <input type="hidden" name="task_id" value="{{$task->id}}">
                                <select class="form-control select2" id="assigned_to" name="assigned_id" style="width: 100%">
                                    <option value="">Please Select</option>
                                    @foreach($agents as $agent)
                                        <option value="{{$agent->id}}" @if(!empty($task->user->fullname) && $agent->fullname === $task->user->fullname) selected @endif>{{$agent->fullname}}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-xs AssigneeButton" style="width: 100%" disabled>update</button>
                            </form>
                        @else
                            {{$task->user->fullname ?? ''}}
                        @endif
                        <input type="hidden" class="form-control assignee_saved_value" value="{{$task->assigned_to}}">
                    </p>

                    <hr>

                    <strong><i class="fas fa-calendar-check mr-1"></i> Due Date</strong>

                    <p class="text-muted">
                        {{\Carbon\Carbon::parse($task->due_date)->format('M d, Y')}} - {{\Carbon\Carbon::parse($task->time)->format('g:i A')}}
                    </p>

                    <hr>

                    <strong><i class="fas fa-info-circle mr-1"></i> Priority</strong>

                    <p class="text-muted">{{$task->priority->name}}</p>
                    <hr>

                    <strong><i class="fa fa-eye mr-1"></i> Watcher</strong>
                        <input type="hidden" class="form-control watchers_array">
                        <p class="text-muted">
                            @if(auth()->user()->id === $task->created_by || auth()->user()->hasRole(['super admin','admin','account manager']))
                                <span class="update-watcher hidden">
                                    @if(!empty($watcher_id))
                                        @foreach($watchers as $watcher_list)
                                            <span class="text-muted">{{$watcher_list['first_name']}} {{$watcher_list['last_name']}}</span><br />
                                        @endforeach
                                    @else
                                        <span class="text-muted">No Watchers Found.</span>
                                    @endif
                                </span>
                                <form id="update-watcher">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="task_id" value="{{$task->id}}">
                                    <select name="watchers[]" multiple class="form-control" id="watchers" style="width: 100%" placeholder="Select Watcher Here">
                                        @foreach($users as $user)
                                            @if(!empty($watcher_id))
                                                @if(in_array($user->id, $watcher_id))
                                                    <option value="{{$user->id}}" selected>{{$user->username}} [{{$user->firstname}} {{$user->lastname}}]</option>
                                                @else
                                                    <option value="{{$user->id}}">{{$user->username}} [{{$user->firstname}} {{$user->lastname}}]</option>
                                                @endif
                                            @else
                                                <option value="{{$user->id}}">{{$user->username}} [{{$user->firstname}} {{$user->lastname}}]</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-xs watchersButton" style="width: 100%" disabled>update</button>
                                </form>
                            @else
                                @if(!empty($watcher_id))
                                    @foreach($watchers as $watcher)
                                        <span class="text-muted">{{$watcher['first_name']}} {{$watcher['last_name']}}</span><br />
                                    @endforeach
                                @else
                                    <span class="text-muted">No Watchers Found.</span>
                                @endif
                            @endif
                        </p>
                    <input type="hidden" class="get_task_id" value="{{$task->id}}">
                    <input type="hidden" class="get_task_status" value="{{$task->status}}">
                </div>
            </div>
            @if(auth()->user()->id === $task->created_by || auth()->user()->hasRole(['super admin','admin','account manager']))
                <input type="hidden" class="get_count_request form-control" value="{{$count_request}}" >
                <div class="card card-default watchers-div-request">
                    <div class="card-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" >
                            <label>Watcher's Request</label>
                            <table id="watchers-request" class="table table-bordered table-striped" role="grid">
                                <thead>
                                <tr role="row">
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card card-default">
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="remarks-list" class="table table-bordered table-striped" role="grid">
                            <thead>
                            <tr role="row">
                                <th>Remarks</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="action-taken">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Action Taken</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form role="form" id="action-taken-form">
                <div class="modal-body">
                    <div class="form-group action">
                        @csrf
                        <span class="action_required_text" style="color:red;"></span>
                        <input type="hidden" name="checklist_id">
                        <input type="hidden" name="task_id" value="{{$task->id}}">
                        <textarea class="form-control actionTaken" name="action" style="min-height: 200px;" id="action"></textarea>
                    </div>
                </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary submit-checklist-btn float-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style>
        #remarks-list_length, #remarks-list_filter, .dataTables_info{
            display:none;
        }
        .dataTables_wrapper {
            overflow-x: auto;
        }
        .tox-statusbar__branding {
            display: none;
        }
        .tox-notifications-container{
            display:none !important;
        }
        .hidden{
            display:none;
        }
        #watchers-request_filter{
            display:none;
        }
    </style>
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('js/custom-alert.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
    <script>
        checkRequest();
        $('.watchers_array').val($('#watchers').val());

        $("#watchers").select2({
            minimumResultsForSearch: 20
        });

        $('.textEditor,.actionTaken').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen']],
            ],
            lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
        });

        $("#update-assignee").change(function() {

            var selected_assignee_value = $('#assigned_to').find(":selected").val();
            var save_assignee_value = $('.assignee_saved_value').val();

            if (save_assignee_value == selected_assignee_value) {
                $('.AssigneeButton').attr('disabled',true);
            } else {
                $('.AssigneeButton').attr('disabled',false);
            }
        });

        $(document).on('submit','#update-assignee',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                'url' : '{{route('tasks.update.agent')}}',
                'type' : 'PUT',
                'data' : data,
                beforeSend: function(){
                    $('#update-assignee').find('select, button').attr('disabled',true);
                    $('#update-assignee').find('button').text('updating ...');
                },success: function(response){
                    if(response.success === true)
                    {
                        $('#update-assignee').find('select').attr('disabled',false);
                        $('#update-assignee').find('button').attr('disabled',true);
                        $('#update-assignee').find('button').text('update');
                        customAlert('success',response.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        })

        $("#update-watcher").change(function() {
            var watchers_val = $('#update-watcher').find('select').val();
            var watchers_array = $('.watchers_array').val();

            var $input = $(this),
                theSame = $("option", this).map(function() {
                    return this.selected === this.defaultSelected
                }).get(),
                allTheSame = !!theSame.reduce(function(a, b) {
                    return (a === b) ? a : NaN;
                });
            if (!allTheSame) {
                $('.watchersButton').attr('disabled',false);
            } else {
                if (watchers_val === watchers_array) {
                    $('.watchersButton').attr('disabled',true);
                } else {
                    $('.watchersButton').attr('disabled',false);
                }
            }

            if (watchers_val == '') {
                $('.watchersButton').attr('disabled',true);
            }
        });

        $(document).on('submit','#update-watcher',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                'url' : '{{route('tasks.update.watcher')}}',
                'type' : 'PUT',
                'data' : data,
                beforeSend: function(){
                    $('#update-watcher').find('select, button').attr('disabled',true);
                    $('#update-watcher').find('button').text('updating ...');
                },success: function(response){
                    if(response.success === true)
                    {
                        $('#update-watcher').find('select').attr('disabled',false);
                        $('#update-watcher').find('button').attr('disabled',true);
                        $('#update-watcher').find('button').text('update');
                        customAlert('success',response.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        })

        function getStatus()
        {
            var get_status = $('.get_task_status').val();
            @if(($task->assigned_to === auth()->user()->id))
                if (get_status == 'pending') {
                    $('.start_task_component_user_span').removeClass('hidden');

                    if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                        $('.ongoing_task_component_user_span').addClass('hidden');
                    }

                    if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                        $('.completed_task_component_user_span').addClass('hidden');
                    }

                    $('.add-new-action-taken').addClass('hidden');
                } else if (get_status == 'on-going') {
                    $('.ongoing_task_component_user_span').removeClass('hidden');
                    if (!$('.start_task_component_user_span').hasClass("hidden")) {
                        $('.start_task_component_user_span').addClass('hidden');
                    }

                    if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                        $('.completed_task_component_user_span').addClass('hidden');
                    }

                    if ($('.action_taken_count').val() == 1) {
                        $('.ongoing_task_component_button').prop("disabled", false);
                    } else {
                        $('.ongoing_task_component_button').prop("disabled", true);
                    }

                    $('.add-new-action-taken').removeClass('hidden');
                } else if (get_status == 'completed') {
                    @if((auth()->user()->hasRole(['super admin','admin','account manager'])))
                        $('.completed_task_component_user_span').removeClass('hidden');
                    @else
                        $('.completed_task_component_span').removeClass('hidden');
                    @endif

                    if (!$('.start_task_component_user_span').hasClass("hidden")) {
                        $('.start_task_component_user_span').addClass('hidden');
                    }

                    if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                        $('.ongoing_task_component_user_span').addClass('hidden');
                    }

                    $('.add-new-action-taken').addClass('hidden');
                }
            @else
                if (get_status == 'pending') {
                    $('.start_task_component_span').removeClass('hidden');

                    if (!$('.ongoing_task_component_span').hasClass("hidden")) {
                        $('.ongoing_task_component_span').addClass('hidden');
                    }

                    if (!$('.completed_task_component_span').hasClass("hidden")) {
                        $('.completed_task_component_span').addClass('hidden');
                    }

                    $('.add-new-action-taken').addClass('hidden');
                } else if (get_status == 'on-going') {
                    $('.ongoing_task_component_span').removeClass('hidden');

                    if (!$('.start_task_component_span').hasClass("hidden")) {
                        $('.start_task_component_span').addClass('hidden');
                    }

                    if (!$('.completed_task_component_span').hasClass("hidden")) {
                        $('.completed_task_component_span').addClass('hidden');
                    }

                    if ($('.action_taken_count').val() == 1) {
                        $('.ongoing_task_component_button').prop("disabled", false);
                    } else {
                        $('.ongoing_task_component_button').prop("disabled", true);
                    }

                    $('.add-new-action-taken').addClass('hidden');
                } else if (get_status == 'completed') {
                    @if((auth()->user()->hasRole(['super admin','admin','account manager'])))
                        $('.completed_task_component_user_span').removeClass('hidden');
                    @else
                        $('.completed_task_component_span').removeClass('hidden');
                    @endif

                    if (!$('.start_task_component_span').hasClass("hidden")) {
                        $('.start_task_component_span').addClass('hidden');
                    }

                    if (!$('.ongoing_task_component_span').hasClass("hidden")) {
                        $('.ongoing_task_component_span').addClass('hidden');
                    }

                    $('#update-assignee').addClass('hidden');
                    $('.update-assignee').removeClass('hidden');

                    $('#update-watcher').addClass('hidden');
                    $('.update-watcher').removeClass('hidden');

                    $('.add-new-action-taken').addClass('hidden');
                }
            @endif
        }
        $(function() {
            getStatus();
            $('#action-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('actionTaken.display',$task->id) !!}',
                columns: [
                    { data: 'action', name: 'action'},
                    { data: 'date', name: 'date'},
                    { data: 'creator', name: 'creator', orderable: false, searchable: false, className: 'text-center'},
                    { data: 'button', name: 'button', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 25
            });


            $('#activity-log').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('log.display',$task->id) !!}',
                columns: [
                    { data: 'id', name: 'id', visible: false},
                    { data: 'description', name: 'description'},
                    { data: 'subject_type', name: 'subject_type'},
                    { data: 'causer_id', name: 'causer_id'},
                    { data: 'created_at', name: 'created_at'},
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10
            });
        });

        function action_taken_editor(id) {
            $('.actionTaken'+id).summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['view', ['fullscreen']],
                ],
                lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });
        }

        function makeid(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
            }
            return result;
        }

        function makeint_id(length) {
            var result           = '';
            var characters       = '0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
            }
            return result;
        }

        @if(auth()->user()->hasRole(["super admin"]))
        $(document).on('click','.delete-action-taken',function(){
            rowId = this.value;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        'url' : '/action-taken/'+rowId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(output){
                            if(output.success === true){
                                customAlert('success',output.message);

                                $('.timeline').find('.label-'+rowId+', .timeline-content-'+rowId).remove();

                            }else if(output.success === false){
                                customAlert('warning',output.message);
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });

                }
            });

        });
        @endif

        $(document).on('click','.add-new-action-taken',function(){
            $('#action-taken').modal('toggle');
        });

        $(document).on('click','button[name=start_task]',function(){
            let id = this.value;
            let buttonStatus = $(this).attr("data-name");

            taskStatus(id);
        });

        function taskStatus(id){
            $.ajax({
                'url' : '/start-tasks/'+id,
                'type' : 'PUT',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    $('.task-action-button').find('button').attr('disabled',true);
                },success: function(response){
                    var get_status = response.status;
                    $('.get_task_status').val(get_status);
                    if (get_status == 'pending') {
                        $('.start_task_component_user_span').removeClass('hidden');

                        if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                            $('.ongoing_task_component_user_span').addClass('hidden');
                        }

                        if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                            $('.completed_task_component_user_span').addClass('hidden');
                        }

                        $('.add-new-action-taken').addClass('hidden');
                    } else if (get_status == 'on-going') {
                        $('.ongoing_task_component_user_span').removeClass('hidden');

                        if (!$('.start_task_component_user_span').hasClass("hidden")) {
                            $('.start_task_component_user_span').addClass('hidden');
                        }

                        if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                            $('.completed_task_component_user_span').addClass('hidden');
                        }

                        $('.ongoing_task_component_button').prop("disabled", true);
                        $('.add-new-action-taken').removeClass('hidden');
                    } else if (get_status == 'completed' && response.actions == 'complete') {
                        @if(auth()->user()->hasRole(['super admin','admin','account manager']))
                            $('.completed_task_component_user_span').removeClass('hidden');
                        @else
                            $('.completed_task_component_span').removeClass('hidden');
                        @endif

                        if (!$('.start_task_component_user_span').hasClass("hidden")) {
                            $('.start_task_component_user_span').addClass('hidden');
                        }

                        if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                            $('.ongoing_task_component_user_span').addClass('hidden');
                        }

                        $('#update-assignee').addClass('hidden');
                        $('.update-assignee').removeClass('hidden');

                        $('#update-watcher').addClass('hidden');
                        $('.update-watcher').removeClass('hidden');

                        $('.add-new-action-taken').addClass('hidden');
                    }

                    if (response.actions == 'incomplete' && response.status == 'completed') {
                        alert('Checklist Action taken must have atleast 1 data. Please Check each checklist action taken before completed the Task.');
                    }

                    //$('.task-action-button').find('button').attr('disabled',false);

                    let tableLog = $('#activity-log').DataTable();
                    tableLog.ajax.reload(null, false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }

        $(document).on('submit','#set-status-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                'url' : '{{route('task.reopen')}}',
                'type' : 'POST',
                'data' : data,
                beforeSend: function(){
                    $('#set-status-form').find('.remarks-btn').val('Updating ...').attr('disabled',true);
                },success: function (response){
                    console.log(response);

                    if(response.success === true)
                    {
                        customAlert('success',response.message);
                        $('#set-status-form').trigger('reset');
                        $('#set-status').modal('toggle');

                        let table = $('#remarks-list').DataTable();
                        table.ajax.reload(null, false);


                        let tableLog = $('#activity-log').DataTable();
                        tableLog.ajax.reload(null, false);
                    }

                    $.each(response, function (key, value) {
                        let element = $('.'+key);

                        element.find('.error-'+key).remove();
                        element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                    });

                    $('#set-status-form').find('.remarks-btn').val('Update Status').attr('disabled',false);

                    var get_status = response.status;
                    $('.get_task_status').val(get_status);
                    if (get_status == 'pending') {
                        if (response.creator == 'no') {
                            $('.start_task_component_user_span').removeClass('hidden');
                        } else {
                            $('.start_task_component_span').removeClass('hidden');
                        }

                        if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                            $('.ongoing_task_component_user_span').addClass('hidden');
                        }

                        if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                            $('.completed_task_component_user_span').addClass('hidden');
                        }

                        $('.add-new-action-taken').addClass('hidden');
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
            clear_errors('remarks');
        });

        $(function() {
            $('#remarks-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('remarks.display',$task->id) !!}',
                columns: [
                    { data: 'task', name: 'task', orderable: false, searchable: false},
                ],
                responsive:true,
                order:[0,'desc']
            });
        });

        $(function() {
            $('#watchers-request').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('request.display',$task->id) !!}',
                columns: [
                    { data: 'name', name: 'name', orderable: false, searchable: false},
                    { data: 'type', name: 'type', orderable: false, searchable: false},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc']
            });
        });

        $(document).on('click','.update-task-request',function(){
            let id = this.id;
            let task_id = $(this).attr('data-id');
            let user_id = $(this).attr('data-user');
            let type = $(this).attr('data-type');
            let request = $(this).attr('data-request');
            let fullname = $(this).attr('data-name');
            let username = $(this).attr('data-username');
            let email = $(this).attr('data-email');

            var type_title = 'remove';
            var type_text = 'reject';
            if (type == 'approved') {
                type_title = 'approve';
                type_text = 'approve';
            }

            Swal.fire({
                title: 'Are you sure',
                text: "You want to "+type_text+" this request?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, '+type_title+' it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'url' : '{{route('request.update')}}',
                        'type' : 'POST',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        'data' : {
                            'id' : id,
                            'task_id' : task_id,
                            'user_id' : user_id,
                            'type' : type,
                            'request' : request,
                            'fullname' : fullname,
                            'username' : username,
                            'email' : email
                        },
                        beforeSend: function(){

                        },success: function(output){
                            if(output.success === true){
                                customAlert('success',output.message);
                            }
                            let table = $('#watchers-request').DataTable();
                            table.ajax.reload(null, false);

                            let tableLog = $('#activity-log').DataTable();
                            tableLog.ajax.reload(null, false);

                            if (request == 'pending') {
                                if (type == 'approved') {
                                    var selectedItems = $('#watchers').val();
                                    selectedItems.push(user_id);
                                    $('#watchers').val(selectedItems).trigger('change');
                                    $('.watchersButton').attr('disabled',true);
                                    $('.watchers_array').val(selectedItems);
                                }
                            } else if (request == 'remove') {
                                if (type == 'approved') {
                                    $("#watchers option[value='"+user_id+"']").remove();
                                    $('.watchers_array').val($('#watchers').val());
                                    $('.watchersButton').attr('disabled',true);
                                }
                            }
                            countRequest(task_id);
                            checkRequest();
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });
                }
            });
        });

        function countRequest(id)
        {
            $.ajax({
                'url' : '/count-request/'+id,
                'type' : 'GET',
                beforeSend: function(){

                },success: function(result){
                    $('.get_count_request').val(result);
                    if (result == 0) {
                        $('.watchers-div-request').addClass('hidden');
                    } else {
                        $('.watchers-div-request').removeClass('hidden');
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }

        function checkRequest()
        {
            var val = $('.get_count_request').val();
            if (val == 0)
            {
                $('.watchers-div-request').addClass('hidden');
            } else {
                $('.watchers-div-request').removeClass('hidden');
            }
        }

        let actionModal = $('#action-taken');
        $('#action-taken').on('hidden.bs.modal',function(){
            actionModal.find('.modal-title').text("Action Taken");
            $('.actionTaken').summernote('code', "");
            let tableLog = $('#activity-log').DataTable();
            tableLog.ajax.reload(null, false);
        });

        $(document).on('click','.edit-action-btn',function(){
            let id = this.id;

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
                return $(this).html();
            }).get();

            let action = data[0];

            actionModal.find('.modal-title').text("Edit Action Taken");
            actionModal.find('form').attr('id','edit-action-form').prepend('<input type="hidden" name="action_taken_id" value="'+id+'">');
            $('.actionTaken').summernote('code', action);
            $('#action-taken').modal('toggle');

            $('.submit-checklist-btn').addClass('UpdateActionTaken');
            $('.UpdateActionTaken').removeClass('submit-checklist-btn');
        });

        $(document).on('click','.submit-checklist-btn',function(){
            let action = $('textarea[name=action]').val();
            let id = $('.get_task_id').val();

            var valid;
            if ($('textarea[name=action]').summernote('isEmpty')) {
                valid = false;
                $('.action_required_text').text('*Action field is required.');
            } else {
                valid = true;
            }

            if (valid)
            {
                $.ajax({
                    'url' : '/action-taken',
                    'type' : 'POST',
                    'data' : {
                        'id': id,
                        'action': action
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        $('.submit-checklist-btn').prop('disabled',true);
                        $('.submit-checklist-btn').text('Saving...');
                    },success: function(output){
                        if(output.success === true){
                            customAlert('success',output.message);
                            $('textarea[name=action]').summernote("code", "");
                            $('#action-taken').modal('toggle');

                            let table = $('#action-list').DataTable();
                            table.ajax.reload(null, false);

                            let tableLog = $('#activity-log').DataTable();
                            tableLog.ajax.reload(null, false);

                            $('.action_required_text').text('');
                            $('.submit-checklist-btn').prop('disabled',false);
                            $('.submit-checklist-btn').text('Save');

                            $('.action_taken_count').val(1);
                            $('.ongoing_task_component_button').prop("disabled", false);
                        }else if(output.success === false){
                            customAlert('warning',output.message);
                            $('.submit-checklist-btn').prop('disabled',false);
                            $('.submit-checklist-btn').text('Save');
                        }

                        $('#action-taken-form').find('input,textarea').attr('disabled',false);
                        $('#action-taken-form').find('input[type=submit]').val('Save');
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            }
        })

        $(document).on('click','.UpdateActionTaken',function(){
            let action = $('textarea[name=action]').val();
            let id = $('input[name=action_taken_id]').val();
            let task_id = $('.get_task_id').val();

            var valid;
            if ($('textarea[name=action]').summernote('isEmpty')) {
                valid = false;
                $('.action_required_text').text('*Action field is required.');
            } else {
                valid = true;
            }

            if (valid)
            {
                $.ajax({
                    'url' : '/action-taken/'+id,
                    'type' : 'PUT',
                    'data' : {
                        'task_id': task_id,
                        'action' : action
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        $('.UpdateActionTaken').prop('disabled',true);
                        $('.UpdateActionTaken').text('Saving...');
                    },success: function(output){
                        if(output.success === true){
                            customAlert('success',output.message);
                            $('textarea[name=action]').summernote("code", "");
                            $('#action-taken').modal('toggle');

                            let table = $('#action-list').DataTable();
                            table.ajax.reload(null, false);

                            let tableLog = $('#activity-log').DataTable();
                            tableLog.ajax.reload(null, false);

                            $('.action_required_text').text('');
                            $('.UpdateActionTaken').prop('disabled',false);
                            $('.UpdateActionTaken').text('Save');
                            $('.action_taken_count').val(1);
                        }else if(output.success === false){
                            customAlert('warning',output.message);
                            $('.UpdateActionTaken').text('Save');
                            $('.UpdateActionTaken').prop('disabled',false);
                        }

                        $('.edit-action-form').find('button,textarea').attr('disabled',false);
                        $('.edit-action-form').find('button .save').text('Save');
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            }
        });

        $(document).on('click','.delete-action-btn',function(){
            let id = this.id;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        'url' : '/action-taken/'+id,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(output){
                            if(output.success === true){
                                customAlert('success',output.message);
                                let table = $('#action-list').DataTable();
                                table.ajax.reload(null, false);

                                let log = $('#activity-log').DataTable();
                                log.ajax.reload(null, false);

                            }else if(output.success === false){
                                customAlert('warning',output.message);
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });

                }
            });
        });
    </script>
@stop
