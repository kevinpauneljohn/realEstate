@extends('adminlte::page')

@section('title', 'Contest')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add role')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-contest-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="contest-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Name</th>
                        <th>Description</th>
                        <th>Active</th>
                        <th>Date Active</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Active</th>
                        <th>Date Active</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add contest')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-contest-modal">
            <form role="form" id="contest-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Contest</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch" name="is_active" value="1">
                                    <label class="custom-control-label" for="customSwitch">Active</label>
                                </div>
                            </div>
                            <div class="form-group title">
                                <label for="title">Title</label><span class="required">*</span>
                                <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label><span class="required">*</span>
                                <textarea name="description" class="form-control" id="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label><span class="required">*</span>
                                <input type="text" name="date_active" class="form-control datemask" id="date_active" value="{{today()->format('Y-m-d')}}" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask="" im-insert="false">
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group amount">
                                        <label for="amount">Amount</label><span class="required">*</span>
                                        <input type="number" name="amount" class="form-control" id="amount" step="0.1" value="0">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group points">
                                        <label for="points">Points</label><span class="required">*</span>
                                        <input type="number" name="points" class="form-control" id="points" step="0.1" value="0">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group item">
                                        <label for="item">Item</label>
                                        <input type="text" name="item" class="form-control" id="item">
                                    </div>
                                </div>
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
        <script src="{{asset('js/contest.js')}}"></script>
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
        </script>
    @stop
