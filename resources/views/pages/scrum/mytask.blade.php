@extends('adminlte::page')

@section('title', 'My Tasks')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">My Tasks</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('tasks.index')}}">Tasks</a> </li>
                <li class="breadcrumb-item active">My Tasks</li>
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
            <select class="select2" name="statusChange" style="width: 150px;">
                <option value="">All</option>
                <option value="pending" @if(\Illuminate\Support\Facades\Session::get('statusMyTask') === 'pending') selected @endif>Pending</option>
                <option value="on-going" @if(\Illuminate\Support\Facades\Session::get('statusMyTask') === 'on-going') selected @endif>On-going</option>
                <option value="completed" @if(\Illuminate\Support\Facades\Session::get('statusMyTask') === 'completed') selected @endif>Completed</option>
            </select>

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="task-list" class="table table-bordered table-striped" role="grid">
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
            <form role="form" id="edit-task-form">
                @csrf
                <input type="hidden" name="task_id">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Task</h4>
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
                                <textarea class="form-control" id="description" name="description" style="min-height:300px;"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group due_date">
                                        <label for="due_date">Due Date</label>
                                        <input type="date" name="due_date" class="form-control" id="due_date" min="{{now()->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" name="time" class="form-control" id="time">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group priority">
                                        <label for="priority">Priority</label>
                                        <select name="priority" class="form-control select2" id="priority" style="width: 100%">
                                            <option value=""></option>
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
                                            <option value=""></option>
                                            @foreach($agents as $agent)
                                                <option value="{{$agent->id}}">{{$agent->fullname}}</option>
                                            @endforeach
                                        </select>
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

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <style>
        .tox-statusbar__branding {
            display: none;
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
    <script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_APP_KEY') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(function() {
            tinymce.init({
                selector: '#description',
                plugins: "emoticons image link lists charmap table", 
                toolbar: "fontsizeselect | bold italic underline strikethrough | forecolor backcolor | h1 h2 h3 | bullist numlist | alignleft aligncenter alignright | link image emoticons charmap hr | indent outdent | superscript subscript | removeformat",
                toolbar_mode: 'wrap',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            });

            $('#task-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('my.tasks.list') !!}',
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'due_date', name: 'due_date'},
                    { data: 'title', name: 'title'},
                    { data: 'priority_id', name: 'priority_id'},
                    { data: 'assigned_to', name: 'assigned_to'},
                    { data: 'created_by', name: 'created_by'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc']
            });
        });

        $('#date_active').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        }).datepicker("setDate", new Date());

        //Initialize Select2 Elements
        $('.select2').select2();

        $(document).on('change','select[name=statusChange]',function(){
            let value = this.value;
            $.ajax({
                'url' : '{{route('display.my.task.change')}}',
                'type' : 'POST',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {
                    'status' : value
                },beforeSend: function(){

                },success: function(response){
                    let table = $('#task-list').DataTable();
                    table.ajax.reload();
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });

        @can('edit task')
        let taskModal = $('#add-task-modal');
        $(document).on('click','.edit-task-btn',function(){
            let id = this.id;

            taskModal.find('input[name=task_id]').val(id);
            $('#add-task-modal').modal('toggle');
            $.ajax({
                'url' : '/tasks/'+id,
                'type' : 'GET',
                beforeSend: function(){

                },success: function(result){
                    console.log(result);
                    taskModal.find('input[name=title]').val(result.title);
                    tinyMCE.get('description').setContent(result.description);
                    taskModal.find('input[name=due_date]').val(result.due_date);
                    taskModal.find('input[name=time]').val(result.time);
                    taskModal.find('select[name=priority]').val(result.priority_id).change();
                    taskModal.find('select[name=assign_to]').val(result.assigned_to).change();

                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });

        $(document).on('submit','#edit-task-form',function(form){
            form.preventDefault();

            let data = $(this).serializeArray();
            $.ajax({
                'url' : '/tasks/'+data[1].value,
                'type' : 'PUT',
                'data' : data,
                beforeSend: function(){
                    $('#edit-task-form input, #edit-task-form select, #edit-task-form textarea').attr('disabled',true);
                },success: function(result){
                    if(result.success === true)
                    {
                        customAlert('success',result.message);
                        let table = $('#task-list').DataTable();
                        table.ajax.reload();
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

        @if(auth()->user()->can('delete task'))
        $(document).on('click','.delete-task-btn',function(){
            let id = this.id;
            console.log(id);
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
                                table.ajax.reload();

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
    </script>

@stop
