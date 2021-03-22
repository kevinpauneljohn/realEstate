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
                                <p>{{ucfirst($task->description)}}</p>
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
                            <form id="update-assignee">
                                @csrf
                                @method('put')
                                <input type="hidden" name="task_id" value="{{$task->id}}">
                                <select class="form-control select" id="assigned_to" name="assigned_id">
                                    <option value=""></option>
                                    @foreach($agents as $agent)
                                        <option value="{{$agent->id}}" @if(!empty($task->user->fullname) && $agent->fullname === $task->user->fullname) selected @endif>{{$agent->fullname}}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-xs" style="width: 100%">update</button>
                            </form>
                        @else
                            {{$task->user->fullname ?? ''}}
                        @endif

                    </p>

                    <hr>

                    <strong><i class="fas fa-calendar-check mr-1"></i> Due Date</strong>

                    <p class="text-muted">
                        {{\Carbon\Carbon::parse($task->due_date)->format('M d, Y')}} - {{\Carbon\Carbon::parse($task->time)->format('g:i A')}}
                    </p>

                    <hr>

                    <strong><i class="fas fa-info-circle mr-1"></i> Priority</strong>

                    <p class="text-muted">{{$task->priority->name}}</p>
                </div>
            </div>

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
                            <table class="checklist-table">
                                <tr class="table-row-0">
                                    <td><textarea class="form-control" name="checklist[]"></textarea></td>
                                    <td><button type="button" class="btn btn-danger btn-xs remove" id="0" title="remove"><i class="fas fa-trash"></i></button></td>
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
                            <textarea class="form-control" name="checklist" style="min-height: 300px;"></textarea>
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
                            <textarea class="form-control" name="action" style="min-height: 200px;" id="action"></textarea>
                            <input type="submit" class="btn btn-primary submit-checklist-btn" value="Save">
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
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <style>
        #remarks-list_length, #remarks-list_filter, .dataTables_info{
            display:none;
        }
        .dataTables_wrapper {
            overflow-x: hidden;
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
    <script>
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
                        $('#update-assignee').find('select, button').attr('disabled',false);
                        $('#update-assignee').find('button').text('update');
                        customAlert('success',response.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        })

        $(function() {
            $('#check-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('checklist.display',$task->id) !!}',
                columns: [
                    { data: 'description', name: 'description'},
                    { data: 'completed', name: 'completed', orderable: false, searchable: false},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 25
            });
        });


        let checklistTable = $('.checklist-table');
        let checklistForm = $('#checklist-form');

        $(document).on('click','.add-row',function(){
            let tr = checklistTable.find('tr').length;

            checklistTable.append(`<tr class="table-row-${tr}">
                                    <td><textarea class="form-control" name="checklist[]"></textarea></td>
                                    <td><button type="button" class="btn btn-danger btn-xs remove" id="${tr}" title="remove"><i class="fas fa-trash"></i></button></td>
                                </tr>`)
        });

        $(document).on('click','.remove',function(){
            let id = this.id;
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
                        $('#checklist').modal('toggle');

                        let table = $('#check-list').DataTable();
                        table.ajax.reload();
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
                                table.ajax.reload();
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
                    return $(this).text();
                }).get();

                $('#edit-checklist-form').find('input[name=checklist_id]').val(checklist_id);
                $('#edit-checklist-form').find('textarea[name=checklist]').val(data[0]);
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
                            table.ajax.reload();
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

            function displayActionTaken()
            {
                $.ajax({
                    'url' : '/action-taken/'+checklist_id+'/display',
                    'type' : 'GET',
                    beforeSend: function(){
                        $('#action-taken-form').find("textarea").val("");
                    },success: function(response){
                        $('.action-timeline').html('<div class="timeline"></div>');

                        $.each(response, function(key, value){
                            let action = value.is_creator === true ? `<button type="button" class="btn btn-primary btn-xs edit-action-taken" value="${value.id}">Edit</button>
                                <button type="button" class="btn btn-danger btn-xs delete-action-taken" value="${value.id}">Delete</button>` : ``;
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
                                        @role("super admin")<div class="timeline-footer" id="action-btn-${value.id}">${action}</div>@endrole
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

        @if(auth()->user()->hasRole(["super admin"]))
        $(document).on('click','.edit-action-taken',function(){
            rowId = this.value;
            $('.action-timeline').find('#action-btn-'+rowId).remove();
            actionContentHtml = $('.action-timeline').find('#action-taken-'+rowId).html();
            actionContent = $('.action-timeline').find('#action-taken-'+rowId).text();

            $('.action-timeline').find('#action-taken-'+rowId).html('<form method="post" class="edit-action-form"><input type="hidden" name="action_taken_id" value="'+rowId+'"><input type="hidden" name="_token" value="{{csrf_token()}}"><textarea class="form-control" name="action_taken" id="'+rowId+'" style="min-height: 150px;">'+actionContent+'</textarea>' +
                '<button type="button" class="btn btn-default btn-xs cancel" value="'+rowId+'">Cancel</button> <button type="submit" class="btn btn-default btn-xs save" value="'+rowId+'">Save</button></form>');
        });
            @endif

        $(document).on('click','.cancel',function(){
            let id = this.value;

            $('.action-timeline').find('#action-taken-'+id)
                .html(`<div class="timeline-body" id="action-taken-${id}">${actionContentHtml}</div>
                    <div class="timeline-footer" id="action-btn-${id}">
                        <button type="button" class="btn btn-primary btn-xs edit-action-taken" value="${id}">Edit</button>
                        <button type="button" class="btn btn-danger btn-xs delete-action-taken" value="${id}">Delete</button>
                    </div>`);
        });

        $(document).on('submit','.edit-action-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            let id = $('input[name=action_taken_id]').val();
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

                        $('.action-timeline').find('#action-taken-'+id)
                            .html(`<div class="timeline-body" id="action-taken-${id}">${actionContent}</div>
                            <div class="timeline-footer" id="action-btn-${id}">
                                <button type="button" class="btn btn-primary btn-xs edit-action-taken" value="${id}">Edit</button>
                                <button type="button" class="btn btn-danger btn-xs delete-action-taken" value="${id}">Delete</button>
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
                        $('#action-taken-form').find('textarea[name=action]').val("");

                        $('#action-taken').modal('toggle');

                        let table = $('#check-list').DataTable();
                        table.ajax.reload();
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

        @role("super admin");
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
        @endrole

        $(document).on('click','button[name=start_task]',function(){
            let id = this.value;
            $.ajax({
                'url' : '/start-tasks/'+id,
                'type' : 'PUT',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    $('.task-action-button').find('button').attr('disabled',true);
                },success: function(response){
                    console.log(response);
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
                        table.ajax.reload();
                    }

                    $.each(response, function (key, value) {
                        let element = $('.'+key);

                        element.find('.error-'+key).remove();
                        element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                    });

                    $('#set-status-form').find('.remarks-btn').val('Update Status').attr('disabled',false);
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

        $('#action-taken').on('hidden.bs.modal',function(){
            let table = $('#check-list').DataTable();
            table.ajax.reload();
        });
    </script>
@stop
