@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <h1>Permissions</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add permission')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-permission-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="permissions-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Permission</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Permission</th>
                        <th>Roles</th>
                        <th width="20%">Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add permission')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-permission-modal">
            <form role="form" id="permission-form">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group permission">
                                <label for="permission">Permission</label><span class="required">*</span>
                                <input type="text" name="permission" class="form-control" id="permission">
                            </div>

                            <div class="form-group roles">
                                <label>Assign Role</label>
                                <select class="select2" name="roles[]" multiple="multiple" data-placeholder="Select a role" style="width: 100%;">
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
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
        <!--end add new permission modal-->
    @endcan

    @can('edit permission')
        <!--edit role modal-->
        <div class="modal fade" id="edit-permission-modal">
            <form role="form" id="edit-permission-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="updatePermissionId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="form-group edit_role">
                                    <label for="edit_permission">Permission</label><span class="required">*</span>
                                    <input type="text" name="edit_permission" class="form-control" id="edit_permission">
                                </div>

                                <div class="form-group edit_roles">
                                    <label>Assign Role</label>
                                    <select class="select2" name="edit_roles[]" multiple="multiple" id="edit_roles" data-placeholder="Select a role" style="width: 100%;">
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add permission modal-->
    @endcan

    @can('delete permission')
        <!--delete permission-->
        <div class="modal fade" id="delete-permission-modal">
            <form role="form" id="delete-role-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deletePermissionId" id="deletePermissionId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_permission">Delete Permission: <span class="delete-permission-name"></span></p>
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
        <!--end delete permission modal-->
    @endcan
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @can('view permission')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/permission.js')}}"></script>
        <script>
            $(function() {
                $('#permissions-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('permission.list') !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'role', name: 'role'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });

            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
    @endcan
@stop
