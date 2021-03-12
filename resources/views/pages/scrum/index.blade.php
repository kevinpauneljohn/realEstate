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
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Request Title: <span class="text-info">{{ucwords($task->title)}}</span></h3>
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

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
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
        let rowId;
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
                let value = this.value;

                $.ajax({
                    'url' : '/task-checklist/'+value,
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    'type' : 'PUT',
                    beforeSend: function(){
                        $('#check-list').find('input').attr('disabled',true);
                    },success: function (response){
                        if(response.success === true)
                        {
                            customAlert('success',response.message);
                        }
                        $('#check-list').find('input').attr('disabled',false);
                    },error: function(xhr, status, error){
                        console.log(xhr);
                    }
                });
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
                rowId = this.id;
                let tr = $(this).closest('tr');

                let data = tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                console.log(data);
                $('#edit-checklist-form').find('input[name=checklist_id]').val(rowId);
                $('#edit-checklist-form').find('textarea[name=checklist]').val(data[0]);
            });

            $(document).on('submit','#edit-checklist-form',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();
                $.ajax({
                    'url' : '/task-checklist/'+rowId+'/update/checklist',
                    'type' : 'PUT',
                    'data' : data,
                    beforeSend: function(){
                        $('#edit-checklist-form').find('input,textarea').attr('disabled',true);
                        $('#edit-checklist-form').find('input[type=submit]').val('Saving...');
                    },success: function(output){
                        console.log(output);
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
    </script>
@stop
