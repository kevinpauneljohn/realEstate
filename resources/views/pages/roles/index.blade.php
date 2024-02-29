@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add role')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-role-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="roles-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Role</th>
                        <th width="20%">Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add role')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-role-modal">
            <form role="form" id="role-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Role</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group role">
                                <label for="role">Role Name</label><span class="required">*</span>
                                <input type="text" name="role" class="form-control" id="role">
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
@section('right-sidebar')
    <x-custom.right-sidebar />
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
    @can('view role')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/role.js')}}"></script>
        <script>
            $(function() {
                $('#roles-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('roles.list') !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });
        </script>
    @endcan
@stop
