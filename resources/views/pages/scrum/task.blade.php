@extends('adminlte::page')

@section('title', 'Tasks')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Tasks</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Tasks</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Open</span>
                    <span class="info-box-number">{{$status['open']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Pending</span>
                    <span class="info-box-number">{{$status['pending']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-maroon elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">On-going</span>
                    <span class="info-box-number">{{$status['ongoing']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-ticket-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Completed</span>
                    <span class="info-box-number">{{$status['completed']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <select class="select2" name="statusChange" id="statusChange" style="width: 200px;">
                        <option value="">All</option>
                        <option value="pending" @if(\Illuminate\Support\Facades\Session::get('status') === 'pending') selected @endif>Pending</option>
                        <option value="on-going" @if(\Illuminate\Support\Facades\Session::get('status') === 'on-going') selected @endif>On-going</option>
                        <option value="completed" @if(\Illuminate\Support\Facades\Session::get('status') === 'completed') selected @endif>Completed</option>
                    </select>
                </div>
                <div class="col-md-6">
                    @can('add task')
                        <button type="button" class="btn bg-gradient-primary btn-sm add-new-task mr-1 float-right" data-toggle="modal" data-target="#add-task-modal"><i class="fa fa-plus-circle"></i> Add New</button>
                    @endcan
                    <!-- @can('view task export')
                    <button type="button" class="btn bg-gradient-success btn-sm add-new-task mr-1 float-right" id="exportTasks"><i class="fa fa-arrow-circle-down"></i> Export</button>
                    @endcan -->
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="task-list" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Task #</th>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Action Taken</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Task #</th>
                        <th>Due Date</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Creator</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Action Taken</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add task')
        <!--add new roles modal-->
        <div class="modal fade" id="add-task-modal">
            <form role="form" id="task-form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Task</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea class="textEditor" name="description" id="description" style="min-height:300px;"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group due_date">
                                        <label for="due_date">Due Date</label>
                                        <input type="date" value="{{date('Y-m-d')}}" name="due_date" class="form-control" id="due_date" min="{{now()->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" value="{{date('H:i')}}" name="time" class="form-control" id="time">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group priority">
                                        <label for="priority">Priority</label>
                                        <select name="priority" class="form-control select2" id="priority" style="width: 100%">
                                            <option value="">Please Select</option>
                                            @foreach($priorities as $priority)
                                                <option value="{{$priority->id}}">{{$priority->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group assign_to">
                                        <label for="assign_to">Assign To</label>
                                        <select name="assign_to" class="form-control select2" id="assign_to" style="width: 100%">
                                            <option value="">Please Select</option>
                                            @foreach($agents as $agent)
                                                <option value="{{$agent->id}}">{{$agent->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group watchers">
                                        <label for="watchers">Watchers</label>
                                        <select name="watchers[]" multiple class="form-control" id="watchers" style="width: 100%" required>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->username}} [{{$user->firstname}} {{$user->lastname}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group privacy">
                                        <label for="watchers">Ticket Privacy</label>
                                        <div class="custom-control custom-switch toggle_private">
                                            <input type="checkbox" checked name="privacy" class="custom-control-input" id="privacy">
                                            <label  style="cursor: pointer;" class="custom-control-label" for="privacy">Toggle to set ticket in Public</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-task-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new roles modal-->
    @endcan

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
        .tox-statusbar__branding {
            display: none;
        }

        .tox-notifications-container{
            display:none !important;
        }
        .due_dates {
            background-color: #ffe4e4 !important;
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
    <script src="{{asset('js/validation.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
    <script>
        $(function() {
            //getTask();
            $("#watchers").select2({
                minimumResultsForSearch: 20
            });

            $('.textEditor').summernote({
                followingToolbar: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen']],
                ],
                lineHeights: ['1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });

            $('#task-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('tasks.list') !!}',
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'due_date', name: 'due_date'},
                    { data: 'title', name: 'title'},
                    { data: 'priority_id', name: 'priority_id'},
                    { data: 'assigned_to', name: 'assigned_to'},
                    { data: 'created_by', name: 'created_by'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'status', name: 'status', "className": "text-center"},
                    { data: 'action_taken', name: 'action_taken', "className": "text-center", orderable: false, searchable: false},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "createdRow": function( row, data, dataIndex ) {
                    if (
                        data['status_due'] <= data['date_today'] &&
                        data['status_text'] != 'completed'
                    ) {
                        $(row).addClass('due_dates');
                        console.log(data);
                    }
                },
                pageLength: 10,
                responsive:true,
                order:[0,'desc']
            });

            function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return true;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }
        });

        $('#date_active').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        }).datepicker("setDate", new Date());

        //Initialize Select2 Elements
        $('.select2').select2();
        @can('view task')
            $(document).on('click','.add-new-task',function(){
                $('#add-task-modal').find('.modal-title').text("Add New Task");
                $('#add-task-modal').find('form').attr('id','task-form').find('input[name=task_id]').remove();

                $('form#task-form').trigger("reset");
                $('form#task-form select').trigger("change");

                $('#watchers').val('{{auth()->user()->id}}').change();
                $('.textEditor').summernote("code", "");
            });

        $(document).on('click','#exportTasks',function(){
            var status = $('#statusChange').val();
            var id = status;
            if (status == '') {
                id = 'all';
            }

            var url = "{{URL::to('export-task')}}/" + id + "/" + "ticket"
            window.location = url;
        });

        $(document).on('submit','#task-form',function(form){
            form.preventDefault();

            let data = $(this).serializeArray();

            $.ajax({
                'url' : '/tasks',
                'type' : 'POST',
                'data' : data,
                beforeSend: function(){
                    $('.submit-task-btn').val('Saving ...').attr('disabled',true);
                },success: function(result){

                    if(result.success === true)
                    {
                        let table = $('#task-list').DataTable();
                        table.ajax.reload();
                        $('#task-form').trigger('reset');
                        $('#task-form #collaborator').empty();
                        toastr.success(result.message);

                        $('#add-task-modal').modal('toggle');
                        $('#assign_to, #priority').val([]).trigger('change');
                    }

                    $.each(result, function (key, value) {
                        let element = $('.'+key);

                        element.find('.error-'+key).remove();
                        element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                    });
                    $('.submit-task-btn').val('Save').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
            clear_errors('title','description','due_date','priority','assign_to');
        });
        @endcan

        $("#add-task-modal").on('hide.bs.modal', function () {
            $('.textEditor').summernote("code", "");
        });

        @can('edit task')
            let taskModal = $('#add-task-modal');
            $(document).on('click','.edit-task-btn',function(){
                let id = this.id;

                taskModal.find('input[name=task_id], .text-danger').remove();
                taskModal.find('.modal-title').text("Edit Task");
                taskModal.find('form').attr('id','edit-task-form').prepend('<input type="hidden" name="task_id" value="'+id+'">');
                $('#add-task-modal').modal('toggle');
                $.ajax({
                    'url' : '/tasks/'+id,
                    'type' : 'GET',
                    beforeSend: function(){

                    },success: function(result){
                        taskModal.find('input[name=title]').val(result.task.title);
                        taskModal.find('input[name=due_date]').val(result.task.due_date);
                        taskModal.find('input[name=time]').val(result.task.time);
                        taskModal.find('select[name=priority]').val(result.task.priority_id).change();
                        taskModal.find('select[name=assign_to]').val(result.task.assigned_to).change();
                        taskModal.find('textarea[name=description]').summernote('code', result.task.description);
                        if (result.task.privacy == 'on') {
                            $('.toggle_private label').text('Toggle to set ticket in Public');
                            taskModal.find('input[name=privacy]').prop('checked', true);
                        } else {
                            $('.toggle_private label').text('Toggle to set ticket in Private');
                            taskModal.find('input[name=privacy]').prop('checked', false);
                        }
                        var watcher_data = [];
                        $.each(result.watcher, function(i, record) {
                            watcher_data.push(record.user_id)
                        });

                        taskModal.find('#watchers').val(watcher_data).change();
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
            });

        $(document).on('submit','#edit-task-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                'url' : '/tasks/'+data[0].value,
                'type' : 'PUT',
                'data' : data,
                beforeSend: function(){
                    $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',true);
                },success: function(result){
                    console.log(result);
                    if(result.success === true)
                    {
                        customAlert('success',result.message);
                        let table = $('#task-list').DataTable();
                        table.ajax.reload(null, false);
                        $('#edit-task-modal').modal('toggle');
                    }else if(result.success === false)
                    {
                        customAlert('warning',result.message);
                    }

                    $.each(result, function (key, value) {
                        let element = $('.'+key);

                        element.find('.error-'+key).remove();
                        element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                    });

                    $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',false);

                    setTimeout(function() {
                        $('#add-task-modal').modal('hide');
                    }, 2000);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
            clear_errors('title','description','due_date','priority','assign_to');
        });
        @endcan

        $(document).on('change','select[name=statusChange]',function(){
            let value = this.value;
            $.ajax({
                'url' : '{{route('display.task.change')}}',
                'type' : 'POST',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {
                    'status' : value
                },beforeSend: function(){

                },success: function(response){
                    let table = $('#task-list').DataTable();
                    table.ajax.reload(null, false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });

        $(document).on('click','.request-task-watch',function(){
            let id = this.id;
            let action = $(this).attr('data-action');
            let data_id = $(this).attr('data-id');
            var text_title;
            if (action == 'watch') {
                text_title = 'You want to request to Remove task ticket #'+data_id+' under your watch?';
            } else if (action == 'unwatch') {
                text_title = 'You want to request to add task ticket #'+data_id+' under your watch?';
            }

            Swal.fire({
                title: 'Are you sure?',
                text: text_title,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, request it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'url' : '/tasks/action/watch/'+id+'/'+action,
                        'type' : 'GET',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(output){
                            if(output.success === true){
                                customAlert('success',output.message);
                                let table = $('#task-list').DataTable();
                                table.ajax.reload(null, false);

                            }else if(output.success === false){
                                customAlert('warning',output.message);
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                            //customAlert('error',"Task Constraints, There's an existing checklist created!");
                        }
                    });

                }
            });
        });

        @if(auth()->user()->can('delete task'))
            $(document).on('click','.delete-task-btn',function(){
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
                            'url' : '/tasks/'+id,
                            'type' : 'DELETE',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(output){
                                if(output.success === true){
                                    customAlert('success',output.message);
                                    let table = $('#task-list').DataTable();
                                    table.ajax.reload(null, false);

                                }else if(output.success === false){
                                    customAlert('warning',output.message);
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                                customAlert('error',"Task Constraints, There's an existing checklist created!");
                            }
                        });

                    }
                });
            });
        @endif

        $(document).on('change','#assign_to',function(){
            $('#privacy').prop('checked', true);
            $('.toggle_private label').text('Toggle to set ticket in Public');
            $.ajax({
                'url' : '/task-ojt',
                'type' : 'GET',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(output){
                    $.each(output.data, function(key, val) {
                        if ($('#assign_to').val() == val) {
                            $('.toggle_private label').text('Toggle to set ticket in Private');
                            $('#privacy').prop('checked', false);
                        }
                    });
                },error: function(xhr, status, error){
                    console.log(xhr);
                    //customAlert('error',"Task Constraints, There's an existing checklist created!");
                }
            });
        });
    </script>
@stop

