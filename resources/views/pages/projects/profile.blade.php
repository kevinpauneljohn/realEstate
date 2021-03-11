@extends('adminlte::page')

@section('title', 'Project Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Project Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a> </li>
                <li class="breadcrumb-item active">Project Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-12 col-lg-9 order-2 order-md-1">
            <div class="card">
                <div class="card-header">
                    @can('add model unit')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-new-model-modal">Add Model Unit</button>
                    @endcan
                    @can('view project')
                        <a href="{{route('projects.index')}}" class="btn btn-success btn-sm">All Projects</a>
                    @endcan
                </div>
                <div class="card-body">

                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="model-units-list" class="table table-bordered table-striped" role="grid">
                            <thead>
                            <tr role="row">
                                <th>Model</th>
                                <th>House Type</th>
                                <th>Floor Level</th>
                                <th>Lot Area</th>
                                <th>Floor Area</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th>Model</th>
                                <th>House Type</th>
                                <th>Floor Level</th>
                                <th>Lot Area</th>
                                <th>Floor Area</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-3 order-1 order-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-primary">{{$project->name}}</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ucfirst($project->remarks)}}</p>
                    <br>
                    <div class="text-muted">
                        <p class="text-sm">Address
                            <b class="d-block">{{ucfirst($project->address)}}</b>
                        </p>
                        <p class="text-sm">Commission Rate
                            <b class="d-block">{{$project->commission_rate}}%</b>
                        </p>
                    </div>

                    {{--                    <h5 class="mt-5 text-muted">Project files</h5>--}}
                    {{--                    <ul class="list-unstyled">--}}
                    {{--                        <li>--}}
                    {{--                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>--}}
                    {{--                        </li>--}}
                    {{--                        <li>--}}
                    {{--                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>--}}
                    {{--                        </li>--}}
                    {{--                        <li>--}}
                    {{--                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>--}}
                    {{--                        </li>--}}
                    {{--                        <li>--}}
                    {{--                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>--}}
                    {{--                        </li>--}}
                    {{--                        <li>--}}
                    {{--                            <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>--}}
                    {{--                        </li>--}}
                    {{--                    </ul>--}}
                    {{--                    <div class="text-center mt-5 mb-3">--}}
                    {{--                        <a href="#" class="btn btn-sm btn-primary">Add files</a>--}}
                    {{--                        <a href="#" class="btn btn-sm btn-warning">Report contact</a>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

    @can('add model unit')
        <!--add new model modal-->
        <div class="modal fade" id="add-new-model-modal">
            <form role="form" id="add-model-form" class="form-submit">
                @csrf
                <input type="hidden" name="project_id" value="{{$project->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Model</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group model_name">
                                <label for="model_name">Model Name</label><span class="required">*</span>
                                <input type="text" name="model_name" class="form-control" id="model_name">
                            </div>
                            <div class="form-group house_type">
                                <label for="house_type">House Type</label><span class="required">*</span>
                                <select name="house_type" id="house_type" class="form-control" style="width: 100%;">
                                    <option value=""> -- Select -- </option>
                                    <option value="Single-attached">Single-attached</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Townhouse">Townhouse</option>
                                    <option value="Rowhouse">Rowhouse</option>
                                    <option value="Condominium">Condominium</option>
                                    <option value="Lot">Lot</option>
                                </select>
                            </div>
                            <div class="form-group floor_level">
                                <label for="floor_level">Floor Level</label><span class="required">*</span>
                                <select class="form-control" name="floor_level" id="floor_level">
                                    <option value=""> -- Select -- </option>
                                    <option value="Lot">Lot</option>
                                    <option value="Bungalow">Bungalow</option>
                                    <option value="Two-storey">Two-storey</option>
                                    <option value="Three-storey">Three-storey</option>
                                    <option value="Four-storey">Four-storey</option>
                                    <option value="Five-storey">Five-storey</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group lot_area">
                                        <label for="lot_area">Lot Area</label><span class="required">*</span>
                                        <input type="number" name="lot_area" class="form-control" id="lot_area" step="any">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group floor_area">
                                        <label for="floor_area">Floor Area</label><span class="required">*</span>
                                        <input type="number" name="floor_area" class="form-control" id="floor_area" step="any">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group photo_url">
                                <label for="photo_url">Facebook Photo URL</label><span class="required">*</span>
                                <input type="text" name="photo_url" class="form-control" id="photo_url">
                            </div>
                            <div class="form-group remarks">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control"  placeholder="Place some text here"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-model-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new model modal-->
    @endcan

    @can('edit model unit')
        <!--edit model modal-->
        <div class="modal fade" id="edit-model-modal">
            <form role="form" id="edit-model-form" class="form-submit">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_project_id" value="{{$project->id}}">
                <input type="hidden" name="model_id" id="model_id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Model</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_model_name">
                                <label for="edit_model_name">Model Name</label><span class="required">*</span>
                                <input type="text" name="edit_model_name" class="form-control" id="edit_model_name">
                            </div>
                            <div class="form-group edit_house_type">
                                <label for="edit_house_type">House Type</label><span class="required">*</span>
                                <select name="edit_house_type" id="edit_house_type" class="form-control" style="width: 100%;">
                                    <option value=""> -- Select -- </option>
                                    <option value="Single-attached">Single-attached</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Townhouse">Townhouse</option>
                                    <option value="Rowhouse">Rowhouse</option>
                                    <option value="Condominium">Condominium</option>
                                </select>
                            </div>
                            <div class="form-group edit_floor_level">
                                <label for="edit_floor_level">Floor Level</label><span class="required">*</span>
                                <select class="form-control" name="edit_floor_level" id="edit_floor_level">
                                    <option value=""> -- Select -- </option>
                                    <option value="Bungalow">Bungalow</option>
                                    <option value="Two-storey">Two-storey</option>
                                    <option value="Three-storey">Three-storey</option>
                                    <option value="Four-storey">Four-storey</option>
                                    <option value="Five-storey">Five-storey</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group edit_lot_area">
                                        <label for="edit_lot_area">Lot Area</label><span class="required">*</span>
                                        <input type="number" name="edit_lot_area" class="form-control" id="edit_lot_area" step="0.1">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group edit_floor_area">
                                        <label for="edit_floor_area">Floor Area</label><span class="required">*</span>
                                        <input type="number" name="edit_floor_area" class="form-control" id="edit_floor_area" step="0.1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group edit_photo_url">
                                <label for="edit_photo_url">Facebook Photo URL</label><span class="required">*</span>
                                <input type="text" name="edit_photo_url" class="form-control" id="edit_photo_url">
                            </div>
                            <div class="form-group edit_remarks">
                                <label for="edit_remarks">Remarks</label>
                                <textarea name="edit_remarks" id="edit_remarks" class="form-control"  placeholder="Place some text here"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary edit-model-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new model modal-->
    @endcan

    @can('view model unit')
        <div class="modal fade" id="view-model-unit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">View Model Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
        </div>
    @endcan
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('/vendor/timepicker/bootstrap-timepicker.min.css')}}">
    <style type="text/css">

    </style>
@stop

@section('js')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- bootstrap datepicker -->
        <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('/vendor/timepicker/bootstrap-timepicker.min.js')}}"></script>
        <script src="{{asset('js/validation.js')}}"></script>
        <script src="{{asset('js/model_unit.js')}}"></script>

        <script>
            $(function() {
                $('#model-units-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('projects.model.units.list',['project_id' => $project->id]) !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'house_type', name: 'house_type'},
                        { data: 'floor_level', name: 'floor_level'},
                        { data: 'lot_area', name: 'lot_area'},
                        { data: 'floor_area', name: 'floor_area'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
        </script>
@stop
