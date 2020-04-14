@extends('adminlte::page')

@section('title', 'Actions')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Actions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Actions</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add action')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-action-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="action-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>id</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th width="5%">id</th>
                        <th width="30%">Action</th>
                        <th width="40%" Description></th>
                        <th width="15%">Priority</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add action')
        <!--add new priority modal-->
        <div class="modal fade" id="add-action-modal">
            <form role="form" id="add-action-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Action</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group action">
                                <label for="action">Action</label><span class="required">*</span>
                                <input type="text" name="action" class="form-control" id="action">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea class="form-control" name="description" id="description"></textarea>
                            </div>
                            <div class="form-group priority">
                                <label for="priority">Priority</label><span class="required">*</span>
                                <select class="form-control select2" name="priority" id="priority" style="width:100%">
                                    <option value=""> -- Select -- </option>
                                    @foreach($priorities as $priority)
                                        <option value="{{$priority->id}}">{{ucfirst($priority->name)}}</option>
                                    @endforeach
                                </select>
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
        <!--end add new priority modal-->
    @endcan

    @can('edit action')
        <!--edit action modal-->
        <div class="modal fade" id="edit-action-modal">
            <form role="form" id="edit-action-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="actionId" id="actionId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Action</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_action">
                                <label for="edit_action">Action</label><span class="required">*</span>
                                <input type="text" name="edit_action" class="form-control" id="edit_action">
                            </div>
                            <div class="form-group edit_description">
                                <label for="edit_description">Description</label><span class="required">*</span>
                                <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                            </div>
                            <div class="form-group edit_priority">
                                <label for="edit_priority">Priority</label><span class="required">*</span>
                                <select class="form-control select2" name="edit_priority" id="edit_priority" style="width:100%">
                                    <option value=""> -- Select -- </option>
                                    @foreach($priorities as $priority)
                                        <option value="{{$priority->id}}">{{ucfirst($priority->name)}}</option>
                                    @endforeach
                                </select>
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
    @endcan

    @can('delete action')
        <!--delete priority-->
        <div class="modal fade" id="delete-priority-modal">
            <form role="form" id="delete-priority-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deletePriorityId" id="deletePriorityId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_role">Delete Priority: <span class="delete-priority-name"></span></p>
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
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('js/validation.js')}}"></script>
    <script src="{{asset('js/action.js')}}"></script>
    @can('view sales')
        <script>
            $(function() {
                $('#action-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('actions.list') !!}',
                    columns: [
                        { data: 'id', name: 'id'},
                        { data: 'name', name: 'name'},
                        { data: 'description', name: 'description'},
                        { data: 'priority_id', name: 'priority_id'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
    @endcan
@stop
