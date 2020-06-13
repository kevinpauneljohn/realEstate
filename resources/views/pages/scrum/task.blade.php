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
    <div class="card">
        <div class="card-header">
            @can('add task')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-task-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="contest-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Title</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Creator</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Creator</th>
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
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group priority">
                                        <label for="priority">Priority</label><span class="required">*</span>
                                        <select class="form-control" name="priority" id="priority">
                                            <option value=""> -- Select -- </option>
                                            @foreach($priorities as $priority)
                                                <option value="{{$priority->id}}">{{$priority->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group collaborator">
                                        <label for="collaborator">Collaborator</label><span class="required">*</span>
                                        <select class="form-control select2" multiple="multiple" name="collaborator[]" id="collaborator" style="width: 100%">
                                            <option value=""> -- Select -- </option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->fullname}}</option>
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

    @can('edit role')
        <!--edit role modal-->
        <div class="modal fade" id="edit-role-modal">
            <form role="form" id="edit-role-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="updateRoleId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Role Name</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="form-group edit_role">
                                    <label for="edit_role">Role Name</label><span class="required">*</span>
                                    <input type="text" name="edit_role" class="form-control" id="edit_role">
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-form-btn"><i class="spinner fa fa-spinner fa-spin"></i> Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add terminal modal-->
    @endcan

    @can('delete role')
        <!--delete terminal-->
        <div class="modal fade" id="delete-role-modal">
            <form role="form" id="delete-role-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteRoleId" id="deleteRoleId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_role">Delete Role: <span class="delete-role-name"></span></p>
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
        <!--end delete terminal modal-->
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
    <script src="{{asset('js/validation.js')}}"></script>
    <script src="{{asset('js/task.js')}}"></script>
    <script>
        $(function() {
            $('#contest-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('contest.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'active', name: 'active'},
                    { data: 'date_working', name: 'date_working'},
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
    </script>
@stop

