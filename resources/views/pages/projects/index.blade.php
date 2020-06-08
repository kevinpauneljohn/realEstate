@extends('adminlte::page')

@section('title', 'Projects')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Projects</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                @can('add project')
                    <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-project-modal"><i class="fa fa-plus-circle"></i> Add Project</button>
                @endcan

            </div>
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table id="projects-list" class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Project Name</th>
                            <th>Address</th>
                            <th>Model Units</th>
                            <th>Commission Rate</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Project Name</th>
                            <th width="40%">Address</th>
                            <th>Model Units</th>
                            <th>Commission Rate</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @can('add project')
        <!--add new users modal-->
        <div class="modal fade" id="add-new-project-modal">
            <form role="form" id="add-project-form" class="form-submit">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group name">
                                <label for="name">Project Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group address">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="address" id="address"></textarea>
                            </div>
                            <div class="form-group remarks">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" placeholder="Place some text here"></textarea>
                            </div>
                            <div class="form-group commission_rate">
                                <label for="commission_rate">Commission Rate</label>
                                <input type="number" name="commission_rate" class="form-control" id="commission_rate" min="1">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary project-form-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new user modal-->
    @endcan

    @can('edit project')
        <!--edit role modal-->
        <div class="modal fade" id="edit-project-modal">
            <form role="form" id="edit-project-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="updateProjectId" id="updateProjectId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Project</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_name">
                                <label for="edit_name">Project Name</label>
                                <input type="text" name="edit_name" class="form-control" id="edit_name">
                            </div>
                            <div class="form-group edit_address">
                                <label for="edit_address">Address</label>
                                <textarea class="form-control" name="edit_address" id="edit_address"></textarea>
                            </div>
                            <div class="form-group edit_remarks">
                                <label for="edit_remarks">Remarks</label>
                                <textarea name="edit_remarks" id="edit_remarks" class="form-control" placeholder="Place some text here"></textarea>
                            </div>
                            <div class="form-group edit_commission_rate">
                                <label for="edit_commission_rate">Commission Rate</label>
                                <input type="number" name="edit_commission_rate" class="form-control" id="edit_commission_rate" min="1">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary edit-form-btn" value="Save">
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add user modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <style type="text/css">

    </style>
@stop

@section('js')
    @can('view user')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/project.js')}}"></script>
        <script>
            $(function() {
                $('#projects-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('projects.list') !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'address', name: 'address'},
                        { data: 'model_units', name: 'model_units'},
                        { data: 'commission_rate', name: 'commission_rate'},
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
