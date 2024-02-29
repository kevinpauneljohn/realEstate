@extends('adminlte::page')

@section('title', 'Model Units')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Model Units</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Model Units</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add model unit')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-unit-modal"><i class="fa fa-plus-circle"></i> Add Model Unit</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="model-unit-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>House Type</th>
                        <th>Floor Level</th>
                        <th>Lot Area</th>
                        <th>Floor Area</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Project</th>
                        <th>Model Unit</th>
                        <th>House Type</th>
                        <th>Floor Level</th>
                        <th>Lot Area</th>
                        <th>Floor Area</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add model unit')
        <!--add new sales modal-->
        <div class="modal fade" id="add-new-unit-modal">
            <form role="form" id="add-unit-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Model Unit</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group project">
                                        <label for="project">Project</label><span class="required">*</span>
                                        <select name="project" id="project" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group name">
                                        <label for="name">Model Unit</label><span class="required">*</span>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                    <div class="form-group house_type">
                                        <label for="house_type">House Type</label><span class="required">*</span>
                                        <select name="house_type" id="house_type" class="form-control select2" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            <option value="Lot">Lot</option>
                                            <option value="Single-attached">Single-attached</option>
                                            <option value="Single-detached">Single-detached</option>
                                            <option value="Bungalow">Bungalow</option>
                                            <option value="Duplex">Duplex</option>
                                            <option value="Townhouse">Townhouse</option>
                                            <option value="Rowhouse">Rowhouse</option>
                                            <option value="Condominium">Condominium</option>
                                        </select>
                                    </div>
                                    <div class="form-group floor_level">
                                        <label for="floor_level">Floor Level</label><span class="required">*</span>
                                        <input type="number" name="floor_level" id="floor_level" class="form-control" min="0">
                                    </div>
                                    <div class="form-group lot_area">
                                        <label for="lot_area">Lot Area</label><span class="required">*</span>
                                        <input type="number" name="lot_area" id="lot_area" class="form-control" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group floor_area">
                                        <label for="floor_area">Floor Area</label><span class="required">*</span>
                                        <input type="number" name="floor_area" id="floor_area" class="form-control" step="0.01" min="0">
                                    </div>
                                    <div class="form-group description">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
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
                                <textarea name="edit_remarks" id="edit_remarks" class="textarea" data-min-height="150" placeholder="Place some text here"></textarea>
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
        <!--end add user modal-->
    @endcan

    @can('delete project')
        <!--delete user-->
        <div class="modal fade" id="delete-project-modal">
            <form role="form" id="delete-project-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteProjectId" id="deleteProjectId">
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_project">Delete Project: <span class="delete-project-name"></span></p>
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
        <!--end delete user modal-->
    @endcan
@stop
@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('js/model_unit.js')}}"></script>
    <!-- Summernote -->
    <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
    <script>

        $(function () {
            // Summernote
            $('.textarea').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['view', ['fullscreen']],
                ],
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });
        })
    </script>
    <script>
        $(function() {
            $('#model-unit-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('model.units.list') !!}',
                columns: [
                    { data: 'project_name', name: 'project_name'},
                    { data: 'name', name: 'name'},
                    { data: 'house_type', name: 'house_type'},
                    { data: 'floor_level', name: 'floor_level'},
                    { data: 'lot_area', name: 'lot_area'},
                    { data: 'floor_area', name: 'floor_area'},
                    { data: 'description', name: 'description'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });
        //Initialize Select2 Elements
        $('.select2').select2();
        $('#reservation_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    </script>
@stop
