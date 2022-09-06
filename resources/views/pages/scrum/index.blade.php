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
                                @if((auth()->user()->hasRole(['super admin','admin','account manager'])) && auth()->user()->can('view checklist'))
                                    <button type="button" class="btn btn-default btn-xs create-checklist mb-md-2" data-toggle="modal" data-target="#checklist">Create Checklist</button>
                                @endif
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                                    <table id="check-list" class="table table-bordered table-hover" role="grid">
                                        <thead>
                                        <tr role="row">
                                            <th>Description</th>
                                            <th width="13%">Completed</th>
                                            <th width="13%">Action</th>
                                        </tr>
                                        </thead>

                                        <tfoot>
                                        <tr>
                                            <th>Description</th>
                                            <th>Completed</th>
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
                <div class="card card-default">
                    <div class="card-body">
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
            @endif
            <div class="card card-default">
                <div class="card-body">
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

    @can('add checklist')
        <div class="modal fade" id="checklist">
            <form role="form" id="checklist-form">
                @csrf
                <input type="hidden" name="task_id" value="{{$task->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Checklist</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="add_checklist_count" value="0" class="form-control">
                            <table class="checklist-table">
                                <tr class="table-row-0">
                                    <td>
                                        <textarea class="form-control" id="checklist0" name="checklist[]"></textarea>
                                    </td>
                                    <td><button type="button" disabled class="btn btn-danger btn-xs remove" id="0" title="remove"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            </table>
                            <button type="button" class="btn btn-default btn-sm add-row">Add Row</button>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-checklist-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
    @endcan

    @can('edit checklist')
        <div class="modal fade" id="edit-checklist">
            <form role="form" id="edit-checklist-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="task_id" value="{{$task->id}}">
                <input type="hidden" name="checklist_id">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Checklist</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <textarea class="form-control textEditor" id="edit_checklist" name="checklist" style="min-height: 300px;"></textarea>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-checklist-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
    @endcan

    <div class="modal fade" id="action-taken">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Action Taken</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group action">
                        <form role="form" id="action-taken-form">
                            @csrf
                            <input type="hidden" name="checklist_id">
                            <input type="hidden" name="task_id" value="{{$task->id}}">
                            <textarea class="form-control actionTaken" name="action" style="min-height: 200px;" id="action"></textarea>
                            <br />
                            <input type="submit" class="btn btn-primary submit-checklist-btn float-right" value="Save">
                            <br />
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-md-12 action-timeline"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>

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
            overflow-x: hidden;
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
                ['insert', ['link']],
                ['height', ['height']],
                ['view', ['fullscreen']],
            ],
            lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
        });

        $('#checklist0').summernote({
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

        let checklist_id; //checklist_id
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
            $watchers_val = $('#update-watcher').find('select').val();

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
                $('.watchersButton').attr('disabled',true);
            }

            if ($watchers_val == '') {
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
                } else if (get_status == 'on-going') {
                    $('.ongoing_task_component_user_span').removeClass('hidden');

                    if (!$('.start_task_component_user_span').hasClass("hidden")) {
                        $('.start_task_component_user_span').addClass('hidden');
                    }

                    if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                        $('.completed_task_component_user_span').addClass('hidden');
                    }
                } else if (get_status == 'completed') {
                    @if((auth()->user()->hasRole(['super admin','admin','account manager'])))
                        $('.completed_task_component_user_span').removeClass('hidden');
                    @else
                        $('.delete-action-taken').attr('disabled',true);
                        $('.submit-checklist-btn').attr('disabled',true);

                        $('.completed_task_component_span').removeClass('hidden');
                    @endif

                    if (!$('.start_task_component_user_span').hasClass("hidden")) {
                        $('.start_task_component_user_span').addClass('hidden');
                    }

                    if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                        $('.ongoing_task_component_user_span').addClass('hidden');
                    }
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
                } else if (get_status == 'on-going') {
                    $('.ongoing_task_component_span').removeClass('hidden');

                    if (!$('.start_task_component_span').hasClass("hidden")) {
                        $('.start_task_component_span').addClass('hidden');
                    }

                    if (!$('.completed_task_component_span').hasClass("hidden")) {
                        $('.completed_task_component_span').addClass('hidden');
                    }
                } else if (get_status == 'completed') {
                    @if((auth()->user()->hasRole(['super admin','admin','account manager'])))
                        $('.completed_task_component_user_span').removeClass('hidden');
                    @else
                        $('.delete-action-taken').attr('disabled',true);
                        $('.submit-checklist-btn').attr('disabled',true);

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
                }
            @endif
        }
        $(function() {
            getStatus();
            $('#check-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('checklist.display',$task->id) !!}',
                columns: [
                    { data: 'description', name: 'description'},
                    { data: 'completed', name: 'completed', orderable: false, searchable: false, className: 'text-center'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
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


        let checklistTable = $('.checklist-table');
        let checklistForm = $('#checklist-form');

        function wysiwyg_editor(id) {
            $('#checklist'+id).summernote({
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
                lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });
        }

        function action_taken_editor(id) {
            $('.actionTaken'+id).summernote({
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

        $(document).on('click','.add-row',function(){
            let tr = checklistTable.find('tr').length + 1;
            var checklist_form = +$('#add_checklist_count').val() + 1;
            var checklist_count = $('#add_checklist_count').val(checklist_form);
            
            if (tr >= 0) {
                $('.remove').prop('disabled', false);
            }

            var create_random_id = makeid(5);
            var create_random_int_id = makeint_id(5);
            checklistTable.append(
                `<tr class="table-row-${create_random_int_id}">
                    <td><textarea class="form-control" id="checklist${create_random_id}" name="checklist[]"></textarea></td>
                    <td><button type="button" class="btn btn-danger btn-xs remove" id="${create_random_int_id}" title="remove"><i class="fas fa-trash"></i></button></td>
                </tr>`
            );
            wysiwyg_editor(create_random_id)
        });

        $(document).on('click','.remove',function(){
            let id = this.id;
            var checklist_form = +$('#add_checklist_count').val() - 1;
            if (checklist_form > -1) {
                var checklist_count = $('#add_checklist_count').val(checklist_form);
            }

            if (checklist_form == 0) {
                $('.remove').prop('disabled', true);
            }
            
            checklistTable.find('.table-row-'+id).remove();
        });

        $(document).on('submit','#checklist-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                'url' : '{{route('task-checklist.store')}}',
                'type' : 'POST',
                'data' : data,
                beforeSend: function(){
                    checklistForm.find('input, textarea').attr('disabled',true);
                },success: function (response){
                    if(response.success === true)
                    {
                        customAlert('success',response.message);
                        checklistTable.find('tr').remove();
                        var create_random_id = makeid(5);
                        checklistTable.append(
                            `<tr class="table-row-0">
                                <td><textarea class="form-control" id="checklist${create_random_id}" name="checklist[]"></textarea></td>
                                <td><button type="button" class="btn btn-danger btn-xs remove" id="0" title="remove"><i class="fas fa-trash"></i></button></td>
                            </tr>`
                        );
                        wysiwyg_editor(create_random_id);
                        $('.remove').prop('disabled', true);
                        $('#add_checklist_count').val(0);

                        $('#checklist').modal('toggle');
                        let table = $('#check-list').DataTable();
                        table.ajax.reload(null, false);

                        let tableLog = $('#activity-log').DataTable();
                        tableLog.ajax.reload(null, false);
                    }
                    checklistForm.find('input, textarea').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr)
                }
            });
        });

        @if((auth()->user()->hasRole(['super admin','admin','account manager'])) || ($task->assigned_to === auth()->user()->id && auth()->user()->can('view checklist')))
            $(document).on('click','.check-list-box',function(){
                $('#action-taken-form').show();
                checklist_id = this.value;
                displayActionTaken();

            $('#action-taken-form').find('input[name=checklist_id]').val(checklist_id);
                $('#action-taken').modal('toggle');
            });
        @endif

        @can('delete checklist')
            $(document).on('click','.delete',function(){
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
                            'url' : '/task-checklist/'+id,
                            'type' : 'DELETE',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            'data' : {'_method':'DELETE','id' : id},
                            beforeSend: function(){

                            },success: function(output){
                                if(output.success === true){
                                    customAlert('success',output.message);
                                }
                                let table = $('#check-list').DataTable();
                                table.ajax.reload(null, false);

                                let tableLog = $('#activity-log').DataTable();
                                tableLog.ajax.reload(null, false);
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }
                });
        });
        @endcan

        @can('edit checklist')

            $(document).on('click','.edit',function(){
            checklist_id = this.id;
                let tr = $(this).closest('tr');

                let data = tr.children("td").map(function () {
                    return $(this).html();
                }).get();

                $('#edit-checklist-form').find('input[name=checklist_id]').val(checklist_id);
                $('#edit_checklist').summernote('code', data[0]);
            });

            $(document).on('submit','#edit-checklist-form',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();
                $.ajax({
                    'url' : '/task-checklist/'+checklist_id+'/update/checklist',
                    'type' : 'PUT',
                    'data' : data,
                    beforeSend: function(){
                        $('#edit-checklist-form').find('input,textarea').attr('disabled',true);
                        $('#edit-checklist-form').find('input[type=submit]').val('Saving...');
                    },success: function(output){
                        if(output.success === true){
                            customAlert('success',output.message);

                            $('#edit-checklist').modal('toggle');
                            let table = $('#check-list').DataTable();
                            table.ajax.reload(null, false);

                            let tableLog = $('#activity-log').DataTable();
                            tableLog.ajax.reload(null, false);
                        }else if(output.success === false){
                            customAlert('warning',output.message);
                        }

                        $('#edit-checklist-form').find('input,textarea').attr('disabled',false);
                        $('#edit-checklist-form').find('input[type=submit]').val('Save');
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            })
        @endcan

        let creator_action;
        let super_admin_action;
        let user_action_id;
        function displayActionTaken()
        {
            let task_id = $('.get_task_id').val();
            $.ajax({
                'url' : '/action-taken/'+checklist_id+'/display',
                'type' : 'GET',
                beforeSend: function(){
                    $('#action-taken-form').find("textarea").val("");
                },success: function(response){
                    $('.action-timeline').html('<div class="timeline"></div>');

                    $.each(response, function(key, value){
                        var created_at = value.created_at;
                        var expired_at = value.expired_at;
                        var today = value.today;

                        var job_start_date = value.expired_at;
                        var job_end_date = value.today;
                        job_start_date = job_start_date.split('-');
                        job_end_date = job_end_date.split('-');

                        var new_start_date = new Date(job_start_date[2],job_start_date[0],job_start_date[1]);
                        var new_end_date = new Date(job_end_date[2],job_end_date[0],job_end_date[1]);

                        creator_action = value.user_id;
                        var action = value.is_creator;
                        user_action_id = '{{auth()->user()->id}}';
                        super_admin_action = '{{auth()->user()->hasRole(["super admin"])}}';

                        var edit_action = `<button type="button" class="btn btn-primary btn-xs edit-action-taken" data-id="${task_id}" value="${value.id}">Edit</button>`;
                        var delete_action = `<button type="button" class="btn btn-danger btn-xs delete-action-taken" data-id="${task_id}" value="${value.id}">Delete</button>`;
                        
                        var actions = '';
                        if (super_admin_action != '') {
                            actions = edit_action+delete_action;
                        } else if (user_action_id == creator_action) {
                            if(new_end_date >= new_start_date) {
                                actions = '';
                            } else {
                                actions = edit_action;
                            }
                        }

                        $('.action-timeline').find('.timeline').append(`
                            <div class="time-label label-${value.id}">
                                <span class="bg-cyan">${moment(value.created_at).format('dddd, MMMM Do YYYY')}</span>
                            </div>

                            <div class="timeline-content-${value.id}">
                                <i class="fas fa-check-circle bg-success"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> ${moment(value.created_at).format('ddd, hA')}</span>
                                    <h3 class="timeline-header"><a href="#">Creator</a> ${value.creator}</h3>
                        
                                    <div class="timeline-body" id="action-taken-${value.id}">${value.action}</div>
                                    <div class="timeline-footer" id="action-btn-${value.id}">${actions}</div>
                                </div>
                            </div>
                        `);
                    });

                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }

        $(document).on('click','.log-action',function(){
            checklist_id = this.id;
            $('#action-taken-form').hide();
            displayActionTaken();
        });

        let actionContent;
        let actionContentHtml;
        let rowId;
        let actionEditHtml;
        let actionDeleteHtml;
        let returnActionHtml;

        $(document).on('click','.edit-action-taken',function(){
            rowId = this.value;
            var task_id = $('.get_task_id').val();

            $('.action-timeline').find('#action-btn-'+rowId).remove();
            actionContentHtml = $('.action-timeline').find('#action-taken-'+rowId).html();
            actionContent = $('.action-timeline').find('#action-taken-'+rowId).html();
            
            $('.action-timeline').find('#action-taken-'+rowId).html('<form method="post" class="edit-action-form"><input type="hidden" name="task_id" value="'+task_id+'"><input type="hidden" name="action_taken_id" value="'+rowId+'"><input type="hidden" name="_token" value="{{csrf_token()}}"><textarea class="form-control actionTaken'+rowId+'" name="action_taken" id="'+rowId+'" style="min-height: 150px;">'+actionContent+'</textarea>' +
                '<button type="button" class="btn btn-default btn-xs cancel" data-id="'+task_id+'" value="'+rowId+'">Cancel</button> <button type="submit" class="btn btn-success btn-xs save" data-id="'+task_id+'" value="'+rowId+'">Save</button></form>');
            action_taken_editor(rowId);
        });

        $(document).on('click','.cancel',function(){
            let id = this.value;
            let task_id = $('.get_task_id').val();
            actionEditHtml = '<button type="button" class="btn btn-primary btn-xs edit-action-taken" data-id="'+task_id+'" value="'+id+'">Edit</button>';
            actionDeleteHtml = '<button type="button" class="btn btn-danger btn-xs delete-action-taken" data-id="'+task_id+'" value="'+id+'">Delete</button>';

            var actions;
            if (super_admin_action != '') {
                actions = actionEditHtml+actionDeleteHtml;
            } else if (user_action_id == creator_action) {
                actions = actionEditHtml;
            }
            $('.action-timeline').find('#action-taken-'+id)
                .html(`<div class="timeline-body" id="action-taken-${id}">${actionContentHtml}</div>
                    <div class="timeline-footer" id="action-btn-${id}">
                        ${actions}
                    </div>`);
        });

        $(document).on('submit','.edit-action-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            let id = $('input[name=action_taken_id]').val();
            let task_id = $('.get_task_id').val();

            $.ajax({
                'url' : '/action-taken/'+id,
                'type' : 'PUT',
                'data' : data,
                beforeSend: function(){
                    $('.edit-action-form').find('button,textarea').attr('disabled',true);
                    $('.edit-action-form').find('button .save').text('Saving...');
                },success: function(output){
                    if(output.success === true){
                        actionContent = output.actionContent;
                        customAlert('success',output.message);

                        action_edit = '<button type="button" class="btn btn-primary btn-xs edit-action-taken" data-id="'+task_id+'" value="'+id+'">Edit</button>';
                        action_delete = '<button type="button" class="btn btn-danger btn-xs delete-action-taken" data-id="'+task_id+'" value="'+id+'">Delete</button>';

                        var actions = '';
                        if (super_admin_action != '') {
                            actions = action_edit+action_delete;
                        } else if (user_action_id == creator_action) {
                            actions = action_edit;
                        }
                        
                        $('.action-timeline').find('#action-taken-'+id)
                            .html(`<div class="timeline-body" id="action-taken-${id}">${actionContent}</div>
                            <div class="timeline-footer" id="action-btn-${id}">
                                ${actions}
                            </div>`);
                    }else if(output.success === false){
                        customAlert('warning',output.message);
                    }

                    $('.edit-action-form').find('button,textarea').attr('disabled',false);
                    $('.edit-action-form').find('button .save').text('Save');
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });


        $(document).on('submit','#action-taken-form',function(form){
            form.preventDefault();
            
            let data = $(this).serializeArray();
            $.ajax({
                'url' : '{{route('action-taken.store')}}',
                'type' : 'POST',
                'data' : data,
                beforeSend: function(){
                    $('#action-taken-form').find('input,textarea').attr('disabled',true);
                    $('#action-taken-form').find('input[type=submit]').val('Saving...');
                },success: function(output){
                    console.log(output);
                    if(output.success === true){
                        customAlert('success',output.message);
                        $('textarea[name=action]').summernote("code", "");
                        $('#action-taken').modal('toggle');

                        let table = $('#check-list').DataTable();
                        table.ajax.reload(null, false);

                        let tableLog = $('#activity-log').DataTable();
                        tableLog.ajax.reload(null, false);
                    }else if(output.success === false){
                        customAlert('warning',output.message);
                    }

                    $('#action-taken-form').find('input,textarea').attr('disabled',false);
                    $('#action-taken-form').find('input[type=submit]').val('Save');
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        })

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

        $(document).on('click','button[name=start_task]',function(){
            let id = this.value;
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
                    } else if (get_status == 'on-going') {
                        $('.ongoing_task_component_user_span').removeClass('hidden');

                        if (!$('.start_task_component_user_span').hasClass("hidden")) {
                            $('.start_task_component_user_span').addClass('hidden');
                        }

                        if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                            $('.completed_task_component_user_span').addClass('hidden');
                        }
                    } else if (get_status == 'completed' && response.actions == 'complete') {
                        @if(auth()->user()->hasRole(['super admin','admin','account manager']))
                            $('.completed_task_component_user_span').removeClass('hidden');
                        @else
                            $('.delete-action-taken').attr('disabled',true);
                            $('.submit-checklist-btn').attr('disabled',true);

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
                    }
                        
                    if (response.actions == 'incomplete' && response.status == 'completed') {
                        alert('Checklist Action taken must have atleast 1 data. Please Check each checklist action taken before completed the Task.');
                    }

                    $('.task-action-button').find('button').attr('disabled',false);

                    let table = $('#check-list').DataTable();
                    table.ajax.reload(null, false);

                    let tableLog = $('#activity-log').DataTable();
                    tableLog.ajax.reload(null, false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });

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

                        let tableList = $('#check-list').DataTable();
                        tableList.ajax.reload(null, false);

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
                        $('.start_task_component_user_span').removeClass('hidden');

                        if (!$('.ongoing_task_component_user_span').hasClass("hidden")) {
                            $('.ongoing_task_component_user_span').addClass('hidden');
                        }

                        if (!$('.completed_task_component_user_span').hasClass("hidden")) {
                            $('.completed_task_component_user_span').addClass('hidden');
                        }
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
                    // { data: 'action', name: 'action', orderable: false, searchable: false}
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
                                }
                            } else if (request == 'remove') {
                                if (type == 'approved') {
                                    var $select = $('#watchers');
                                    var idToRemove = user_id;

                                    var values = $select.val();
                                    if (values) {
                                        var i = values.indexOf(idToRemove);
                                        if (i <= 0) {
                                            values.splice(i, 1);
                                            $select.val(values).change();
                                        }
                                    }
                                    $('.watchersButton').attr('disabled',true);
                                }
                            }
                            
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });

                }
            });
        });
        $('#action-taken').on('hidden.bs.modal',function(){
            let table = $('#check-list').DataTable();
            table.ajax.reload(null, false);

            let tableLog = $('#activity-log').DataTable();
            tableLog.ajax.reload(null, false);
        });
    </script>
@stop
